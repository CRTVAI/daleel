<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EnterpriseContactController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'subject' => ['required', 'string', 'max:80'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

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
            'Subject: ' . $subject,~
            '',
            'Message:',
            $data['message'],
        ]);

        Mail::raw($body, function ($message) use ($data, $subject) {
            $message->to('anas@crtvai.com')
                ->replyTo($data['email'], $data['name'])
                ->subject('Daleel contact: ' . $subject);
        }); 

        return back()->with('enterprise_contact_success', true);
    }
}
