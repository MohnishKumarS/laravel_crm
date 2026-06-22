<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(20);
        return view('admin.post.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.post.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'           => 'required|string|max:255',
            'slug'            => 'nullable|string|unique:posts,slug',
            'content'         => 'required|string',
            'featured_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'status'          => 'required|in:draft,published',
        ]);

        $slug = $request->slug ?: Str::slug($request->title);

        // Ensure slug uniqueness even if auto-generated from a duplicate title
        $originalSlug = $slug;
        $count = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('posts', 'public');
        }

        Post::create([
            'title'          => $request->title,
            'slug'           => $slug,
            'content'        => $request->content,
            'featured_image' => $imagePath,
            'status'         => $request->status,
            'published_at'   => $request->status === 'published' ? now() : null,
        ]);

        return redirect()->route('posts.index')->with('success', 'Post created');
    }

    public function show(string $id)
    {
        $post = Post::findOrFail($id);
        return view('admin.post.show', compact('post'));
    }

    public function edit(string $id)
    {
        $post = Post::findOrFail($id);
        return view('admin.post.edit', compact('post'));
    }

    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'title'          => 'required|string|max:255',
            'slug'           => 'required|string|unique:posts,slug,' . $post->id,
            'content'        => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'status'         => 'required|in:draft,published',
        ]);

        $imagePath = $post->featured_image;
        if ($request->hasFile('featured_image')) {
            // Remove old image before storing the new one
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('featured_image')->store('posts', 'public');
        }

        $post->update([
            'title'          => $request->title,
            'slug'           => $request->slug,
            'content'        => $request->content,
            'featured_image' => $imagePath,
            'status'         => $request->status,
            'published_at'   => $request->status === 'published'
                                  ? ($post->published_at ?? now())
                                  : null,
        ]);

        return redirect()->route('posts.index')->with('success', 'Post updated');
    }

    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);

        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted');
    }
}
