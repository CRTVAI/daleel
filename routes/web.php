<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EnterpriseContactController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/enterprise', function () {
    return view('enterprise');
});

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::post('/enterprise/contact', [EnterpriseContactController::class, 'send'])->name('enterprise.contact.send');



Route::get('/videos', [App\Http\Controllers\LearningController::class, 'explore'])->name('videos.public');

Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    return response('Application cache cleared successfully.');
})->name('cache.clear');

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->is_admin) {
        return redirect()->route('admin.dashboard');
    }
    
    if (!$user->hasVerifiedEmail()) {
        auth()->guard('web')->logout();
        return redirect()->route('login')->with('status', 'verification-required');
    }
    
    return redirect()->route('user.dashboard');
})->middleware(['auth'])->name('dashboard');

// Admin Only Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\HomeController::class, 'adminIndex'])->name('admin.dashboard');
    Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'usersIndex'])->name('admin.users.index');
    Route::get('/admin/users/{user}/profile', [App\Http\Controllers\AdminController::class, 'userProfile'])->name('admin.users.profile');
    Route::post('/admin/users', [App\Http\Controllers\AdminController::class, 'userStore'])->name('admin.users.store');
    Route::put('/admin/users/{user}', [App\Http\Controllers\AdminController::class, 'userUpdate'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [App\Http\Controllers\AdminController::class, 'userDestroy'])->name('admin.users.destroy');
    Route::patch('/admin/users/{user}/toggle-team', [App\Http\Controllers\AdminController::class, 'toggleTeamAccess'])->name('admin.users.toggle-team');
    Route::get('/admin/contents', [App\Http\Controllers\AdminController::class, 'contentsIndex'])->name('admin.contents.index');
    Route::post('/admin/contents', [App\Http\Controllers\AdminController::class, 'contentStore'])->name('admin.contents.store');
    Route::put('/admin/contents/{content}', [App\Http\Controllers\AdminController::class, 'contentUpdate'])->name('admin.contents.update');
    Route::delete('/admin/contents/{content}', [App\Http\Controllers\AdminController::class, 'contentDestroy'])->name('admin.contents.destroy');

    // Category Management
    Route::get('/admin/categories', [App\Http\Controllers\AdminController::class, 'categoriesIndex'])->name('admin.categories.index');
    Route::post('/admin/categories', [App\Http\Controllers\AdminController::class, 'categoryStore'])->name('admin.categories.store');
    Route::put('/admin/categories/{category}', [App\Http\Controllers\AdminController::class, 'categoryUpdate'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [App\Http\Controllers\AdminController::class, 'categoryDestroy'])->name('admin.categories.destroy');
    Route::get('/admin/courses', [App\Http\Controllers\AdminController::class, 'coursesIndex'])->name('admin.courses.index');
    Route::post('/admin/courses', [App\Http\Controllers\AdminController::class, 'courseStore'])->name('admin.courses.store');
    Route::get('/admin/courses/{course}/manage', [App\Http\Controllers\AdminController::class, 'manageCourseContents'])->name('admin.courses.manage');
    Route::put('/admin/courses/{course}', [App\Http\Controllers\AdminController::class, 'courseUpdate'])->name('admin.courses.update');
    Route::delete('/admin/courses/{course}', [App\Http\Controllers\AdminController::class, 'courseDestroy'])->name('admin.courses.destroy');
    Route::get('/admin/analytics', [App\Http\Controllers\AdminController::class, 'analyticsIndex'])->name('admin.analytics');
    Route::get('/admin/settings', [App\Http\Controllers\AdminController::class, 'settingsIndex'])->name('admin.settings');
    Route::get('/admin/profile-options', [App\Http\Controllers\AdminController::class, 'profileOptionsIndex'])->name('admin.profile-options.index');
    Route::post('/admin/profile-options/learning-goals', [App\Http\Controllers\AdminController::class, 'storeLearningGoal'])->name('admin.profile-options.learning-goals.store');
    Route::delete('/admin/profile-options/learning-goals/{goal}', [App\Http\Controllers\AdminController::class, 'destroyLearningGoal'])->name('admin.profile-options.learning-goals.destroy');
    Route::post('/admin/profile-options/experience-levels', [App\Http\Controllers\AdminController::class, 'storeExperienceLevel'])->name('admin.profile-options.experience-levels.store');
    Route::delete('/admin/profile-options/experience-levels/{level}', [App\Http\Controllers\AdminController::class, 'destroyExperienceLevel'])->name('admin.profile-options.experience-levels.destroy');
    Route::resource('/admin/tools', App\Http\Controllers\ToolController::class)->names('admin.tools');
});

