<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BlogPost::where('status', 'published')->orderBy('created_at', 'desc');

        // Search
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhere('content', 'like', $searchTerm)
                  ->orWhere('excerpt', 'like', $searchTerm);
            });
        }

        // Category Filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $allCategories = BlogPost::where('status', 'published')
            ->whereNotNull('category')
            ->groupBy('category')
            ->pluck('category')
            ->all();

        $blogs = $query->paginate(9);

        // Featured post (latest featured or just latest post)
        $featured = BlogPost::where('status', 'published')
            ->where('is_featured', true)
            ->first() ?: BlogPost::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->first();

        // SEO values
        $seo = [
            'title' => 'Resources & Insights — Daleel AI',
            'meta_description' => 'Browse our latest guides, tutorials, design resources, and enterprise AI onboarding strategy playbooks.',
            'meta_keywords' => 'AI, dynamic workflows, learning roadmaps, training portal, enterprise prompts',
            'canonical' => route('public.blog')
        ];

        return view('blog.index', compact('blogs', 'allCategories', 'featured', 'seo'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $blog = BlogPost::where('slug', $slug)->firstOrFail();

        // If the post is draft, only show it to authenticated admins so they can preview it
        if ($blog->status !== 'published' && (!auth()->check() || !auth()->user()->is_admin)) {
            abort(404);
        }

        // Related blogs
        $related = BlogPost::where('status', 'published')
            ->where('category', $blog->category)
            ->where('id', '!=', $blog->id)
            ->take(3)
            ->get();

        // SEO meta
        $seo = [
            'title' => ($blog->meta_title ?: $blog->title) . ' | Daleel AI Blog',
            'meta_description' => $blog->meta_description ?: $blog->excerpt,
            'meta_keywords' => $blog->meta_keywords ?: str_replace(' ', ', ', $blog->title),
            'canonical' => route('public.blog.show', $blog->slug),
            'og_image' => $blog->cover_image ? asset($blog->cover_image) : asset('images/default-blog.jpg')
        ];

        return view('blog.show', compact('blog', 'related', 'seo'));
    }
}
