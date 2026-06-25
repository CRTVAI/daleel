<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('user_video_progress')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $users = [
            [
                'name'             => 'Admin User',
                'email'            => 'admin@crtvai.com',
                'password'         => Hash::make('password'),
                'is_admin'         => true,
                'learning_goal'    => null,
                'experience_level' => null,
                'interests'        => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'Alex Johnson',
                'email'            => 'user@crtvai.com',
                'password'         => Hash::make('password'),
                'is_admin'         => false,
                'learning_goal'    => 'Get a job in tech',
                'experience_level' => 'Beginner',
                'email_verified_at' => '2026-06-07 14:22:32',
                'interests'        => json_encode(['Web Development', 'AI & ML']),
                'created_at'       => now()->subDays(30),
                'updated_at'       => now()->subDays(30),
            ],
            [
                'name'             => 'Sara Ahmed',
                'email'            => 'sara@demo.com',
                'password'         => Hash::make('password'),
                'is_admin'         => false,
                'learning_goal'    => 'Explore AI tools',
                'experience_level' => 'Intermediate',
                'interests'        => json_encode(['AI & ML', 'Productivity', 'Design']),
                'created_at'       => now()->subDays(20),
                'updated_at'       => now()->subDays(20),
            ],
            [
                'name'             => 'Carlos Rivera',
                'email'            => 'carlos@demo.com',
                'password'         => Hash::make('password'),
                'is_admin'         => false,
                'learning_goal'    => 'Build a side project',
                'experience_level' => 'Intermediate',
                'interests'        => json_encode(['Web Development', 'Data Science', 'Business']),
                'created_at'       => now()->subDays(15),
                'updated_at'       => now()->subDays(15),
            ],
            [
                'name'             => 'Mia Chen',
                'email'            => 'mia@demo.com',
                'password'         => Hash::make('password'),
                'is_admin'         => false,
                'learning_goal'    => 'Improve skills at work',
                'experience_level' => 'Advanced',
                'interests'        => json_encode(['Data Science', 'AI & ML', 'Cybersecurity']),
                'created_at'       => now()->subDays(10),
                'updated_at'       => now()->subDays(10),
            ],
            [
                'name'             => 'Tariq Malik',
                'email'            => 'tariq@demo.com',
                'password'         => Hash::make('password'),
                'is_admin'         => false,
                'learning_goal'    => 'Learn to code',
                'experience_level' => 'Beginner',
                'interests'        => json_encode(['Web Development', 'Productivity']),
                'created_at'       => now()->subDays(8),
                'updated_at'       => now()->subDays(8),
            ],
            [
                'name'             => 'Priya Sharma',
                'email'            => 'priya@demo.com',
                'password'         => Hash::make('password'),
                'is_admin'         => false,
                'learning_goal'    => 'Explore AI tools',
                'experience_level' => 'Beginner',
                'interests'        => json_encode(['AI & ML', 'Design', 'Marketing']),
                'created_at'       => now()->subDays(5),
                'updated_at'       => now()->subDays(5),
            ],
            [
                'name'             => 'James Wilson',
                'email'            => 'james@demo.com',
                'password'         => Hash::make('password'),
                'is_admin'         => false,
                'learning_goal'    => 'Build a side project',
                'experience_level' => 'Advanced',
                'interests'        => json_encode(['Cybersecurity', 'Web Development']),
                'created_at'       => now()->subDays(3),
                'updated_at'       => now()->subDays(3),
            ],
            [
                'name'             => 'Fatima Al-Rashid',
                'email'            => 'fatima@demo.com',
                'password'         => Hash::make('password'),
                'is_admin'         => false,
                'learning_goal'    => 'Improve skills at work',
                'experience_level' => 'Intermediate',
                'interests'        => json_encode(['Business', 'Marketing', 'Productivity']),
                'created_at'       => now()->subDays(2),
                'updated_at'       => now()->subDays(2),
            ],
            [
                'name'             => 'Liam O\'Brien',
                'email'            => 'liam@demo.com',
                'password'         => Hash::make('password'),
                'is_admin'         => false,
                'learning_goal'    => 'Get a job in tech',
                'experience_level' => 'Intermediate',
                'interests'        => json_encode(['Data Science', 'AI & ML', 'Web Development']),
                'created_at'       => now()->subDay(),
                'updated_at'       => now()->subDay(),
            ],
        ];

        DB::table('users')->insert($users);

        // ── Seed realistic video progress for demo users ──────────────
        $contents = DB::table('contents')->pluck('id')->toArray();
        if (empty($contents)) return;

        // Get user IDs (non-admin)
        $studentUsers = DB::table('users')->where('is_admin', false)->pluck('id')->toArray();

        $progressRows = [];
        foreach ($studentUsers as $userId) {
            // Each student watched 5–15 random videos
            $watched = array_slice(array_reverse($contents), 0, rand(5, 15));
            foreach ($watched as $contentId) {
                $watchedSeconds = rand(120, 2400);
                $duration = 1800;
                $percent = min(100, round(($watchedSeconds / $duration) * 100, 2));
                $completed = $percent >= 90;

                $progressRows[] = [
                    'user_id'           => $userId,
                    'content_id'        => $contentId,
                    'watched_seconds'   => $watchedSeconds,
                    'duration_seconds'  => $duration,
                    'completion_percent'=> $percent,
                    'completed'         => $completed ? 1 : 0,
                    'last_watched_at'   => now()->subMinutes(rand(30, 14400)),
                    'created_at'        => now()->subDays(rand(1, 20)),
                    'updated_at'        => now(),
                ];
            }
        }

        foreach (array_chunk($progressRows, 50) as $chunk) {
            DB::table('user_video_progress')->insert($chunk);
        }
    }
}
