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
                    <a href="#">Create</a>
                </li>
            </ul>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Create Page</div>
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

                        <form action="{{ route('shop.dynamic-pages.store') }}" method="POST" enctype="multipart/form-data">

                            @csrf

                            <div class="form-group">
                                <label>Title</label>

                                <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                                    required>
                            </div>


                            <div class="form-group">
                                <label>Subtitle</label>

                                <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle') }}">
                            </div>


                            <div class="form-group">
                                <label>Heading</label>

                                <input type="text" name="heading" class="form-control" value="{{ old('heading') }}">
                            </div>


                            <div class="form-group">
                                <label>Description</label>

                                <textarea name="description" rows="6" class="form-control">{{ old('description') }}</textarea>
                            </div>


                            <div class="form-group">
                                <label>Page URL</label>

                                <input type="text" name="page_url" class="form-control"
                                    placeholder="products/fathers-days-special" value="{{ old('page_url') }}" required>

                                <small class="text-muted">
                                    Example: https://shop.yuukke.com/products/fathers-days-special
                                </small>
                            </div>


                            <div class="form-group">
                                <label>Products Key</label>

                                <input type="text" name="products_id" class="form-control" placeholder="title13"
                                    value="{{ old('products_id') }}">

                                <small class="text-muted">
                                    Example: title13. This is a product collection key,
                                    not a product ID.
                                </small>
                            </div>


                            <div class="form-group">
                                <label>Banner Image</label>

                                <input type="file" name="banner_image" class="form-control">
                            </div>


                            <hr>

                            <h5>SEO Details</h5>

                            <div class="form-group">
                                <label>Meta Title</label>

                                <input type="text" name="meta_title" class="form-control">
                            </div>


                            <div class="form-group">
                                <label>Meta Description</label>

                                <textarea name="meta_description" class="form-control"></textarea>
                            </div>


                            <div class="form-group">
                                <label>Meta Keywords</label>

                                <textarea name="meta_keywords" class="form-control"></textarea>
                            </div>


                            <div class="form-group">
                                <label>Status</label>

                                <select name="status" class="form-select">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>


                            <div class="form-group">
                                <label>Sort Order</label>

                                <input type="number" name="sort_order" class="form-control" value="0">
                            </div>


                            <button type="submit" class="btn btn-primary mt-4">
                                Create Page
                            </button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