// Protected User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [App\Http\Controllers\HomeController::class, 'userIndex'])->name('user.dashboard');
    Route::get('/roadmap', [App\Http\Controllers\LearningController::class, 'roadmap'])->name('roadmap');
    Route::get('/extension-setup', [App\Http\Controllers\HomeController::class, 'extensionSetup'])->name('extension.install');
    Route::get('/extension-data', [App\Http\Controllers\HomeController::class, 'extensionData'])->name('extension.data');
    Route::post('/extension-data/reset', [App\Http\Controllers\HomeController::class, 'resetExtensionData'])->name('extension.data.reset');
    Route::get('/learn/explore', [App\Http\Controllers\LearningController::class, 'explore'])->name('learn.explore');
    Route::get('/learn/courses/{course}', [App\Http\Controllers\LearningController::class, 'courseView'])->name('course.view');
    Route::post('/learn/progress/save', [App\Http\Controllers\LearningController::class, 'saveProgress'])->name('learn.progress.save');
    Route::post('/extension/verification-codes', [\App\Http\Controllers\Api\Extension\VerificationCodeController::class, 'store'])->name('extension.verify-code');
    Route::post('/extension/device/{device}/revoke', [App\Http\Controllers\HomeController::class, 'revokeDevice'])->name('extension.device.revoke');
    
    // Team Management
    Route::get('/team', [App\Http\Controllers\TeamController::class, 'index'])->name('team.index');
    Route::post('/team/departments', [App\Http\Controllers\TeamController::class, 'storeDepartment'])->name('team.departments.store');
    Route::delete('/team/departments/{department}', [App\Http\Controllers\TeamController::class, 'destroyDepartment'])->name('team.departments.destroy');
    Route::post('/team/employees', [App\Http\Controllers\TeamController::class, 'storeEmployee'])->name('team.employees.store');
    Route::delete('/team/employees/{employee}', [App\Http\Controllers\TeamController::class, 'destroyEmployee'])->name('team.employees.destroy');
    Route::post('/team/employees/{employee}/regenerate-code', [App\Http\Controllers\TeamController::class, 'regenerateCode'])->name('team.employees.regenerate-code');
    Route::get('/team/employees/{employee}/top-sites', [App\Http\Controllers\TeamController::class, 'employeeTopSites'])->name('team.employees.top-sites');
    Route::get('/team/departments/{department}/top-sites', [App\Http\Controllers\TeamController::class, 'departmentTopSites'])->name('team.departments.top-sites');
    Route::get('/team/overall-top-sites', [App\Http\Controllers\TeamController::class, 'overallTopSites'])->name('team.overall-top-sites');
    Route::get('/team/employees/{employee}/help-requests', [App\Http\Controllers\TeamController::class, 'employeeHelpRequests'])->name('team.employees.help-requests');
    
    // Onboarding
    Route::post('/onboarding/store', [App\Http\Controllers\OnboardingController::class, 'store'])->name('onboarding.store');
});

// ── AI MENTOR & SEARCH ──
Route::get('/ai-mentor', [App\Http\Controllers\AiController::class, 'mentor'])->name('ai.mentor');
Route::get('/search/advanced', [App\Http\Controllers\AdvancedSearchController::class, 'search'])->name('search.advanced');
Route::get('/learn/{content}', [App\Http\Controllers\LearningController::class, 'watch'])->name('learn.watch');

