<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SubtitlesController extends Controller
{
    /**
     * Fetch SRT file from URL, convert to WebVTT on the fly, and stream it.
     */
    public function convert(Request $request)
    {
        $url = $request->get('url');

        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return response('Invalid subtitle URL.', 400);
        }

        try {
            // Fetch the original subtitle content (S3 or Local storage)
            $response = Http::timeout(10)->get($url);

            if (!$response->successful()) {
                return response('Failed to fetch subtitle file from storage.', 502);
            }

            $srtContent = $response->body();

            // Convert SRT to WebVTT on the fly
            $vttContent = "WEBVTT\r\n\r\n";

            // Normalize line endings
            $srtContent = str_replace(["\r\n", "\r"], "\n", $srtContent);

            // Replace time commas with dots (00:00:10,230 --> 00:00:15,120 into 00:00:10.230 --> 00:00:15.120)
            $converted = preg_replace('/(\d{2}:\d{2}:\d{2}),(\d{3})/', '$1.$2', $srtContent);

            $vttContent .= $converted;

            return response($vttContent, 200)
                ->header('Content-Type', 'text/vtt')
                ->header('Access-Control-Allow-Origin', '*');

        } catch (\Exception $e) {
            Log::error('Subtitle conversion failed for URL (' . $url . '): ' . $e->getMessage());
            return response('Error converting subtitle: ' . $e->getMessage(), 500);
        }
    }
}
