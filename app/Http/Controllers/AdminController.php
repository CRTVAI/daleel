<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Content;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    // User Management
    public function usersIndex()
    {
        $users = User::where('is_admin', false)->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function userProfile(User $user)
    {
        return view('admin.users.profile', compact('user'));
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
            'account_type' => $request->account_type ?? 'Free Plan',
            'primary_goal' => $request->primary_goal,
            'experience_level' => $request->experience_level,
            'tools' => $request->tools ? array_map('trim', explode(',', $request->tools)) : [],
            'interests' => $request->interests ? array_map('trim', explode(',', $request->interests)) : [],
        ]);

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function userUpdate(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'account_type' => $request->account_type,
            'primary_goal' => $request->primary_goal,
            'experience_level' => $request->experience_level,
            'tools' => $request->tools ? array_map('trim', explode(',', $request->tools)) : [],
            'interests' => $request->interests ? array_map('trim', explode(',', $request->interests)) : [],
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

    // Content Management
    public function contentsIndex(Request $request)
    {
        $query = Content::query();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('connected_tools', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Skill Level Filter
        if ($request->filled('skill_level')) {
            $query->where('skill_level', $request->skill_level);
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

        // Statistics for the header
        $stats = [
            'total_videos' => Content::count(),
            'total_courses' => Course::count(),
            'standalone_videos' => Content::whereNull('course_id')->count(),
            'total_duration' => Content::sum('duration_seconds'),
        ];
        
        return view('admin.contents.index', compact('contents', 'categories', 'courses', 'stats'));
    }

    public function contentStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'required|url',
            'category' => 'nullable|string',
            'skill_level' => 'required|in:Beginner,Intermediate,Advanced',
            'course_id' => 'nullable|exists:courses,id',
            'section_part_label' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'connected_tools' => 'nullable|string',
        ]);

        $data = $request->all();
        if ($request->filled('connected_tools')) {
            $data['connected_tools'] = array_map('trim', explode(',', $request->connected_tools));
        } else {
            $data['connected_tools'] = [];
        }

        Content::create($data);

        return redirect()->back()->with('success', 'Content added successfully.');
    }

    public function contentUpdate(Request $request, Content $content)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'required|url',
            'category' => 'nullable|string',
            'skill_level' => 'required|in:Beginner,Intermediate,Advanced',
            'course_id' => 'nullable|exists:courses,id',
            'section_part_label' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'connected_tools' => 'nullable|string',
        ]);

        $data = $request->all();
        if ($request->filled('connected_tools')) {
            $data['connected_tools'] = array_map('trim', explode(',', $request->connected_tools));
        } else {
            $data['connected_tools'] = [];
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
        return view('admin.courses.index', compact('courses'));
    }

    public function courseStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,draft',
        ]);

        $data = $request->except('thumbnail');

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $data['thumbnail'] = '/storage/' . $path;
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
        ]);

        $data = $request->except('thumbnail');

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $data['thumbnail'] = '/storage/' . $path;
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
}
