{{-- resources/views/admin/forms/create.blade.php --}}
@extends('admin.layouts.main')

@section('title', 'Add Form | Yuukke Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Create Dynamic Form</div>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <h5 class="alert alert-success">{{ session('success') }}</h5>
                @endif

                <form action="{{ route('forms.store') }}" method="POST" onsubmit="serializeFields()">
                    @csrf

                    <div class="form-group">
                        <label for="title">Form Title</label>
                        <input name="title" type="text" class="form-control" id="title"
                            placeholder="e.g. Contact Us" value="{{ old('title') }}" required />
                        @error('title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input name="slug" type="text" class="form-control" id="slug"
                            placeholder="contact-us" value="{{ old('slug') }}" required />
                        @error('slug')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Fields</label>
                        <div id="field-builder" class="mb-2"></div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addField()">
                            + Add field
                        </button>
                        @error('fields')
                            <span class="text-danger d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <input type="hidden" name="fields" id="fields-json" />

                    <div class="card-action mt-3">
                        <button type="submit" class="btn btn-success">Save Form</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let fields = [];

function addField() {
    fields.push({ name: '', type: 'text', label: '', required: false, options: [] });
    renderFields();
}

function removeField(i) {
    fields.splice(i, 1);
    renderFields();
}

function renderFields() {
    const container = document.getElementById('field-builder');

    container.innerHTML = fields.map((f, i) => `
        <div class="row align-items-center mb-2 border p-2 rounded" data-index="${i}">
            <div class="col-md-3">
                <input class="form-control field-name" placeholder="Field name (e.g. email)" value="${f.name}" />
            </div>
            <div class="col-md-3">
                <input class="form-control field-label" placeholder="Label" value="${f.label}" />
            </div>
            <div class="col-md-2">
                <select class="form-control field-type">
                    <option value="text"     ${f.type === 'text' ? 'selected' : ''}>Text</option>
                    <option value="email"    ${f.type === 'email' ? 'selected' : ''}>Email</option>
                    <option value="mobile"   ${f.type === 'mobile' ? 'selected' : ''}>Mobile</option>
                    <option value="textarea" ${f.type === 'textarea' ? 'selected' : ''}>Textarea</option>
                    <option value="select"   ${f.type === 'select' ? 'selected' : ''}>Select</option>
                    <option value="checkbox" ${f.type === 'checkbox' ? 'selected' : ''}>Checkbox</option>
                </select>
            </div>
            <div class="col-md-2 form-check pt-2">
                <input type="checkbox" class="form-check-input field-required" ${f.required ? 'checked' : ''} />
                <label class="form-check-label">Required</label>
            </div>
            <div class="col-md-2 text-right">
                <button type="button" class="btn btn-sm btn-danger remove-field">Remove</button>
            </div>

            <div class="col-md-12 mt-2 options-wrapper" style="${['select','checkbox'].includes(f.type) ? '' : 'display:none'}">
                <label class="small mb-1">
                    Options (comma separated)
                    ${f.type === 'checkbox' ? '— leave blank for a single yes/no checkbox' : ''}
                </label>
                <input class="form-control field-options"
                    placeholder="e.g. Red, Blue, Green"
                    value="${(f.options || []).join(', ')}" />
            </div>
        </div>
    `).join('');

    attachFieldListeners();
}

function attachFieldListeners() {
    const container = document.getElementById('field-builder');

    container.querySelectorAll('[data-index]').forEach(row => {
        const i = Number(row.dataset.index);

        row.querySelector('.field-name').addEventListener('input', e => {
            fields[i].name = e.target.value;
        });

        row.querySelector('.field-label').addEventListener('input', e => {
            fields[i].label = e.target.value;
        });

        row.querySelector('.field-type').addEventListener('change', e => {
            fields[i].type = e.target.value;
            renderFields();
        });

        row.querySelector('.field-required').addEventListener('change', e => {
            fields[i].required = e.target.checked;
        });

        row.querySelector('.field-options').addEventListener('input', e => {
            fields[i].options = e.target.value
                .split(',')
                .map(opt => opt.trim())
                .filter(opt => opt.length > 0);
        });

        row.querySelector('.remove-field').addEventListener('click', () => {
            removeField(i);
        });
    });
}

function serializeFields() {
    document.getElementById('fields-json').value = JSON.stringify(fields);
}
</script>
@endsection