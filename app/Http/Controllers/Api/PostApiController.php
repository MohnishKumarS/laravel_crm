<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Str;


class PostApiController extends Controller
{
    /**
     * GET /api/posts
     * Returns all published posts, latest first.
     */
    public function index()
    {
        $posts = Post::published()
            ->orderByDesc('published_at')
            ->get()
            ->map(fn ($post) => $this->formatPost($post, false));

        return response()->json($posts);
    }

    /**
     * GET /api/posts/{slug}
     * Returns a single published post with full content.
     */
    public function show(string $slug)
    {
        $post = Post::published()->where('slug', $slug)->first();

        if (! $post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json($this->formatPost($post, true));
    }

    protected function formatPost(Post $post, bool $withContent): array
    {
        $data = [
            'title'           => $post->title,
            'slug'            => $post->slug,
            'featured_image'  => $post->featured_image_url,
            'published_at'    => $post->published_at?->toIso8601String(),
        ];

        if ($withContent) {
            $data['content'] = $post->content;
        } else {
            // For list views, send a stripped excerpt instead of full HTML
            $data['excerpt'] = Str::limit(strip_tags($post->content), 150);
        }

        return $data;
    }
}
