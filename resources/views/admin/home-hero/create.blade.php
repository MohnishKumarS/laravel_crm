{{-- resources/views/admin/home-hero/create.blade.php --}}
@extends('admin.layouts.main')

@section('title', 'Add Homepage Hero | Yuukke Dashboard')

@section('content')
    {{-- Breadcrumb --}}
    <div class="page-header">
        <h3 class="fw-bold mb-3">Hero Section</h3>
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
                <a href="{{ route('admin.home-hero.index') }}">Hero</a>
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
                    <div class="card-title">Create Homepage Hero</div>
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

                    <form action="{{ route('admin.home-hero.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                    value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active (shows on homepage — turns off
                                    any other active version)</label>
                            </div>
                        </div>

                        <hr>
                        <h6 class="text-muted">Copy</h6>
                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <label for="heading">Heading</label>
                                <input name="heading" type="text" class="form-control" id="heading"
                                    placeholder="The world celebrates brands. We celebrate" value="{{ old('heading') }}"
                                    required>
                                @error('heading')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="heading_highlight">Heading highlight (gradient word)</label>
                                <input name="heading_highlight" type="text" class="form-control" id="heading_highlight"
                                    placeholder="builders." value="{{ old('heading_highlight') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subtext">Subtext</label>
                            <textarea name="subtext" id="subtext" class="form-control" rows="3"
                                placeholder="Shop products, book services, and choose meaningful gifts created by women entrepreneurs.">{{ old('subtext') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="badge_text">Floating badge text</label>
                            <input name="badge_text" type="text" class="form-control" id="badge_text"
                                placeholder="TALENT INTO INDEPENDENCE" value="{{ old('badge_text') }}">
                        </div>

                        <hr>
                        <h6 class="text-muted">Images</h6>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="image_main">Main image (large)</label>
                                <input type="file" name="image_main" class="form-control-file" id="image_main"
                                    accept="image/*">
                                @error('image_main')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="image_secondary_1">Secondary image 1 (top-right)</label>
                                <input type="file" name="image_secondary_1" class="form-control-file"
                                    id="image_secondary_1" accept="image/*">
                                @error('image_secondary_1')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="image_secondary_2">Secondary image 2 (bottom-right)</label>
                                <input type="file" name="image_secondary_2" class="form-control-file"
                                    id="image_secondary_2" accept="image/*">
                                @error('image_secondary_2')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <hr>
                        <h6 class="text-muted">Buttons</h6>
                        <div id="buttons-repeater">
                            <div class="form-row repeater-row align-items-end mb-2">
                                <div class="form-group col-md-4 mb-0">
                                    <label>Button text</label>
                                    <input type="text" name="button_text[]" class="form-control"
                                        placeholder="Explore Marketplace →">
                                </div>
                                <div class="form-group col-md-4 mb-0">
                                    <label>Link URL</label>
                                    <input type="text" name="button_url[]" class="form-control"
                                        placeholder="/marketplace">
                                </div>
                                <div class="form-group col-md-3 mb-0">
                                    <label>Style</label>
                                    <select name="button_style[]" class="form-control">
                                        <option value="primary">Primary (filled)</option>
                                        <option value="outline">Outline</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-1 mb-0">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-row">✕</button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mb-4" id="add-button-row">+ Add
                            Button</button>

                        <hr>
                        <h6 class="text-muted">Feature List (e.g. Shop / Connect / Grow)</h6>
                        <div id="features-repeater">
                            <div class="form-row repeater-row align-items-end mb-2">
                                <div class="form-group col-md-3 mb-0">
                                    <label>Title</label>
                                    <input type="text" name="feature_title[]" class="form-control"
                                        placeholder="Shop">
                                </div>
                                <div class="form-group col-md-8 mb-0">
                                    <label>Description</label>
                                    <input type="text" name="feature_description[]" class="form-control"
                                        placeholder="Products, gifts & services">
                                </div>
                                <div class="form-group col-md-1 mb-0">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-row">✕</button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mb-4" id="add-feature-row">+ Add
                            Feature</button>

                        <div class="card-action mt-3">
                            <button type="submit" class="btn btn-success">Save Hero Section</button>
                            <a href="{{ route('admin.home-hero.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-button-row').addEventListener('click', function() {
            var wrap = document.getElementById('buttons-repeater');
            var row = wrap.querySelector('.repeater-row').cloneNode(true);
            row.querySelectorAll('input').forEach(function(i) {
                i.value = '';
            });
            wrap.appendChild(row);
        });
        document.getElementById('add-feature-row').addEventListener('click', function() {
            var wrap = document.getElementById('features-repeater');
            var row = wrap.querySelector('.repeater-row').cloneNode(true);
            row.querySelectorAll('input').forEach(function(i) {
                i.value = '';
            });
            wrap.appendChild(row);
        });
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row')) {
                var wrap = e.target.closest('#buttons-repeater, #features-repeater');
                if (wrap.querySelectorAll('.repeater-row').length > 1) {
                    e.target.closest('.repeater-row').remove();
                }
            }
        });
    </script>
@endsection
