{{-- resources/views/admin/campaigns/_form.blade.php --}}
@php
    $c = $campaign ?? null;
    $val = fn($field, $default = '') => old($field, $c?->$field ?? $default);
@endphp

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="name">Campaign Name</label>
        <input name="name" type="text" class="form-control" id="name"
            placeholder="e.g. Diwali Sale 2026" value="{{ $val('name') }}" required />
        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="slug">Slug (optional — auto-generated if left blank)</label>
        <input name="slug" type="text" class="form-control" id="slug"
            placeholder="diwali-2026" value="{{ $val('slug') }}" />
        @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>

<hr>
<h6 class="text-muted">Scheduling</h6>
<div class="form-row">
    <div class="form-group col-md-4">
        <label for="start_at">Starts</label>
        <input name="start_at" type="datetime-local" class="form-control" id="start_at"
            value="{{ $val('start_at') ? \Illuminate\Support\Carbon::parse($val('start_at'))->format('Y-m-d\TH:i') : '' }}" />
        <small class="form-text text-muted">Leave blank to allow it to start immediately.</small>
    </div>
    <div class="form-group col-md-4">
        <label for="end_at">Ends</label>
        <input name="end_at" type="datetime-local" class="form-control" id="end_at"
            value="{{ $val('end_at') ? \Illuminate\Support\Carbon::parse($val('end_at'))->format('Y-m-d\TH:i') : '' }}" />
        <small class="form-text text-muted">Leave blank for it to never expire.</small>
    </div>
    <div class="form-group col-md-4">
        <label for="priority">Priority</label>
        <input name="priority" type="number" min="0" class="form-control" id="priority" value="{{ $val('priority', 0) }}" />
        <small class="form-text text-muted">If two campaigns' windows overlap, highest priority wins.</small>
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-6">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="is_published" name="is_published" value="1" {{ $val('is_published', true) ? 'checked' : '' }}>
            <label class="custom-control-label" for="is_published">Published (visible to site visitors when in window)</label>
        </div>
    </div>
    <div class="form-group col-md-6">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="is_default" name="is_default" value="1" {{ $val('is_default') ? 'checked' : '' }}>
            <label class="custom-control-label" for="is_default">Use as evergreen default (shown when no other campaign is active)</label>
        </div>
    </div>
</div>

<hr>
<h6 class="text-muted">Theme</h6>
<div class="form-row">
    <div class="form-group col-md-3">
        <label for="theme_bg_start">Overlay gradient — start</label>
        <input name="theme_bg_start" type="color" class="form-control" id="theme_bg_start" value="{{ $val('theme_bg_start', '#2b2326') }}">
    </div>
    <div class="form-group col-md-3">
        <label for="theme_bg_end">Overlay gradient — end</label>
        <input name="theme_bg_end" type="color" class="form-control" id="theme_bg_end" value="{{ $val('theme_bg_end', '#2b2326') }}">
    </div>
    <div class="form-group col-md-3">
        <label for="accent_color">Accent (buttons / badge)</label>
        <input name="accent_color" type="color" class="form-control" id="accent_color" value="{{ $val('accent_color', '#e6a23c') }}">
    </div>
    <div class="form-group col-md-3">
        <label for="accent_text_color">Text on accent</label>
        <input name="accent_text_color" type="color" class="form-control" id="accent_text_color" value="{{ $val('accent_text_color', '#2b2326') }}">
    </div>
    <div class="form-group col-md-3">
        <label for="eyebrow_color">Eyebrow text color</label>
        <input name="eyebrow_color" type="color" class="form-control" id="eyebrow_color" value="{{ $val('eyebrow_color', '#e6a23c') }}">
    </div>
</div>
<div class="form-group">
    <label for="background_image">Hero background image</label>
    @if($c?->background_image)
        <div class="mb-2"><img src="{{ $c->background_image_url }}" style="max-height:100px;border-radius:6px;"></div>
    @endif
    <input type="file" name="background_image" class="form-control-file" id="background_image" accept="image/*" />
    @error('background_image') <span class="text-danger d-block">{{ $message }}</span> @enderror