// ── ROADMAP WIZARD (Single Page) ──
Route::middleware(['auth'])->group(function () {
    Route::get('/roadmap', [App\Http\Controllers\RoadmapController::class, 'index'])->name('roadmap');
    Route::get('/roadmap/wizard', [App\Http\Controllers\RoadmapController::class, 'wizard'])->name('roadmap.wizard');
    Route::get('/roadmap/{roadmap}', [App\Http\Controllers\RoadmapController::class, 'show'])->name('roadmap.show');
    Route::post('/roadmap/api/categories', [App\Http\Controllers\RoadmapController::class, 'getFocusCategories'])->name('roadmap.api.categories');
    Route::post('/roadmap/api/generate', [App\Http\Controllers\RoadmapController::class, 'generateRoadmap'])->name('roadmap.api.generate');
});

// Public JSON API for static site video library
Route::get('/api/public/videos', function () {
    $videos = \App\Models\Content::where('status', 'active')
        ->where('type', 'video')
        ->orderBy('created_at', 'desc')
        ->get(['id', 'title', 'category', 'skill_level', 'duration_label', 'thumbnail_url', 'description', 'tags', 'youtube_id'])
        ->map(fn($v) => [
            'id'          => $v->id,
            'title'       => $v->title,
            'category'    => $v->category,
            'level'       => $v->skill_level,
            'duration'    => $v->duration_label,
            'thumb'       => $v->thumbnail_url,
            'description' => $v->description,
            'tags'        => $v->tags,
            'youtubeId'   => $v->youtube_id,
            'watchUrl'    => '/learn/' . $v->id,
        ]);
    return response()->json($videos);
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit'); // Placeholder for closure in previous view
    Route::get('/integrations', function () {
        $tools = \App\Models\Tool::where('status', 'active')->orderBy('name')->get();
        return view('integrations', compact('tools'));
    })->name('integrations');
    Route::post('/profile/connections/toggle', function (Illuminate\Http\Request $request) {
        $user = auth()->user();
        $toolName = $request->tool_name;
        $connections = is_array($user->connections) ? $user->connections : [];
        if (in_array($toolName, $connections)) {
            $connections = array_values(array_diff($connections, [$toolName]));
            $msg = "Disconnected from " . $toolName;
        } else {
            $connections[] = $toolName;
            $msg = "Successfully connected to " . $toolName;
        }
        $user->update(['connections' => $connections]);
        return redirect()->back()->with('success', $msg);
    })->name('profile.connections.toggle');
    Route::get('/bookmarks', [App\Http\Controllers\BookmarkController::class, 'index'])->name('bookmarks');
    Route::post('/bookmarks/{content}/toggle', [App\Http\Controllers\BookmarkController::class, 'toggle'])->name('bookmarks.toggle');
    Route::get('/progress', function () { return view('progress'); })->name('progress');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/activity-history', [App\Http\Controllers\HomeController::class, 'activityHistory'])->middleware(['auth', 'verified'])->name('activity.history');
require __DIR__.'/auth.php';

Route::get('/{page}', function ($page) {
    $validPages = [
        'how-it-works', 'enterprise', 'success-stories', 'pricing',
        'about', 'contact', 'blog', 'tools-directory', 'help-center',
        'terms', 'privacy', 'cookies', 'learning-paths', 'chrome-extension',
        'lesson'
    ];
    
    if (in_array($page, $validPages)) {
        return view($page);
    }
    
    abort(404);
})->where('page', '^(?!admin|user|dashboard|login|register|roadmap|logout|forgot-password|reset-password|verify-email|profile|integrations|bookmarks|progress|activity-history|ai-mentor|learn|clear-cache|team|extension-setup|extension-data|api|videos).*$');
