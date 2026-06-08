<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExternalContentController extends Controller
{
    public function store(Request $request)
    {
        // Matching the exact validation from AdminController@contentStore
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'required|url',
            'video_url_ar' => 'nullable|url',
            'category' => 'nullable|string',
            'skill_level' => 'required|in:Beginner,Intermediate,Advanced',
            'course_id' => 'nullable|exists:courses,id',
            'section_part_label' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'connected_tools' => 'nullable', // Can be string or array
            'description' => 'nullable|string',
            'tags' => 'nullable|string',
            'duration_seconds' => 'nullable|integer',
            'is_featured' => 'nullable|boolean',
            'type' => 'nullable|string|in:video,article,course',
            'status' => 'nullable|string|in:active,inactive'
        ]);

        $data = $validated;
        $data['is_featured'] = $request->boolean('is_featured');

        // Smart Language Mapping (Mirroring AdminController logic)
        $data['language'] = $request->filled('video_url_ar') ? 'both' : 'en';

        // Tools Mapping (Mirroring AdminController logic + API flexibility)
        if ($request->filled('connected_tools')) {
            if (is_string($request->connected_tools)) {
                $data['connected_tools'] = array_map('trim', explode(',', $request->connected_tools));
            } else {
                $data['connected_tools'] = (array)$request->connected_tools;
            }
        } else {
            $data['connected_tools'] = [];
        }

        // Standard fields not in basic admin store but needed for platform
        $data['type'] = $request->get('type', 'video');
        $data['status'] = $request->get('status', 'active');
        $data['slug'] = Str::slug($data['title']) . '-' . rand(1000, 9999);

        $content = Content::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Content synchronized successfully with admin library.',
            'content_id' => $content->id,
            'language_assigned' => $data['language'],
            'url' => route('learn.watch', $content->id)
        ], 201);
    }
}
