{{-- resources/views/admin/posts/create.blade.php --}}
@extends('admin.layouts.main')

@section('title', 'Add Post | Yuukke Dashboard')

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
                <a href="#">Create</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Create Blog Post</div>
                </div>
                <div class="card-body">

                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="title">Title</label>
                            <input name="title" type="text" class="form-control" id="title"
                                placeholder="e.g. 5 Tips for Better Sleep" value="{{ old('title') }}" required />
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug (optional — auto-generated from title if left blank)</label>
                            <input name="slug" type="text" class="form-control" id="slug"
                                placeholder="5-tips-for-better-sleep" value="{{ old('slug') }}" />
                            @error('slug')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="featured_image">Featured Image</label>
                            <input type="file" name="featured_image" class="form-control-file" id="featured_image"
                                accept="image/*" />
                            @error('featured_image')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea name="content" id="content" class="form-control" rows="12">{{ old('content') }}</textarea>
                            @error('content')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published
                                </option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="card-action mt-3">
                            <button type="submit" class="btn btn-success">Save Post</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>



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
