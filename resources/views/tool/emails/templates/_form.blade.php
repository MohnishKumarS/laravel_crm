@csrf

@if (isset($template))
    @method('PUT')
@endif


<div class="row">

    <div class="col-lg-8">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                {{-- Name --}}
                <div class="mb-3">

                    <label class="form-label">
                        Template Name
                        <span class="text-danger">*</span>
                    </label>

                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $template->name ?? '') }}">

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Subject --}}
                <div class="mb-3">

                    <label class="form-label">
                        Email Subject
                        <span class="text-danger">*</span>
                    </label>

                    <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                        value="{{ old('subject', $template->subject ?? '') }}">

                    @error('subject')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Body --}}
                <div class="mb-3">
                    <label class="form-label">Email — Body (Raw HTML) <span class="text-danger"> *
                        </span></label>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="small text-muted mb-1">HTML Source</p>
                            <textarea name="body" id="emailBody" class="form-control" rows="25"
                                style="font-family: monospace; font-size: 12px; resize: vertical;"
                                oninput="updatePreview('emailBody', 'customer_preview')">{{ old('body',$template->body) }}</textarea>
                            <small class="text-muted">
                                Use the field's machine name wrapped in double curly braces to insert a
                                submitted value.
                            </small>
                        </div>
                        <div class="col-md-6">
                            <p class="small text-muted mb-1">Live Preview</p>
                            <iframe id="customer_preview"
                                style="width:100%; height:480px; border:1px solid #ddd; border-radius:6px; background:#fff;"></iframe>
                        </div>
                    </div>

                    @error('body')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>

    </div>


    <div class="col-lg-4">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                {{-- Category --}}
                <div class="mb-3">

                    <label class="form-label">
                        Category
                    </label>

                    <input type="text" name="category" class="form-control"
                        value="{{ old('category', $template->category ?? '') }}">

                </div>


                {{-- Description --}}
                <div class="mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea name="description" rows="4" class="form-control">{{ old('description', $template->description ?? '') }}</textarea>

                </div>


                {{-- Status --}}
                <div class="mb-3">

                    <label class="form-label">
                        Status
                    </label>

                    <select name="status" class="form-select">

                        <option value="active" @selected(old('status', $template->status ?? 'active') === 'active')>
                            Active
                        </option>

                        <option value="inactive" @selected(old('status', $template->status ?? '') === 'inactive')>
                            Inactive
                        </option>

                    </select>

                </div>


                <button type="submit" class="btn btn-primary w-100">

                    Update Template

                </button>

            </div>

        </div>

    </div>

</div>
