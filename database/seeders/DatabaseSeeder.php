<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Order matters: options and content first, then users
        $this->call([
            ProfileOptionsSeeder::class,
            ToolsSeeder::class,
            ContentSeeder::class,
            ConnectedToolsSeeder::class,
            UserSeeder::class,
        ]);
    }
}
