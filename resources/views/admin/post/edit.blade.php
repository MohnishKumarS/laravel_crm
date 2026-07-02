{{-- resources/views/admin/posts/edit.blade.php --}}
@extends('admin.layouts.main')

@section('title', 'Edit Post | Yuukke Dashboard')

@section('content')
    {{-- Breadcrumb --}}
    <div class="page-header">
        <h3 class="fw-bold mb-3">Blog</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="{{ url('/') }}">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('posts.index') }}">Blog</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Edit</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Edit Post: {{ $post->title }}</div>
                </div>
                <div class="card-body">

                    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="title">Title</label>
                            <input name="title" type="text" class="form-control" id="title"
                                value="{{ old('title', $post->title) }}" required />
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input name="slug" type="text" class="form-control" id="slug"
                                value="{{ old('slug', $post->slug) }}" required />
                            @error('slug')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="featured_image">Featured Image</label>
                            @if ($post->featured_image)
                                <div class="mb-2">
                                    <img src="{{ $post->featured_image_url }}" alt="Current image"
                                        style="max-height: 150px; border-radius: 6px;" />
                                    <p class="small text-muted mb-0">Current image — upload a new one below to replace it
                                    </p>
                                </div>
                            @endif
                            <input type="file" name="featured_image" class="form-control-file" id="featured_image"
                                accept="image/*" />
                            @error('featured_image')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea name="content" id="content" class="form-control" rows="12">{{ old('content', $post->content) }}</textarea>
                            @error('content')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>
                                    Draft</option>
                                <option value="published"
                                    {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="card-action mt-3">
                            <button type="submit" class="btn btn-success">Update Post</button>
                            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- CKEditor 5 via CDN --}}


@endsection

@push('scripts')
    <script>
        ClassicEditor
            .create(document.querySelector('#content'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
