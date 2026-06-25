<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Tool;
use App\Models\Course;
use App\Models\Content;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SearchTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. FRESH START
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('contents')->truncate();
        DB::table('courses')->truncate();
        DB::table('tools')->truncate();
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. CATEGORIES
        $catAI = Category::create(['name' => 'Artificial Intelligence', 'slug' => 'ai', 'status' => 'active']);
        $catAuto = Category::create(['name' => 'Automation', 'slug' => 'automation', 'status' => 'active']);
        $catProd = Category::create(['name' => 'Productivity', 'slug' => 'productivity', 'status' => 'active']);
        $catDev = Category::create(['name' => 'Development', 'slug' => 'development', 'status' => 'active']);

        // 3. TOOLS
        $tools = ['ChatGPT', 'Zapier', 'Notion', 'Excel', 'Midjourney', 'Python', 'Make.com', 'Slack'];
        foreach ($tools as $t) {
            Tool::create(['name' => $t, 'status' => 'active']);
        }

        // ---------------------------------------------------------------------
        // 4. FOUR FULL COURSES
        // ---------------------------------------------------------------------

        // COURSE 1: ChatGPT Masterclass
        $c1 = Course::create([
            'title' => 'ChatGPT Masterclass: From Zero to Hero',
            'category_id' => $catAI->id,
            'description' => 'Master prompt engineering, custom GPTs, and advanced AI workflows for 2024.',
            'thumbnail' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=800',
            'status' => 'active'
        ]);
        for ($i=1; $i<=8; $i++) {
            Content::create([
                'title' => "ChatGPT Lesson $i: " . ['Basics', 'Advanced Prompts', 'Vision API', 'Custom GPTs', 'Data Analysis', 'Writing Code', 'Job Hunting', 'Final Project'][$i-1],
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'course_id' => $c1->id,
                'category_id' => $catAI->id,
                'status' => 'active',
                'type' => 'video'
            ]);
        }

        // COURSE 2: AI & Machine Learning Foundations
        $c2 = Course::create([
            'title' => 'AI & Machine Learning Foundations',
            'category_id' => $catAI->id,
            'description' => 'The complete mathematical and practical guide to building AI models from scratch.',
            'thumbnail' => 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?w=800',
            'status' => 'active'
        ]);
        for ($i=1; $i<=10; $i++) {
            Content::create([
                'title' => "ML Module $i: " . ['Intro', 'Math Basics', 'Linear Regression', 'Neural Nets', 'CNNs', 'RNNs', 'Transformers', 'Deployment', 'PyTorch Intro', 'Case Study'][$i-1],
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'course_id' => $c2->id,
                'category_id' => $catAI->id,
                'status' => 'active',
                'type' => 'video'
            ]);
        }

        // COURSE 3: Global Automation Masterclass
        $c3 = Course::create([
            'title' => 'Automation Masterclass: Zapier & Make',
            'category_id' => $catAuto->id,
            'description' => 'Connect every app you use and automate your entire business life.',
            'thumbnail' => 'https://images.unsplash.com/photo-1518186285589-2f7649de83e0?w=800',
            'status' => 'active'
        ]);
        for ($i=1; $i<=7; $i++) {
            Content::create([
                'title' => "Automation Step $i: " . ['Zapier Basics', 'Multi-step Zaps', 'Webhooks', 'Make.com Logic', 'Error Handling', 'API Connectors', 'Scaling Workflows'][$i-1],
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'course_id' => $c3->id,
                'category_id' => $catAuto->id,
                'status' => 'active',
                'type' => 'video'
            ]);
        }

        // COURSE 4: Productivity Pro: Notion Guide
        $c4 = Course::create([
            'title' => 'Productivity Pro: The Notion Guide',
            'category_id' => $catProd->id,
            'description' => 'Build a second brain, manage projects, and organize your life with Notion.',
            'thumbnail' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800',
            'status' => 'active'
        ]);
        for ($i=1; $i<=6; $i++) {
            Content::create([
                'title' => "Notion Level $i: " . ['Blocks & Basics', 'Databases', 'Relational Links', 'Formulas', 'Dashboard Design', 'Team Collaboration'][$i-1],
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'course_id' => $c4->id,
                'category_id' => $catProd->id,
                'status' => 'active',
                'type' => 'video'
            ]);
        }

        // ---------------------------------------------------------------------
        // 5. TEN INDIVIDUAL VIDEOS
        // ---------------------------------------------------------------------
        $indiv = [
            ['title' => 'How to write email with gpt', 'cat' => $catProd->id],
            ['title' => 'Excel VLOOKUP for beginners', 'cat' => $catProd->id],
            ['title' => 'Center a div in CSS perfectly', 'cat' => $catDev->id],
            ['title' => '10 AI tools for designers 2024', 'cat' => $catAI->id],
            ['title' => 'How to use Midjourney for Logo Design', 'cat' => $catAI->id],
            ['title' => 'Intro to Python for marketers', 'cat' => $catDev->id],
            ['title' => 'Setting up a professional Slack workspace', 'cat' => $catProd->id],
            ['title' => 'Building a landing page with Carrd', 'cat' => $catDev->id],
            ['title' => 'Clean Architecture in 10 minutes', 'cat' => $catDev->id],
            ['title' => 'Zapier vs Make: Which is better?', 'cat' => $catAuto->id],
        ];

        foreach ($indiv as $v) {
            Content::create([
                'title' => $v['title'],
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'category_id' => $v['cat'],
                'status' => 'active',
                'type' => 'video',
                'description' => 'This is a standalone training video on ' . $v['title']
            ]);
        }
    }
}
