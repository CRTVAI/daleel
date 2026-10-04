<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnterpriseContactController extends Controller
{
    public function send(Request $request)
    {
        if ($request->filled('website')) {
            return back()
                ->withInput()
                ->with('enterprise_contact_error', 'We could not send your message right now. Please try again.');
        }

        if (! $request->user() && ! $this->passesTurnstile($request)) {
            return back()
                ->withInput()
                ->withErrors(['cf-turnstile-response' => 'Please complete the captcha challenge.']);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'subject' => ['nullable', 'string', 'max:80'],
            'company' => ['nullable', 'string', 'max:160'],
            'size' => ['nullable', 'string', 'max:40'],
            'message' => ['nullable', 'string', 'max:5000'],
            'goal' => ['nullable', 'string', 'max:5000'],
        ]);

        $data['subject'] = $data['subject'] ?? 'enterprise';
        $data['message'] = $data['message'] ?? $data['goal'] ?? '';

        if (trim($data['message']) === '') {
            return back()
                ->withInput()
                ->withErrors(['message' => 'Please tell us a little about your request.']);
        }

        $subjectLabels = [
            'enterprise' => 'Enterprise Solutions',
            'support' => 'Technical Support',
            'partnership' => 'Partnership Inquiry',
            'other' => 'Other',
        ];

        $subject = $subjectLabels[$data['subject']] ?? $data['subject'];
        $body = implode("\n", [
            'New enterprise contact message',
            '',
            'Name: ' . $data['name'],
            'Work email: ' . $data['email'],
            'Company: ' . ($data['company'] ?? 'Not provided'),
            'Company size: ' . ($data['size'] ?? 'Not provided'),
            'Subject: ' . $subject,
            '',
            'Message:',
            $data['message'],
        ]);

        try {
            Mail::raw($body, function ($message) use ($data, $subject) {
                $message->to(config('mail.contact_to', env('CONTACT_TO_ADDRESS', 'anas@crtvai.com')))
                    ->replyTo($data['email'], $data['name'])
                    ->subject('Daleel contact: ' . $subject);
            });
        } catch (\Throwable $e) {
            Log::error('Enterprise contact email failed', [
                'error' => $e->getMessage(),
                'from' => $data['email'],
                'subject' => $subject,
            ]);

            return back()
                ->withInput()
                ->with('enterprise_contact_error', 'We could not send your message right now. Please try again in a moment.');
        }

        return back()->with('enterprise_contact_success', true);
    }

    private function passesTurnstile(Request $request): bool
    {
        $secretKey = config('services.turnstile.secret_key');

        if (! $secretKey) {
            return true;
        }

        $token = $request->input('cf-turnstile-response');

        if (! is_string($token) || trim($token) === '') {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(8)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => $secretKey,
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]);
        } catch (\Throwable $e) {
            Log::warning('Turnstile verification request failed', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }

        if (! $response->ok()) {
            Log::warning('Turnstile verification returned an error response', [
                'status' => $response->status(),
            ]);

            return false;
        }

        return (bool) $response->json('success');
    }
}
