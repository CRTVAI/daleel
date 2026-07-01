<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ExternalContentController extends Controller
{
    public function store(Request $request)
    {
        // 1. Security Check: Use the existing OpenAI key from .env as the password
        $apiKey = env('OPENAI_API_KEY', 'default_secret_key_change_me');
        if ($request->header('X-API-Key') !== $apiKey) {
            return response()->json(['error' => 'Unauthorized content request.'], 401);
        }

        // 2. Validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'required|string', 
            'video_url_ar' => 'nullable|url',
            'category' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'skill_level' => 'required|in:Beginner,Intermediate,Advanced',
            'course_id' => 'nullable|exists:courses,id',
            'course_name' => 'nullable|string|max:255',
            'course_thumbnail' => 'nullable|string',
            'section_part_label' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'connected_tools' => 'nullable', 
            'description' => 'nullable|string',
            'tags' => 'nullable|string',
            'duration_seconds' => 'nullable|integer',
            'is_featured' => 'nullable|boolean',
            'type' => 'nullable|string|in:video,article,course',
            'status' => 'nullable|string|in:active,inactive',
            'thumbnail_base64' => 'nullable|string',
            'is_individual' => 'nullable|boolean',
            'reference_url' => 'nullable|string',
            'video_duration' => 'nullable|string',
        ]);

        $data = $validated;
        $data['is_featured'] = $request->boolean('is_featured');
        $data['language'] = $request->filled('video_url_ar') ? 'both' : 'en';

        // --- Parse Connected Tools early ---
        $incomingTools = [];
        if ($request->filled('connected_tools')) {
            if (is_string($request->connected_tools)) {
                $incomingTools = array_map('trim', explode(',', $request->connected_tools));
            } else {
                $incomingTools = (array)$request->connected_tools;
            }
        }
        $data['connected_tools'] = $incomingTools;

        // --- Handle Course Association ---
        $isIndividual = $request->boolean('is_individual', true); // Default to individual if not specified

        if (!$isIndividual) {
            if ($request->filled('course_name')) {
                $courseName = trim($request->course_name);
                $course = \App\Models\Course::firstOrCreate(
                    ['title' => $courseName],
                    [
                        'category_id' => $request->category_id ?? null,
                        'thumbnail' => $request->course_thumbnail ?? null,
                        'status' => 'active',
                        'connected_tools' => $incomingTools
                    ]
                );

                // Merge tools if the course already existed to keep a cumulative list of tools
                if (!$course->wasRecentlyCreated && !empty($incomingTools)) {
                    $existingTools = is_array($course->connected_tools) ? $course->connected_tools : [];
                    $mergedTools = array_values(array_unique(array_merge($existingTools, $incomingTools)));
                    $course->update(['connected_tools' => $mergedTools]);
                }

                $data['course_id'] = $course->id;
            } elseif ($request->filled('course_id')) {
                $course = \App\Models\Course::find($request->course_id);
                if ($course) {
                    if (!empty($incomingTools)) {
                        $existingTools = is_array($course->connected_tools) ? $course->connected_tools : [];
                        $mergedTools = array_values(array_unique(array_merge($existingTools, $incomingTools)));
                        $course->update(['connected_tools' => $mergedTools]);
                    }
                    $data['course_id'] = $course->id;
                }
            }
        } else {
            // Ensure no course is linked if marked as individual
            $data['course_id'] = null;
        }

        // 3. Handle Thumbnail (Base64 to S3/Public)
        if ($request->filled('thumbnail_base64')) {
            try {
                $imageData = $request->thumbnail_base64;
                if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                    $type = strtolower($type[1]);
                    $imageData = base64_decode($imageData);

                    if ($imageData !== false) {
                        $fileName = 'thumbnails/' . Str::slug($data['title']) . '-' . time() . '.' . $type;
                        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
                        Storage::disk($disk)->put($fileName, $imageData, 'public');
                        $data['thumbnail'] = Storage::disk($disk)->url($fileName);
                    }
                }
            } catch (\Exception $e) {
                Log::error('External Thumbnail Upload Failed: ' . $e->getMessage());
            }
        }

        // 4. Tools Mapping - already handled above, ensure array defaults if empty
        if (!isset($data['connected_tools'])) {
            $data['connected_tools'] = [];
        }

        $data['type'] = $request->get('type', 'video');
        $data['status'] = $request->get('status', 'active');
        $data['slug'] = Str::slug($data['title']) . '-' . rand(1000, 9999);

        // Remove the base64 from data before saving to DB
        unset($data['thumbnail_base64']);
        unset($data['course_name']); // Not a DB column

        $content = Content::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Content synchronized successfully.',
            'content_id' => $content->id,
            'thumbnail_url' => $content->thumbnail_url,
            'view_url' => route('learn.watch', $content->id)
        ], 201);
    }
}
