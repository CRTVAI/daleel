<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Content;
use App\Models\Tool;

class VideoToolLinkerSeeder extends Seeder
{
    public function run(): void
    {
        $contents = Content::all();
        $tools = Tool::all();

        foreach ($contents as $content) {
            $matchingTools = [];
            $title = strtolower($content->title);
            $desc = strtolower($content->description);

            foreach ($tools as $tool) {
                $toolName = strtolower($tool->name);
                // Basic matching: tool name exists in title or description
                if (str_contains($title, $toolName) || str_contains($desc, $toolName) || 
                   ($toolName === 'excel' && str_contains($title, 'spreadsheet')) ||
                   ($toolName === 'word' && str_contains($title, 'document'))) {
                    $matchingTools[] = $tool->name;
                }
            }

            if (!empty($matchingTools)) {
                $content->update([
                    'connected_tools' => array_unique($matchingTools)
                ]);
                $this->command->info("Linked " . count($matchingTools) . " tools to: " . $content->title);
            }
        }
    }
}
