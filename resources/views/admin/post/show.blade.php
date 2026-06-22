{{-- resources/views/admin/posts/show.blade.php --}}
@extends('admin.layouts.main')

@section('title', $post->title . ' | Yuukke Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <div class="card-title">{{ $post->title }}</div>
                <div>
                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <a href="{{ route('posts.index') }}" class="btn btn-secondary btn-sm">Back</a>
                </div>
            </div>
            <div class="card-body">

                @if ($post->featured_image)
                    <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}"
                        class="img-fluid mb-3" style="max-height: 350px; border-radius: 8px;" />
                @endif

                <p>
                    <strong>Slug:</strong> <code>{{ $post->slug }}</code><br>
                    <strong>Status:</strong>
                    @if ($post->status === 'published')
                        <span class="badge badge-success">Published</span>
                    @else
                        <span class="badge badge-secondary">Draft</span>
                    @endif
                    <br>
                    <strong>Published At:</strong> {{ $post->published_at?->format('d M Y, h:i A') ?? '—' }}
                </p>

                <p><strong>Public API:</strong> <code>GET /api/posts/{{ $post->slug }}</code></p>

                <hr>

                <div class="post-content">
                    {!! $post->content !!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
