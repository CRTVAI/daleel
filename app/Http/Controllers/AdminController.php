<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Content;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    // User Management
    public function usersIndex(Request $request)
    {
        $query = User::where('is_admin', false);

        if ($request->filled('account_type')) {
            $query->where('account_type', $request->account_type);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        $learningGoals = \App\Models\LearningGoal::orderBy('title')->get();
        $experienceLevels = \App\Models\ExperienceLevel::orderBy('title')->get();
        return view('admin.users.index', compact('users', 'learningGoals', 'experienceLevels'));
    }

    public function userProfile(User $user)
    {
        $learningGoals = \App\Models\LearningGoal::orderBy('title')->get();
        $experienceLevels = \App\Models\ExperienceLevel::orderBy('title')->get();
        $tools = \App\Models\Tool::where('status', 'active')->orderBy('name')->get();
        $interestsList = \App\Models\Content::distinct()->whereNotNull('category')->pluck('category')->toArray();

        return view('admin.users.profile', compact('user', 'learningGoals', 'experienceLevels', 'tools', 'interestsList'));
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => false,
            'account_type' => 'Free Plan',
            'learning_goal' => null,
            'experience_level' => null,
            'interests' => [],
        ]);

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function userUpdate(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $interests = $request->interests;
        if ($interests) {
            $decoded = json_decode($interests, true);
            if (is_array($decoded)) {
                $interests = array_map(fn($item) => $item['value'], $decoded);
            } else {
                $interests = array_filter(array_map('trim', explode(',', $interests)));
            }
        }

        $tools = $request->tools;
        if ($tools) {
            $decoded = json_decode($tools, true);
            if (is_array($decoded)) {
                $tools = array_map(fn($item) => $item['value'], $decoded);
            } else {
                $tools = array_filter(array_map('trim', explode(',', $tools)));
            }
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'account_type' => $request->account_type,
            'learning_goal' => $request->primary_goal,
            'experience_level' => $request->experience_level,
            'interests' => $interests ?: [],
            'tools' => $tools ?: [],
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function userDestroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    public function toggleTeamAccess(User $user)
    {
        $user->update(['can_access_team' => !$user->can_access_team]);
        $status = $user->can_access_team ? 'granted' : 'revoked';
        return redirect()->back()->with('success', "Team access {$status} for {$user->name}.");
    }

    // Content Management
    public function contentsIndex(Request $request)
    {
        $query = Content::query();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('connected_tools', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%")
                  ->orWhere('language', 'like', "%{$search}%")
                  ->orWhere('video_url', 'like', "%{$search}%")
                  ->orWhere('video_url_ar', 'like', "%{$search}%");

                // Smart language mapping
                $s = strtolower($search);
                if ($s === 'arabic' || $s === 'ar') {
                    $q->orWhereIn('language', ['ar', 'both']);
                } elseif ($s === 'english' || $s === 'en') {
                    $q->orWhereIn('language', ['en', 'both']);
                }
            });
        }

        // Category Filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Connected Tool Filter
        if ($request->filled('tool')) {
            $query->where('connected_tools', 'like', "%{$request->tool}%");
        }

        // Skill Level Filter
        if ($request->filled('skill_level')) {
            $query->where('skill_level', $request->skill_level);
        }

        // Language Filter
        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        // Course Filter
        if ($request->filled('course_id')) {
            if ($request->course_id === 'none') {
                $query->whereNull('course_id');
            } else {
                $query->where('course_id', $request->course_id);
            }
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'title') {
            $query->orderBy('title', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $contents = $query->paginate(12)->withQueryString();

        $categories = Content::distinct()->whereNotNull('category')->pluck('category')->sort();
        $courses = Course::orderBy('title')->get();

        // Extract unique connected tools currently mapped to contents
        $allContents = Content::whereNotNull('connected_tools')->get();
        $usedTools = [];
        foreach ($allContents as $c) {
            if (is_array($c->connected_tools)) {
                foreach ($c->connected_tools as $tool) {
                    $trimmed = trim($tool);
                    if ($trimmed && !in_array($trimmed, $usedTools)) {
                        $usedTools[] = $trimmed;
                    }
                }
            }
        }
        sort($usedTools);

        // Statistics for the header
        $stats = [
            'total_videos' => Content::count(),
            'total_courses' => Course::count(),
            'standalone_videos' => Content::whereNull('course_id')->count(),
            'total_duration' => Content::sum('duration_seconds'),
        ];

        $tools = \App\Models\Tool::where('status', 'active')->orderBy('name')->get();
        $allCategories = Category::where('status', 'active')->orderBy('name')->get();

        return view('admin.contents.index', compact('contents', 'categories', 'courses', 'stats', 'usedTools', 'tools', 'allCategories'));
    }

    public function contentStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'video_file' => 'required|file|mimes:mp4,mov,avi,wmv,m4v|max:204800', // 200MB max
            'video_url' => 'nullable|url',
            'thumbnail_base64' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'skill_level' => 'required|in:Beginner,Intermediate,Advanced',
            'course_id' => 'nullable|exists:courses,id',
            'section_part_label' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'connected_tools' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,draft',
            'tags' => 'nullable|string',
            'duration_seconds' => 'nullable|integer',
            'type' => 'nullable|string|in:video,article,course',
            'is_featured' => 'nullable|boolean',
            'srt_file_en' => 'nullable|file|max:10240',
            'srt_file_ar' => 'nullable|file|max:10240',
        ]);

        $data = $request->except(['video_file', 'thumbnail_base64', 'srt_file_en', 'srt_file_ar']);
        $data['is_featured'] = $request->has('is_featured');
        $data['language'] = $request->filled('video_url_ar') ? 'both' : 'en';
        $data['duration_seconds'] = $request->get('duration_seconds', 0);

        // Handle Video Upload to S3
        if ($request->hasFile('video_file')) {
            $uploadedFile = $request->file('video_file');
            $path = $uploadedFile->store('direct-uploads/' . date('Y-m-d'), 's3');
            $data['video_url'] = \Illuminate\Support\Facades\Storage::disk('s3')->url($path);
            // Clean up the local temp file now that it's safely on S3
            @unlink($uploadedFile->getRealPath());
        }

        // Handle Base64 Thumbnail (same as extension)
        if ($request->filled('thumbnail_base64')) {
            $imageData = $request->thumbnail_base64;
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $type = strtolower($type[1]);
                $imageData = base64_decode($imageData);

                if ($imageData !== false) {
                    $fileName = 'thumbnails/' . \Illuminate\Support\Str::slug($data['title']) . '-' . time() . '.' . $type;
                    $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
                    \Illuminate\Support\Facades\Storage::disk($disk)->put($fileName, $imageData, 'public');
                    $data['thumbnail'] = \Illuminate\Support\Facades\Storage::disk($disk)->url($fileName);
                }
            }
        }

        // Process connected tools
        if ($request->filled('connected_tools')) {
            $data['connected_tools'] = array_map('trim', explode(',', $request->connected_tools));
        } else {
            $data['connected_tools'] = [];
        }

        // Handle English SRT Upload
        if ($request->hasFile('srt_file_en')) {
            $file = $request->file('srt_file_en');
            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            $path = $file->store('subtitles/' . date('Y-m-d'), $disk);
            $data['srt_file_en'] = \Illuminate\Support\Facades\Storage::disk($disk)->url($path);
        }

        // Handle Arabic SRT Upload
        if ($request->hasFile('srt_file_ar')) {
            $file = $request->file('srt_file_ar');
            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            $path = $file->store('subtitles/' . date('Y-m-d'), $disk);
            $data['srt_file_ar'] = \Illuminate\Support\Facades\Storage::disk($disk)->url($path);
        }

        Content::create($data);

        return redirect()->back()->with('success', 'Content added successfully.');
    }

    public function contentUpdate(Request $request, Content $content)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'video_file' => 'nullable|file|mimes:mp4,mov,avi,wmv,m4v|max:204800',
            'video_url' => 'nullable|url',
            'thumbnail_base64' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'skill_level' => 'required|in:Beginner,Intermediate,Advanced',
            'course_id' => 'nullable|exists:courses,id',
            'section_part_label' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'connected_tools' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,draft',
            'tags' => 'nullable|string',
            'duration_seconds' => 'nullable|integer',
            'type' => 'nullable|string|in:video,article,course',
            'is_featured' => 'nullable|boolean',
            'srt_file_en' => 'nullable|file|max:10240',
            'srt_file_ar' => 'nullable|file|max:10240',
        ]);

        $data = $request->except(['video_file', 'thumbnail_base64', 'srt_file_en', 'srt_file_ar']);
        $data['is_featured'] = $request->has('is_featured');
        $data['language'] = $request->filled('video_url_ar') ? 'both' : 'en';

        // Handle Video Upload to S3
        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('direct-uploads/' . date('Y-m-d'), 's3');
            $data['video_url'] = \Illuminate\Support\Facades\Storage::disk('s3')->url($path);
        }

        // Handle Base64 Thumbnail
        if ($request->filled('thumbnail_base64')) {
            $imageData = $request->thumbnail_base64;
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $type = strtolower($type[1]);
                $imageData = base64_decode($imageData);

                if ($imageData !== false) {
                    $fileName = 'thumbnails/' . \Illuminate\Support\Str::slug($data['title']) . '-' . time() . '.' . $type;
                    $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
                    \Illuminate\Support\Facades\Storage::disk($disk)->put($fileName, $imageData, 'public');
                    $data['thumbnail'] = \Illuminate\Support\Facades\Storage::disk($disk)->url($fileName);
                }
            }
        }

        if ($request->filled('connected_tools')) {
            $data['connected_tools'] = array_map('trim', explode(',', $request->connected_tools));
        } else {
            $data['connected_tools'] = [];
        }

        // Handle English SRT Upload
        if ($request->hasFile('srt_file_en')) {
            $file = $request->file('srt_file_en');
            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            $path = $file->store('subtitles/' . date('Y-m-d'), $disk);
            $data['srt_file_en'] = \Illuminate\Support\Facades\Storage::disk($disk)->url($path);
        }

        // Handle Arabic SRT Upload
        if ($request->hasFile('srt_file_ar')) {
            $file = $request->file('srt_file_ar');
            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            $path = $file->store('subtitles/' . date('Y-m-d'), $disk);
            $data['srt_file_ar'] = \Illuminate\Support\Facades\Storage::disk($disk)->url($path);
        }

        $content->update($data);

        return redirect()->back()->with('success', 'Content updated successfully.');
    }

    public function contentDestroy(Content $content)
    {
        $content->delete();
        return redirect()->back()->with('success', 'Content deleted successfully.');
    }

    // Course Management
    public function coursesIndex()
    {
        $courses = Course::withCount('contents')->orderBy('created_at', 'desc')->paginate(10);
        $tools   = \App\Models\Tool::where('status', 'active')->orderBy('name')->get();
        return view('admin.courses.index', compact('courses', 'tools'));
    }

    public function courseStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,draft',
            'connected_tools' => 'nullable|string',
        ]);

        $data = $request->except('thumbnail');

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $data['thumbnail'] = '/storage/' . $path;
        }

        if ($request->filled('connected_tools')) {
            $data['connected_tools'] = array_map('trim', explode(',', $request->connected_tools));
        } else {
            $data['connected_tools'] = [];
        }

        Course::create($data);

        return redirect()->back()->with('success', 'Course created successfully.');
    }

    public function courseUpdate(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,draft',
            'connected_tools' => 'nullable|string',
        ]);

        $data = $request->except('thumbnail');

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $data['thumbnail'] = '/storage/' . $path;
        }

        if ($request->filled('connected_tools')) {
            $data['connected_tools'] = array_map('trim', explode(',', $request->connected_tools));
        } else {
            $data['connected_tools'] = [];
        }

        $course->update($data);

        return redirect()->back()->with('success', 'Course updated successfully.');
    }

    public function courseDestroy(Course $course)
    {
        $course->delete();
        return redirect()->back()->with('success', 'Course deleted successfully.');
    }

    /**
     * Manage videos within a specific course.
     */
    public function manageCourseContents(Course $course)
    {
        $course->load('contents');
        $contents = $course->contents()->orderBy('sort_order')->get();
        return view('admin.courses.manage', compact('course', 'contents'));
    }

    public function analyticsIndex()
    {
        $users = User::where('is_admin', false)
            ->with(['videoProgress.content'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Aggregate Data for Charts
        $categoryStats = \App\Models\Content::select('category', \DB::raw('count(*) as count'))
            ->groupBy('category')
            ->get();

        $totalViews = \App\Models\UserVideoProgress::count();
        $avgCompletion = \App\Models\UserVideoProgress::avg('completion_percent') ?? 0;
        $totalWatchTime = \App\Models\UserVideoProgress::sum('watched_seconds');
        $uniqueViewers = \App\Models\UserVideoProgress::distinct('user_id')->count();

        return view('admin.analytics', compact(
            'users',
            'categoryStats',
            'totalViews',
            'avgCompletion',
            'totalWatchTime',
            'uniqueViewers'
        ));
    }

    public function settingsIndex()
    {
        return view('admin.settings');
    }

    public function profileOptionsIndex()
    {
        $learningGoals = \App\Models\LearningGoal::orderBy('title')->get();
        $experienceLevels = \App\Models\ExperienceLevel::orderBy('title')->get();
        return view('admin.profile-options.index', compact('learningGoals', 'experienceLevels'));
    }

    public function storeLearningGoal(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:learning_goals,title',
        ]);

        \App\Models\LearningGoal::create([
            'title' => $request->title,
        ]);

        return redirect()->back()->with('success', 'Learning goal added successfully.');
    }

    public function destroyLearningGoal(\App\Models\LearningGoal $goal)
    {
        $goal->delete();
        return redirect()->back()->with('success', 'Learning goal deleted successfully.');
    }

    public function storeExperienceLevel(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:experience_levels,title',
        ]);

        \App\Models\ExperienceLevel::create([
            'title' => $request->title,
        ]);

        return redirect()->back()->with('success', 'Experience level added successfully.');
    }

    public function destroyExperienceLevel(\App\Models\ExperienceLevel $level)
    {
        $level->delete();
        return redirect()->back()->with('success', 'Experience level deleted successfully.');
    }

    // Category Management
    public function categoriesIndex()
    {
        $categories = Category::withCount(['contents', 'courses'])->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'status' => 'required|in:active,draft',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function categoryUpdate(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'status' => 'required|in:active,draft',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    public function categoryDestroy(Category $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }
}
