<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\ExtensionDevice;
use App\Models\ExtensionSession;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeamDemoSeeder extends Seeder
{
    public function run(): void
    {
        $manager = User::firstOrNew(['email' => 'anas@crtvai.com']);
        $manager->fill([
            'name' => $manager->exists ? $manager->name : 'Anas',
            'email_verified_at' => $manager->email_verified_at ?? now(),
            'can_access_team' => true,
            'account_type' => $manager->account_type ?: 'company',
        ]);

        if (! $manager->exists) {
            $manager->password = Hash::make('password');
        }

        $manager->save();

        $groups = [
            'Operations A',
            'Sales Enablement',
            'Marketing Studio',
            'Finance Analysts',
            'Product Research',
            'Customer Support',
            'HR Training',
            'Design Lab',
            'Engineering Prep',
            'Executive Assistants',
        ];

        $departments = collect($groups)->mapWithKeys(function (string $name) use ($manager) {
            $department = Department::updateOrCreate(
                ['company_id' => $manager->id, 'name' => $name],
                ['updated_at' => now()]
            );

            return [$name => $department];
        });

        $studentNames = [
            'Aisha Rahman',
            'Omar Siddiqui',
            'Maya Chen',
            'Daniel Brooks',
            'Sara Ahmed',
            'Noah Williams',
            'Fatima Khan',
            'Liam Carter',
            'Priya Shah',
            'Ethan Miller',
            'Nadia Hassan',
            'Lucas Brown',
            'Hannah Wilson',
            'Yusuf Ali',
            'Amelia Clark',
            'Zara Malik',
            'James Foster',
            'Ivy Nguyen',
            'Arman Hossain',
            'Grace Taylor',
            'Leila Noor',
            'Ryan Cooper',
            'Tania Islam',
            'Bilal Karim',
            'Sofia Martinez',
        ];

        $students = collect($studentNames)->map(function (string $name, int $index) use ($manager, $departments, $groups) {
            $department = $departments[$groups[$index % count($groups)]];
            $slug = Str::slug($name, '.');

            return User::updateOrCreate(
                ['email' => $slug . '@daleel-demo.test'],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'parent_id' => $manager->id,
                    'department_id' => $department->id,
                    'is_employee' => true,
                    'connection_code' => 'DEMO-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'connection_code_issued_at' => now()->subDays($index % 12),
                ]
            );
        });

        $platforms = [
            ['domain' => 'chatgpt.com', 'category' => 'AI Assistant', 'is_ai' => true, 'weight' => 9],
            ['domain' => 'excel.office.com', 'category' => 'Productivity', 'is_ai' => false, 'weight' => 8],
            ['domain' => 'docs.google.com', 'category' => 'Productivity', 'is_ai' => false, 'weight' => 6],
            ['domain' => 'sheets.google.com', 'category' => 'Productivity', 'is_ai' => false, 'weight' => 7],
            ['domain' => 'notion.so', 'category' => 'Knowledge Base', 'is_ai' => false, 'weight' => 5],
            ['domain' => 'slack.com', 'category' => 'Communication', 'is_ai' => false, 'weight' => 5],
            ['domain' => 'figma.com', 'category' => 'Design', 'is_ai' => false, 'weight' => 4],
            ['domain' => 'canva.com', 'category' => 'Design', 'is_ai' => false, 'weight' => 4],
            ['domain' => 'github.com', 'category' => 'Development', 'is_ai' => false, 'weight' => 4],
            ['domain' => 'stackoverflow.com', 'category' => 'Research', 'is_ai' => false, 'weight' => 5],
            ['domain' => 'w3schools.com', 'category' => 'Research', 'is_ai' => false, 'weight' => 3],
            ['domain' => 'zapier.com', 'category' => 'Automation', 'is_ai' => false, 'weight' => 3],
            ['domain' => 'make.com', 'category' => 'Automation', 'is_ai' => false, 'weight' => 3],
            ['domain' => 'teams.microsoft.com', 'category' => 'Communication', 'is_ai' => false, 'weight' => 4],
            ['domain' => 'youtube.com', 'category' => 'Learning', 'is_ai' => false, 'weight' => 3],
            ['domain' => 'coursera.org', 'category' => 'Learning', 'is_ai' => false, 'weight' => 2],
            ['domain' => 'gmail.com', 'category' => 'Communication', 'is_ai' => false, 'weight' => 5],
        ];

        $groupFocus = [
            'Operations A' => ['excel.office.com', 'sheets.google.com', 'zapier.com', 'make.com'],
            'Sales Enablement' => ['chatgpt.com', 'gmail.com', 'slack.com', 'docs.google.com'],
            'Marketing Studio' => ['canva.com', 'chatgpt.com', 'notion.so', 'youtube.com'],
            'Finance Analysts' => ['excel.office.com', 'sheets.google.com', 'chatgpt.com'],
            'Product Research' => ['notion.so', 'chatgpt.com', 'stackoverflow.com', 'docs.google.com'],
            'Customer Support' => ['slack.com', 'gmail.com', 'chatgpt.com', 'teams.microsoft.com'],
            'HR Training' => ['docs.google.com', 'notion.so', 'teams.microsoft.com', 'coursera.org'],
            'Design Lab' => ['figma.com', 'canva.com', 'chatgpt.com', 'notion.so'],
            'Engineering Prep' => ['github.com', 'stackoverflow.com', 'w3schools.com', 'chatgpt.com'],
            'Executive Assistants' => ['gmail.com', 'docs.google.com', 'excel.office.com', 'chatgpt.com'],
        ];

        foreach ($students as $studentIndex => $student) {
            $device = ExtensionDevice::updateOrCreate(
                ['user_id' => $student->id, 'device_id' => 'demo-device-' . $student->id],
                [
                    'device_name' => $student->name . ' Chrome',
                    'extension_name' => 'Daleel Mentor',
                    'extension_version' => '0.1.0-demo',
                    'last_active_at' => now()->subMinutes(($studentIndex % 9) * 7),
                    'revoked_at' => null,
                ]
            );

            ExtensionSession::where('extension_device_id', $device->id)->delete();

            $departmentName = $student->department?->name ?? $groups[$studentIndex % count($groups)];
            $focusDomains = $groupFocus[$departmentName] ?? [];

            for ($day = 0; $day < 30; $day++) {
                $sessionsToday = 3 + (($studentIndex + $day) % 5);
                $baseTime = Carbon::today()->subDays($day)->setTime(9 + ($studentIndex % 3), 0);

                for ($session = 0; $session < $sessionsToday; $session++) {
                    $platform = $this->pickPlatform($platforms, $focusDomains, $studentIndex, $day, $session);
                    $activeMinutes = 8 + (($studentIndex * 7 + $day * 5 + $session * 11 + $platform['weight']) % 68);
                    $activeMs = $activeMinutes * 60 * 1000;
                    $openMs = $activeMs + ((2 + (($day + $session) % 12)) * 60 * 1000);
                    $startedAt = (clone $baseTime)->addMinutes(($session * 53) + (($studentIndex + $day) % 17));
                    $endedAt = (clone $startedAt)->addMilliseconds($openMs);

                    ExtensionSession::create([
                        'user_id' => $student->id,
                        'extension_device_id' => $device->id,
                        'session_id_from_ext' => 'demo-' . $student->id . '-' . $day . '-' . $session . '-' . Str::random(6),
                        'started_at' => $startedAt,
                        'ended_at' => $endedAt,
                        'platform_type' => 'website',
                        'platform_domain' => $platform['domain'],
                        'platform_category' => $platform['category'],
                        'is_ai_tool' => $platform['is_ai'],
                        'active_ms' => $activeMs,
                        'idle_ms' => max(0, $openMs - $activeMs),
                        'open_ms' => $openMs,
                        'click_count' => 4 + (($studentIndex + $session) % 24),
                        'interaction_count' => 8 + (($studentIndex + $day + $session) % 34),
                        'page_count' => 1 + (($session + $day) % 5),
                        'tab_switch_count' => ($studentIndex + $day + $session) % 9,
                        'pages' => [
                            ['title' => ucfirst(str_replace('.', ' ', $platform['domain'])), 'url' => 'https://' . $platform['domain']],
                        ],
                        'local_signals' => ['demo' => true, 'focus_group' => $departmentName],
                        'recommended_content_tags' => [$platform['category']],
                    ]);
                }
            }
        }

        $this->command?->info('Team demo data ready for anas@crtvai.com');
    }

    private function pickPlatform(array $platforms, array $focusDomains, int $studentIndex, int $day, int $session): array
    {
        if ($focusDomains && (($studentIndex + $day + $session) % 10) < 7) {
            $domain = $focusDomains[($studentIndex + $day + $session) % count($focusDomains)];

            foreach ($platforms as $platform) {
                if ($platform['domain'] === $domain) {
                    return $platform;
                }
            }
        }

        $weighted = [];
        foreach ($platforms as $platform) {
            for ($i = 0; $i < $platform['weight']; $i++) {
                $weighted[] = $platform;
            }
        }

        return $weighted[($studentIndex * 13 + $day * 7 + $session * 5) % count($weighted)];
    }
}
