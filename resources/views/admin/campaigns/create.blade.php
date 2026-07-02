{{-- resources/views/admin/campaigns/create.blade.php --}}
@extends('admin.layouts.main')

@section('title', 'Add Campaign | Yuukke Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Create Campaign</div>
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

                <form action="{{ route('admin.campaigns.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Campaign Name</label>
                            <input name="name" type="text" class="form-control" id="name"
                                placeholder="e.g. Diwali Sale 2026" value="{{ old('name') }}" required />
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="slug">Slug (optional — auto-generated if left blank)</label>
                            <input name="slug" type="text" class="form-control" id="slug"
                                placeholder="diwali-2026" value="{{ old('slug') }}" />
                            @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-muted">Scheduling</h6>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="start_at">Starts</label>
                            <input name="start_at" type="datetime-local" class="form-control" id="start_at"
                                value="{{ old('start_at') }}" />
                            <small class="form-text text-muted">Leave blank to allow it to start immediately.</small>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="end_at">Ends</label>
                            <input name="end_at" type="datetime-local" class="form-control" id="end_at"
                                value="{{ old('end_at') }}" />
                            <small class="form-text text-muted">Leave blank for it to never expire.</small>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="priority">Priority</label>
                            <input name="priority" type="number" min="0" class="form-control" id="priority" value="{{ old('priority', 0) }}" />
                            <small class="form-text text-muted">If two campaigns' windows overlap, highest priority wins.</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_published" name="is_published" value="1"
                                    {{ old('is_published', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_published">Published (visible to site visitors when in window)</label>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_default" name="is_default" value="1"
                                    {{ old('is_default') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_default">Use as evergreen default (shown when no other campaign is active)</label>
                            </div>
                        </div>
                    </div>

                    {{-- ============================================================
                         THEME COLORS — swatch preview block + native picker + hex
                         text box, all three kept in sync via the inline JS below.
                    ============================================================ --}}
                    <hr>
                    <h6 class="text-muted">Theme Colors</h6>
                    <div class="form-row">
                        @php
                            $colorFields = [
                                'theme_bg_start'     => ['Overlay gradient — start', '#2b2326'],
                                'theme_bg_end'        => ['Overlay gradient — end', '#2b2326'],
                                'accent_color'        => ['Accent (buttons / badge)', '#e6a23c'],
                                'accent_text_color'   => ['Text on accent', '#2b2326'],
                                'eyebrow_color'       => ['Eyebrow text color', '#e6a23c'],
                            ];
                        @endphp
                        @foreach ($colorFields as $field => [$label, $default])
                            @php $current = old($field, $default); @endphp
                            <div class="form-group col-md-2">
                                <label for="{{ $field }}">{{ $label }}</label>
                                <div class="d-flex align-items-center mb-1">
                                    <div id="{{ $field }}_preview"
                                         style="width:36px;height:36px;border-radius:6px;border:1px solid #ccc;background-color:{{ $current }};flex:none;"></div>
                                    <input
                                        type="color"
                                        id="{{ $field }}"
                                        name="{{ $field }}"
                                        class="form-control color-swatch ml-2"
                                        value="{{ $current }}"
                                        style="width:44px;height:36px;padding:2px;flex:none;"
                                        oninput="document.getElementById('{{ $field }}_hex').value = this.value.toUpperCase(); document.getElementById('{{ $field }}_preview').style.backgroundColor = this.value;"
                                    >
                                </div>
                                <input
                                    type="text"
                                    id="{{ $field }}_hex"
                                    class="form-control text-center"
                                    value="{{ strtoupper($current) }}"
                                    oninput="document.getElementById('{{ $field }}').value = this.value; document.getElementById('{{ $field }}_preview').style.backgroundColor = this.value;"
                                    style="font-family:monospace;"
                                >
                                @error($field) <span class="text-danger d-block">{{ $message }}</span> @enderror
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group">
                        <label for="background_image">Hero background image</label>
                        <input type="file" name="background_image" class="form-control-file" id="background_image" accept="image/*" />
                        @error('background_image') <span class="text-danger d-block">{{ $message }}</span> @enderror
                    </div>

                    <hr>
                    <h6 class="text-muted">Hero Copy</h6>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="eyebrow">Eyebrow text</label>
                            <input name="eyebrow" type="text" class="form-control" id="eyebrow" placeholder="Festival of Lights · Limited Time" value="{{ old('eyebrow') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="badge_text">Badge text (optional pill)</label>
                            <input name="badge_text" type="text" class="form-control" id="badge_text" placeholder="DIWALI SALE · UP TO 40% OFF" value="{{ old('badge_text') }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="heading">Heading</label>
                            <input name="heading" type="text" class="form-control" id="heading" placeholder="Gift handmade." value="{{ old('heading') }}" required>
                            @error('heading') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="heading_highlight">Heading highlight (italicised)</label>
                            <input name="heading_highlight" type="text" class="form-control" id="heading_highlight" placeholder="Gift with meaning." value="{{ old('heading_highlight') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subtext">Subtext</label>
                        <textarea name="subtext" id="subtext" class="form-control" rows="3">{{ old('subtext') }}</textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="cta1_text">Primary button text</label>
                            <input name="cta1_text" type="text" class="form-control" id="cta1_text" value="{{ old('cta1_text') }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="cta1_url">Primary button link</label>
                            <input name="cta1_url" type="text" class="form-control" id="cta1_url" value="{{ old('cta1_url') }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="cta2_text">Secondary button text</label>
                            <input name="cta2_text" type="text" class="form-control" id="cta2_text" value="{{ old('cta2_text') }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="cta2_url">Secondary button link</label>
                            <input name="cta2_url" type="text" class="form-control" id="cta2_url" value="{{ old('cta2_url') }}">
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-muted">Announcement Bar</h6>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="announcement_text">Message (leave blank to hide the bar)</label>
                            <input name="announcement_text" type="text" class="form-control" id="announcement_text" placeholder="🪔 Diwali Sale is live — up to 40% off" value="{{ old('announcement_text') }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="announcement_link_text">Link text</label>
                            <input name="announcement_link_text" type="text" class="form-control" id="announcement_link_text" value="{{ old('announcement_link_text') }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="announcement_link_url">Link URL</label>
                            <input name="announcement_link_url" type="text" class="form-control" id="announcement_link_url" value="{{ old('announcement_link_url') }}">
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-muted">Countdown</h6>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="show_countdown" name="show_countdown" value="1" {{ old('show_countdown') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="show_countdown">Show "Ends in" countdown</label>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="countdown_end_at">Countdown target</label>
                            <input name="countdown_end_at" type="datetime-local" class="form-control" id="countdown_end_at" value="{{ old('countdown_end_at') }}">
                            <small class="form-text text-muted">Usually the same as the "Ends" date above.</small>
                        </div>
                    </div>

                    <div class="card-action mt-3">
                        <button type="submit" class="btn btn-success">Save Campaign</button>
                        <a href="{{ route('admin.campaigns.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
