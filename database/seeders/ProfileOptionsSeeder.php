<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LearningGoal;
use App\Models\ExperienceLevel;

class ProfileOptionsSeeder extends Seeder
{
    public function run(): void
    {
        $goals = [
            'Get a job in tech',
            'Build a side project',
            'Improve skills at work',
            'Explore AI tools',
            'Learn to code',
        ];

        foreach ($goals as $goal) {
            LearningGoal::firstOrCreate(['title' => $goal]);
        }

        $levels = [
            'Beginner',
            'Intermediate',
            'Advanced',
        ];

        foreach ($levels as $level) {
            ExperienceLevel::firstOrCreate(['title' => $level]);
        }
    }
}
