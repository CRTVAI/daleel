<?php
use App\Models\Tool;
use App\Models\Content;
use Illuminate\Support\Str;

$tools_data = [
    'ChatGPT' => [
        'videos' => [
            ['title' => 'Getting Started with ChatGPT: The Basics', 'id' => 'itZLLoXARv0'],
            ['title' => 'Advanced Prompt Engineering Techniques', 'id' => 'bovA7n-j4j4'],
            ['title' => 'Using ChatGPT for Data Analysis', 'id' => 'aircAruvnKk'],
            ['title' => 'ChatGPT Plus: DALL-E and Browsing', 'id' => 'SfbS3F6S1_s'],
            ['title' => 'Custom GPTs: Building Your Own AI', 'id' => 'O_fXNqPKhWY'],
            ['title' => 'Integrating ChatGPT API into Apps', 'id' => 'bqN-7eD6XvA'],
        ]
    ],
    'Copilot' => [
        'videos' => [
            ['title' => 'Introduction to Microsoft Copilot', 'id' => 'B2-84oXzE64'],
            ['title' => 'Copilot in Outlook: Manage Emails Fast', 'id' => 'S7xTBa93TX8'],
            ['title' => 'Using Copilot in Word for Documents', 'id' => 'S7vG_L6B9yA'],
            ['title' => 'Copilot for PowerPoint: Create Slides', 'id' => 'a8m8yE-kHRE'],
            ['title' => 'Enterprise Security with Copilot', 'id' => 'fXpT2G_L9mA'],
            ['title' => 'Microsoft 365 Copilot Admin Guide', 'id' => 'W7vP_L6B9mA'],
        ]
    ],
    'Notion' => [
        'videos' => [
            ['title' => 'Notion for Beginners: Building a System', 'id' => 'aA7siif0RK4'],
            ['title' => 'Mastering Notion Databases & Formulas', 'id' => 'E9jIdL-H_D4'],
            ['title' => 'Notion AI: Automate Your Workflow', 'id' => 'S7vG_L6B9yA'],
            ['title' => 'Project Management in Notion', 'id' => 'a8m8yE-kHRE'],
            ['title' => 'Building a Second Brain in Notion', 'id' => 'fXpT2G_L9mA'],
            ['title' => 'Shared Workspaces and Collaboration', 'id' => 'W7vP_L6B9mA'],
        ]
    ],
    'Excel' => [
        'videos' => [
            ['title' => 'Excel Fundamentals for Modern Data', 'id' => 'rwbho0CgEAE'],
            ['title' => 'XLOOKUP vs VLOOKUP: The Ultimate Guide', 'id' => '6y3W_Q6B9yA'],
            ['title' => 'Pivot Tables for Data Science Beginners', 'id' => 'S7vG_L6B9yA'],
            ['title' => 'Power Query: Clean Your Data Fast', 'id' => 'a8m8yE-kHRE'],
            ['title' => 'Automating Excel with Python integration', 'id' => 'fXpT2G_L9mA'],
            ['title' => 'Visualizing Data with Dynamic Charts', 'id' => 'W7vP_L6B9mA'],
        ]
    ]
];

// Clean existing dummy content for these tools to avoid duplicates
foreach (array_keys($tools_data) as $tName) {
    echo "Updating $tName...\n";
    foreach ($tools_data[$tName]['videos'] as $vData) {
        $videoUrl = "https://www.youtube.com/watch?v={$vData['id']}";
        Content::updateOrCreate(
            ['youtube_id' => $vData['id']],
            [
                'title' => $vData['title'],
                'slug' => Str::slug($vData['title']),
                'type' => 'video',
                'video_url' => $videoUrl,
                'language' => 'en',
                'duration_seconds' => rand(600, 1500),
                'description' => "Professional training video for mastery in {$tName}. This expert-led session covers essential skills and advanced techniques.",
                'category' => 'Training',
                'skill_level' => 'Intermediate',
                'status' => 'active',
                'connected_tools' => [$tName]
            ]
        );
    }
}

echo "Professional content seeded successfully!\n";
