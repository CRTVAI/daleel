<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogApiController extends Controller
{
    /**
     * Get a list of blog posts in JSON format.
     */
    public function index(Request $request)
    {
        $query = BlogPost::where('status', 'published')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhere('content', 'like', $searchTerm);
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $blogs = $query->paginate($request->input('per_page', 10));

        return response()->json([
            'success' => true,
            'message' => 'Blog posts retrieved successfully.',
            'data' => $blogs
        ]);
    }

    /**
     * Get details of a single blog post.
     */
    public function show($idOrSlug)
    {
        $blog = BlogPost::where('id', $idOrSlug)
            ->orWhere('slug', $idOrSlug)
            ->first();

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog post not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $blog
        ]);
    }

    /**
     * Upload a new blog post.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'content' => 'required',
            'excerpt' => 'nullable|string',
            'cover_image' => 'nullable', // file or string URL
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'author_name' => 'nullable|string|max:100',
            'read_time_minutes' => 'nullable|integer',
            'status' => 'nullable|in:published,draft',
            'is_featured' => 'nullable|boolean',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('blogs', 'public');
            $coverImagePath = '/storage/' . $path;
        } elseif ($request->filled('cover_image')) {
            $coverImagePath = $request->cover_image; // Accept string URL directly
        }

        $blog = BlogPost::create([
            'title' => $request->title,
            'slug' => $request->slug ?: Str::slug($request->title),
            'content' => $request->content,
            'excerpt' => $request->excerpt ?: Str::limit(strip_tags($request->content), 150),
            'cover_image' => $coverImagePath,
            'category' => $request->category ?: 'General',
            'tags' => $request->tags,
            'author_name' => $request->author_name ?: 'Admin',
            'read_time_minutes' => $request->read_time_minutes ?: 5,
            'status' => $request->status ?: 'published',
            'is_featured' => filter_var($request->is_featured, FILTER_VALIDATE_BOOLEAN),
            'meta_title' => $request->meta_title ?: $request->title,
            'meta_description' => $request->meta_description ?: Str::limit(strip_tags($request->excerpt ?? $request->content), 150),
            'meta_keywords' => $request->meta_keywords,
            'published_at' => ($request->status ?: 'published') === 'published' ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Blog post uploaded successfully via API.',
            'data' => $blog
        ], 201);
    }
}
