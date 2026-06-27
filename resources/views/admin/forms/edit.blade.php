{{-- resources/views/admin/forms/edit.blade.php --}}
@extends('admin.layouts.main')

@section('title', 'Edit Form | Yuukke Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Edit Form: {{ $form->title }}</div>
            </div>
            <div class="card-body">
                <form action="{{ route('forms.update', $form->id) }}" method="POST" onsubmit="serializeFields()">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="title">Form Title</label>
                        <input name="title" type="text" class="form-control" id="title"
                            value="{{ old('title', $form->title) }}" required />
                        @error('title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input name="slug" type="text" class="form-control" id="slug"
                            value="{{ old('slug', $form->slug) }}" required />
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

                    <hr class="mt-4 mb-3">
                    <h5>Email Settings</h5>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="send_email" name="send_email"
                                value="1" {{ old('send_email', $form->send_email) ? 'checked' : '' }}
                                onchange="document.getElementById('email-settings-block').style.display = this.checked ? 'block' : 'none'" />
                            <label class="form-check-label" for="send_email">Send email after submission</label>
                        </div>
                    </div>

                    <div id="email-settings-block" style="{{ old('send_email', $form->send_email) ? '' : 'display:none' }}">

                        <div class="form-group">
                            <label>Which field holds the customer's email address?</label>
                            <input type="text" name="email_field_name" class="form-control"
                                placeholder="e.g. email" value="{{ old('email_field_name', $form->email_field_name) }}" />
                            <small class="text-muted">Must match a field's machine name exactly (check the Fields list above).</small>
                        </div>

                        <hr>
                        <h6>Customer Confirmation Email</h6>

                        <div class="form-group">
                            <label>Subject</label>
                            <input type="text" name="customer_subject" class="form-control"
                                placeholder="Thank you for your submission"
                                value="{{ old('customer_subject', $form->customer_subject) }}" />
                        </div>

                        <div class="form-group">
                         <label>Customer Email — Body (Raw HTML)</label>
                         <div class="row">
                             <div class="col-md-6">
                                 <p class="small text-muted mb-1">HTML Source</p>
                                 <textarea name="customer_template" id="customer_template" class="form-control"
                                     rows="20" style="font-family: monospace; font-size: 12px; resize: vertical;"
                                     oninput="updatePreview('customer_template', 'customer_preview')">{{ old('customer_template', $form->customer_template ?? '') }}</textarea>
                                 <small class="text-muted">
                                     Use the field's machine name wrapped in double curly braces to insert a submitted value.
                                 </small>
                             </div>
                             <div class="col-md-6">
                                 <p class="small text-muted mb-1">Live Preview</p>
                                 <iframe id="customer_preview"
                                     style="width:100%; height:480px; border:1px solid #ddd; border-radius:6px; background:#fff;"></iframe>
                             </div>
                         </div>
                     </div>

                        <hr>
                        <h6>Admin Notification Email</h6>

                        <div class="form-group">
                            <label>Send admin notification to</label>
                            <input type="email" name="admin_email" class="form-control"
                                placeholder="admin@yoursite.com" value="{{ old('admin_email', $form->admin_email) }}" />
                        </div>

                        <div class="form-group">
                            <label>Subject</label>
                            <input type="text" name="admin_subject" class="form-control"
                                placeholder="New submission received" value="{{ old('admin_subject', $form->admin_subject) }}" />
                        </div>

                        <div class="form-group mt-4">
                          <label>Admin Email — Body (Raw HTML, leave blank to auto-generate)</label>
                          <div class="row">
                              <div class="col-md-6">
                                  <p class="small text-muted mb-1">HTML Source</p>
                                  <textarea name="admin_template" id="admin_template" class="form-control"
                                      rows="20" style="font-family: monospace; font-size: 12px; resize: vertical;"
                                      oninput="updatePreview('admin_template', 'admin_preview')">{{ old('admin_template', $form->admin_template ?? '') }}</textarea>
                              </div>
                              <div class="col-md-6">
                                  <p class="small text-muted mb-1">Live Preview</p>
                                  <iframe id="admin_preview"
                                      style="width:100%; height:480px; border:1px solid #ddd; border-radius:6px; background:#fff;"></iframe>
                              </div>
                          </div>
                      </div>

                    </div>

                    <div class="card-action mt-3">
                        <button type="submit" class="btn btn-success">Update Form</button>
                        <a href="{{ route('forms.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script> --}}
<script>
// Seed with existing fields from the DB
let fields = @json($form->fields ?? []);

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

// Render immediately on page load with seeded data
renderFields();

function serializeFields() {
    document.getElementById('fields-json').value = JSON.stringify(fields);
}

function updatePreview(textareaId, iframeId) {
    const html = document.getElementById(textareaId).value;
    const iframe = document.getElementById(iframeId);
    const doc = iframe.contentDocument || iframe.contentWindow.document;
    doc.open();
    doc.write(html);
    doc.close();
}

document.addEventListener('DOMContentLoaded', function () {
    updatePreview('customer_template', 'customer_preview');
    updatePreview('admin_template', 'admin_preview');
});
</script>
@endsection

@push('scripts')
{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
    ClassicEditor.create(document.querySelector('#customer_template'),).catch(console.error);
    ClassicEditor.create(document.querySelector('#admin_template')).catch(console.error);
});
</script> --}}
@endpush

