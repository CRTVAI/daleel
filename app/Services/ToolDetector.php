<?php

namespace App\Services;

class ToolDetector
{
    public static function detect(?string $url): array
    {
        if (!$url) {
            return self::unknown();
        }

        $normalizedUrl = self::normalizeUrl($url);
        $parts = parse_url($normalizedUrl);
        if (!$parts || empty($parts['host'])) {
            return self::unknown();
        }

        $host = strtolower($parts['host']);
        $path = strtolower($parts['path'] ?? '');
        $query = strtolower($parts['query'] ?? '');
        $host = preg_replace('/^www\./', '', $host);

        // Google Workspace
        if ($host === 'docs.google.com') {
            if (str_starts_with($path, '/spreadsheets')) {
                return self::result('Google', 'Google Sheets');
            }
            if (str_starts_with($path, '/presentation')) {
                return self::result('Google', 'Google Slides');
            }
            if (str_starts_with($path, '/document')) {
                return self::result('Google', 'Google Docs');
            }
            if (str_starts_with($path, '/forms')) {
                return self::result('Google', 'Google Forms');
            }
            if (str_starts_with($path, '/drawings')) {
                return self::result('Google', 'Google Drawings');
            }
            return self::result('Google', 'Google Docs');
        }

        if ($host === 'drive.google.com') {
            return self::result('Google', 'Google Drive');
        }
        if ($host === 'mail.google.com') {
            return self::result('Google', 'Gmail');
        }
        if ($host === 'calendar.google.com') {
            return self::result('Google', 'Google Calendar');
        }
        if ($host === 'meet.google.com') {
            return self::result('Google', 'Google Meet');
        }

        // Microsoft 365 / Office
        if (
            $host === 'office.com' ||
            $host === 'microsoft365.com' ||
            str_ends_with($host, '.office.com') ||
            str_ends_with($host, '.microsoft365.com')
        ) {
            if (str_contains($path, 'excel')) {
                return self::result('Microsoft', 'Microsoft Excel');
            }
            if (str_contains($path, 'word')) {
                return self::result('Microsoft', 'Microsoft Word');
            }
            if (str_contains($path, 'powerpoint')) {
                return self::result('Microsoft', 'Microsoft PowerPoint');
            }
            if (str_contains($path, 'onenote')) {
                return self::result('Microsoft', 'Microsoft OneNote');
            }
            if (str_contains($path, 'outlook')) {
                return self::result('Microsoft', 'Microsoft Outlook');
            }
            if (str_contains($path, 'teams')) {
                return self::result('Microsoft', 'Microsoft Teams');
            }
            return self::result('Microsoft', 'Microsoft 365');
        }

        if (str_ends_with($host, '.sharepoint.com')) {
            if (str_contains($path, '/:x:')) {
                return self::result('Microsoft', 'Microsoft Excel');
            }
            if (str_contains($path, '/:w:')) {
                return self::result('Microsoft', 'Microsoft Word');
            }
            if (str_contains($path, '/:p:')) {
                return self::result('Microsoft', 'Microsoft PowerPoint');
            }
            return self::result('Microsoft', 'SharePoint');
        }

        if ($host === 'onedrive.live.com') {
            if (str_contains($query, 'app=word')) {
                return self::result('Microsoft', 'Microsoft Word');
            }
            if (str_contains($query, 'app=excel')) {
                return self::result('Microsoft', 'Microsoft Excel');
            }
            if (str_contains($query, 'app=powerpoint')) {
                return self::result('Microsoft', 'Microsoft PowerPoint');
            }
            return self::result('Microsoft', 'OneDrive');
        }

        // Figma
        if ($host === 'figma.com') {
            if (str_starts_with($path, '/design')) {
                return self::result('Figma', 'Figma Design');
            }
            if (str_starts_with($path, '/figjam')) {
                return self::result('Figma', 'FigJam');
            }
            if (str_starts_with($path, '/slides')) {
                return self::result('Figma', 'Figma Slides');
            }
            if (str_starts_with($path, '/make')) {
                return self::result('Figma', 'Figma Make');
            }
            return self::result('Figma', 'Figma');
        }

        // Canva
        if ($host === 'canva.com') {
            if (str_starts_with($path, '/design')) {
                return self::result('Canva', 'Canva Design');
            }
            if (str_starts_with($path, '/docs')) {
                return self::result('Canva', 'Canva Docs');
            }
            if (str_starts_with($path, '/presentations')) {
                return self::result('Canva', 'Canva Presentations');
            }
            if (str_contains($path, 'whiteboard')) {
                return self::result('Canva', 'Canva Whiteboard');
            }
            return self::result('Canva', 'Canva');
        }

        // Adobe
        $isAdobe = $host === 'adobe.com' || str_ends_with($host, '.adobe.com');
        if ($isAdobe) {
            if ($host === 'acrobat.adobe.com') {
                return self::result('Adobe', 'Adobe Acrobat');
            }
            if ($host === 'photoshop.adobe.com') {
                return self::result('Adobe', 'Adobe Photoshop');
            }
            if ($host === 'firefly.adobe.com') {
                return self::result('Adobe', 'Adobe Firefly');
            }
            if ($express = ($host === 'express.adobe.com' || str_starts_with($path, '/express'))) {
                return self::result('Adobe', 'Adobe Express');
            }
            return self::result('Adobe', 'Adobe');
        }

        // Atlassian
        if (str_ends_with($host, '.atlassian.net')) {
            if (str_starts_with($path, '/wiki')) {
                return self::result('Atlassian', 'Confluence');
            }
            if (str_contains($path, 'jira')) {
                return self::result('Atlassian', 'Jira');
            }
            return self::result('Atlassian', 'Atlassian');
        }

        // Zoho
        $isZoho = $host === 'zoho.com' || str_ends_with($host, '.zoho.com');
        if ($isZoho) {
            if ($host === 'writer.zoho.com' || str_contains($path, '/writer')) {
                return self::result('Zoho', 'Zoho Writer');
            }
            if ($host === 'sheet.zoho.com' || str_contains($path, '/sheet')) {
                return self::result('Zoho', 'Zoho Sheet');
            }
            if ($host === 'show.zoho.com' || str_contains($path, '/show')) {
                return self::result('Zoho', 'Zoho Show');
            }
            if ($host === 'crm.zoho.com' || str_contains($path, '/crm')) {
                return self::result('Zoho', 'Zoho CRM');
            }
            return self::result('Zoho', 'Zoho');
        }

        // Common single-tool domains
        $simpleDomains = [
            'chatgpt.com' => ['OpenAI', 'ChatGPT'],
            'claude.ai' => ['Anthropic', 'Claude'],
            'notion.so' => ['Notion', 'Notion'],
            'miro.com' => ['Miro', 'Miro'],
            'trello.com' => ['Atlassian', 'Trello'],
            'slack.com' => ['Slack', 'Slack'],
            'github.com' => ['GitHub', 'GitHub'],
            'gitlab.com' => ['GitLab', 'GitLab'],
            'dropbox.com' => ['Dropbox', 'Dropbox'],
            'box.com' => ['Box', 'Box'],
            'airtable.com' => ['Airtable', 'Airtable'],
            'monday.com' => ['Monday', 'Monday.com'],
            'clickup.com' => ['ClickUp', 'ClickUp'],
            'zoom.us' => ['Zoom', 'Zoom'],
            'loom.com' => ['Loom', 'Loom'],
        ];

        foreach ($simpleDomains as $domain => [$provider, $tool]) {
            if ($host === $domain || str_ends_with($host, '.' . $domain)) {
                return self::result($provider, $tool);
            }
        }

        // Fallback for unknown websites
        return [
            'tool_provider' => self::guessProviderFromHost($host),
            'tool_name' => self::guessToolNameFromHost($host),
        ];
    }

    private static function normalizeUrl(string $url): string
    {
        $url = trim($url);
        if (!preg_match('/^https?:\/\//i', $url)) {
            $url = 'https://' . $url;
        }
        return $url;
    }

    private static function result(string $provider, string $toolName): array
    {
        return [
            'tool_provider' => $provider,
            'tool_name' => $toolName,
        ];
    }

    private static function unknown(): array
    {
        return [
            'tool_provider' => null,
            'tool_name' => 'Unknown',
        ];
    }

    private static function guessProviderFromHost(string $host): ?string
    {
        $mainDomain = self::getMainDomain($host);
        return $mainDomain ? ucfirst($mainDomain) : null;
    }

    private static function guessToolNameFromHost(string $host): string
    {
        $mainDomain = self::getMainDomain($host);
        return $mainDomain ? ucfirst($mainDomain) : 'Unknown';
    }

    private static function getMainDomain(string $host): ?string
    {
        $parts = explode('.', $host);
        if (count($parts) < 2) {
            return null;
        }
        return $parts[count($parts) - 2];
    }
}
