<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminBlogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $blogs = BlogPost::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'content' => 'required',
            'excerpt' => 'nullable|string|max:1000',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string|max:255',
            'author_name' => 'nullable|string|max:100',
            'read_time_minutes' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published',
            'is_featured' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('blogs', 'public');
            $coverImagePath = '/storage/' . $path;
        }

        BlogPost::create([
            'title' => $request->title,
            'slug' => $request->slug ?: Str::slug($request->title),
            'content' => $request->content,
            'excerpt' => $request->excerpt,
            'cover_image' => $coverImagePath,
            'category' => $request->category ?: 'General',
            'tags' => $request->tags,
            'author_name' => $request->author_name ?: 'Admin',
            'read_time_minutes' => $request->read_time_minutes ?: 5,
            'status' => $request->status,
            'is_featured' => $request->has('is_featured') ? true : false,
            'meta_title' => $request->meta_title ?: $request->title,
            'meta_description' => $request->meta_description ?: Str::limit(strip_tags($request->excerpt ?? $request->content), 150),
            'meta_keywords' => $request->meta_keywords,
            'published_at' => $request->status === 'published' ? now() : null,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post created successfully.');
    }

    public function update(Request $request, BlogPost $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts,slug,' . $blog->id,
            'content' => 'required',
            'excerpt' => 'nullable|string|max:1000',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string|max:255',
            'author_name' => 'nullable|string|max:100',
            'read_time_minutes' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        $coverImagePath = $blog->cover_image;
        if ($request->hasFile('cover_image')) {
            // Delete old cover image
            if ($blog->cover_image && !str_starts_with($blog->cover_image, '/images/')) {
                $oldPath = str_replace('/storage/', '', $blog->cover_image);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('cover_image')->store('blogs', 'public');
            $coverImagePath = '/storage/' . $path;
        }

        $blog->update([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'content' => $request->content,
            'excerpt' => $request->excerpt,
            'cover_image' => $coverImagePath,
            'category' => $request->category ?: 'General',
            'tags' => $request->tags,
            'author_name' => $request->author_name ?: 'Admin',
            'read_time_minutes' => $request->read_time_minutes ?: 5,
            'status' => $request->status,
            'is_featured' => $request->has('is_featured') ? true : false,
            'meta_title' => $request->meta_title ?: $request->title,
            'meta_description' => $request->meta_description ?: Str::limit(strip_tags($request->excerpt ?? $request->content), 150),
            'meta_keywords' => $request->meta_keywords,
            'published_at' => ($request->status === 'published' && !$blog->published_at) ? now() : $blog->published_at,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blog)
    {
        if ($blog->cover_image && !str_starts_with($blog->cover_image, '/images/')) {
            $oldPath = str_replace('/storage/', '', $blog->cover_image);
            Storage::disk('public')->delete($oldPath);
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post deleted successfully.');
    }
}
