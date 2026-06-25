<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\UserRoadmap;
use App\Models\Content;
use App\Models\Tool;

$roadmap = UserRoadmap::find(1);
if (!$roadmap) {
    echo "Roadmap not found\n";
    exit;
}

echo "Repairing Roadmap: {$roadmap->title}\n";
$toolIds = $roadmap->tools;
$level = $roadmap->level;
$phases = [];

$tools = Tool::whereIn('id', $toolIds)->get();
foreach ($tools as $tool) {
    echo "- Checking tool: {$tool->name} for level: {$level}\n";
    $videos = Content::whereJsonContains('connected_tools', $tool->name)
        ->where(function($q) use ($level) {
            $q->where('skill_level', $level)
              ->orWhere('skill_level', ucfirst(strtolower($level)))
              ->orWhere('skill_level', strtolower($level));
        })
        ->get();
    
    if ($videos->count() > 0) {
        echo "  Found " . $videos->count() . " videos!\n";
        $videoData = $videos->map(function($v) {
            return [
                'id' => $v->id,
                'title' => $v->title,
                'thumbnail_url' => $v->thumbnail_url,
            ];
        })->toArray();

        $phases[] = [
            'name' => "Mastering {$tool->name}",
            'tool_name' => $tool->name,
            'videos' => $videoData
        ];
    } else {
        echo "  No videos found for this level.\n";
    }
}

$roadmap->curriculum = [
    'title' => $roadmap->title,
    'focus' => $roadmap->focus,
    'level' => $level,
    'phases' => $phases
];
$roadmap->save();
echo "SUCCESS: Roadmap #1 repaired with " . count($phases) . " phases.\n";
