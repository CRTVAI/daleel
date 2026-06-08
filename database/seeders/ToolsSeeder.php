<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tool;

class ToolsSeeder extends Seeder
{
    public function run(): void
    {
        $tools = [
            [
                'name' => 'ChatGPT',
                'description' => 'AI chatbot by OpenAI for content generation and assistance.',
                'logo' => '/images/tools/chatgpt.svg',
            ],
            [
                'name' => 'Notion',
                'description' => 'All-in-one workspace for notes, documentation, and database tracking.',
                'logo' => '/images/tools/notion.svg',
            ],
            [
                'name' => 'Slack',
                'description' => 'Corporate messaging application for communication and real-time alerts.',
                'logo' => '/images/tools/slack.svg',
            ],
            [
                'name' => 'Zapier',
                'description' => 'Leading workflow automation platform to connect web applications.',
                'logo' => '/images/tools/zapier.svg',
            ],
            [
                'name' => 'Gmail',
                'description' => 'Secure and dynamic email server by Google for sending newsletters and updates.',
                'logo' => '/images/tools/gmail.svg',
            ],
        ];

        foreach ($tools as $t) {
            Tool::firstOrCreate(
                ['name' => $t['name']],
                [
                    'description' => $t['description'],
                    'logo' => $t['logo'],
                    'status' => 'active',
                ]
            );
        }
    }
}