</div>

<hr>
<h6 class="text-muted">Hero Copy</h6>
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="eyebrow">Eyebrow text</label>
        <input name="eyebrow" type="text" class="form-control" id="eyebrow" placeholder="Festival of Lights · Limited Time" value="{{ $val('eyebrow') }}">
    </div>
    <div class="form-group col-md-6">
        <label for="badge_text">Badge text (optional pill)</label>
        <input name="badge_text" type="text" class="form-control" id="badge_text" placeholder="DIWALI SALE · UP TO 40% OFF" value="{{ $val('badge_text') }}">
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-8">
        <label for="heading">Heading</label>
        <input name="heading" type="text" class="form-control" id="heading" placeholder="Gift handmade." value="{{ $val('heading') }}" required>
        @error('heading') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="heading_highlight">Heading highlight (italicised)</label>
        <input name="heading_highlight" type="text" class="form-control" id="heading_highlight" placeholder="Gift with meaning." value="{{ $val('heading_highlight') }}">
    </div>
</div>
<div class="form-group">
    <label for="subtext">Subtext</label>
    <textarea name="subtext" id="subtext" class="form-control" rows="3">{{ $val('subtext') }}</textarea>
</div>
<div class="form-row">
    <div class="form-group col-md-3">
        <label for="cta1_text">Primary button text</label>
        <input name="cta1_text" type="text" class="form-control" id="cta1_text" value="{{ $val('cta1_text') }}">
    </div>
    <div class="form-group col-md-3">
        <label for="cta1_url">Primary button link</label>
        <input name="cta1_url" type="text" class="form-control" id="cta1_url" value="{{ $val('cta1_url') }}">
    </div>
    <div class="form-group col-md-3">
        <label for="cta2_text">Secondary button text</label>
        <input name="cta2_text" type="text" class="form-control" id="cta2_text" value="{{ $val('cta2_text') }}">
    </div>
    <div class="form-group col-md-3">
        <label for="cta2_url">Secondary button link</label>
        <input name="cta2_url" type="text" class="form-control" id="cta2_url" value="{{ $val('cta2_url') }}">
    </div>
</div>

<hr>
<h6 class="text-muted">Announcement Bar</h6>
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="announcement_text">Message (leave blank to hide the bar)</label>
        <input name="announcement_text" type="text" class="form-control" id="announcement_text" placeholder="🪔 Diwali Sale is live — up to 40% off" value="{{ $val('announcement_text') }}">
    </div>
    <div class="form-group col-md-3">
        <label for="announcement_link_text">Link text</label>
        <input name="announcement_link_text" type="text" class="form-control" id="announcement_link_text" value="{{ $val('announcement_link_text') }}">
    </div>
    <div class="form-group col-md-3">
        <label for="announcement_link_url">Link URL</label>
        <input name="announcement_link_url" type="text" class="form-control" id="announcement_link_url" value="{{ $val('announcement_link_url') }}">
    </div>
</div>

<hr>
<h6 class="text-muted">Countdown</h6>
<div class="form-row">
    <div class="form-group col-md-6">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="show_countdown" name="show_countdown" value="1" {{ $val('show_countdown') ? 'checked' : '' }}>
            <label class="custom-control-label" for="show_countdown">Show "Ends in" countdown</label>
        </div>
    </div>
    <div class="form-group col-md-6">
        <label for="countdown_end_at">Countdown target</label>
        <input name="countdown_end_at" type="datetime-local" class="form-control" id="countdown_end_at"
            value="{{ $val('countdown_end_at') ? \Illuminate\Support\Carbon::parse($val('countdown_end_at'))->format('Y-m-d\TH:i') : '' }}">
        <small class="form-text text-muted">Usually the same as the "Ends" date above.</small>
    </div>
</div>
