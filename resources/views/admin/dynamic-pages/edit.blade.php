@extends('admin.layouts.main')

@section('content')
    <div class="">

        <div class="page-header">
            <h3 class="fw-bold mb-3">Dynamic Pages</h3>

            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ route('dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>

                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>

                <li class="nav-item">
                    <a href="{{ route('shop.dynamic-pages.index') }}">Dynamic Pages</a>
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
                        <div class="card-title">Edit Page</div>
                    </div>
                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Please fix the following:</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        <form action="{{ route('shop.dynamic-pages.update', $dynamicPage->id) }}" method="POST"
                            enctype="multipart/form-data">

                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label>Title</label>

                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title', $dynamicPage->title) }}" required>
                            </div>

                            <div class="form-group">
                                <label>Subtitle</label>

                                <input type="text" name="subtitle" class="form-control"
                                    value="{{ old('subtitle', $dynamicPage->subtitle) }}">
                            </div>

                            <div class="form-group">
                                <label>Heading</label>

                                <input type="text" name="heading" class="form-control"
                                    value="{{ old('heading', $dynamicPage->heading) }}">
                            </div>

                            <div class="form-group">
                                <label>Description</label>

                                <textarea name="description" rows="6" class="form-control">{{ old('description', $dynamicPage->description) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Page URL</label>

                                <input type="text" name="page_url" class="form-control"
                                    value="{{ old('page_url', $dynamicPage->page_url) }}" required>
                            </div>

                            <div class="form-group">
                                <label>Products Key</label>

                                <input type="text" name="products_id" class="form-control"
                                    value="{{ old('products_id', $dynamicPage->products_id) }}">
                            </div>

                            <div class="form-group">
                                <label>Banner Image</label>

                                <input type="file" name="banner_image" class="form-control">

                                @if ($dynamicPage->banner_image)
                                    <img src="{{ asset($dynamicPage->banner_image) }}" width="150" class="mt-2">
                                @endif

                            </div>

                            <div class="form-group">
                                <label>Meta Title</label>

                                <input type="text" name="meta_title" class="form-control"
                                    value="{{ old('meta_title', $dynamicPage->meta_title) }}">
                            </div>

                            <div class="form-group">
                                <label>Meta Description</label>

                                <textarea name="meta_description" class="form-control">{{ old('meta_description', $dynamicPage->meta_description) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Status</label>

                                <select name="status" class="form-select">
                                    <option value="1" @selected($dynamicPage->status == '1')>
                                        Active
                                    </option>

                                    <option value="0" @selected($dynamicPage->status == '0')>
                                        Inactive
                                    </option>
                                </select>
                            </div>

                            <button class="btn btn-primary mt-3">
                                Update Page
                            </button>

                        </form>
                    </div>
                </div>
            </div>
        </div>



    </div>
@endsection
