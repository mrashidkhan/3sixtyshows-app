@extends('admin.layout.layout')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Add New Video Gallery</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br>

                {{-- FIX 4: show validation errors so the user knows why the form failed --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('videogallery.store') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="form-horizontal form-label-left">
                    @csrf

                    {{-- Gallery Title --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="title">
                            Gallery Title <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <input type="text"
                                   id="title"
                                   name="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <br>

                    {{-- Show --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="show_id">
                            Show
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <select name="show_id" id="show_id"
                                    class="form-control @error('show_id') is-invalid @enderror">
                                <option value="">— Select Show —</option>
                                @foreach($shows as $show)
                                    <option value="{{ $show->id }}"
                                        {{ old('show_id') == $show->id ? 'selected' : '' }}>
                                        {{ $show->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('show_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <br>

                    {{-- Video Type --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="video_type">
                            Video Type <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <select name="video_type" id="video_type"
                                    class="form-control @error('video_type') is-invalid @enderror"
                                    required>
                                <option value="">— Select Type —</option>
                                <option value="youtube"  {{ old('video_type') == 'youtube'  ? 'selected' : '' }}>YouTube</option>
                                <option value="vimeo"    {{ old('video_type') == 'vimeo'    ? 'selected' : '' }}>Vimeo</option>
                                <option value="other"    {{ old('video_type') == 'other'    ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('video_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <br>

                    {{-- Video URL --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="video_url">
                            Video URL <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <input type="url"
                                   id="video_url"
                                   name="video_url"
                                   class="form-control @error('video_url') is-invalid @enderror"
                                   value="{{ old('video_url') }}"
                                   placeholder="https://www.youtube.com/watch?v=..."
                                   required>
                            <small class="form-text text-muted">Full YouTube or Vimeo URL.</small>
                            @error('video_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <br>

                    {{-- Thumbnail --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="thumbnail">
                            Thumbnail Image
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <input type="file"
                                   id="thumbnail"
                                   name="thumbnail"
                                   class="form-control @error('thumbnail') is-invalid @enderror"
                                   accept="image/*">
                            <small class="form-text text-muted">
                                Optional. JPEG / PNG / GIF, max 2 MB.
                                If not provided, the YouTube thumbnail will be used on the public gallery.
                            </small>
                            @error('thumbnail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <br>

                    {{-- Description --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="description">
                            Description
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <textarea id="description" name="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <br>

                    {{-- Display Order --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="display_order">
                            Display Order
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <input type="number"
                                   id="display_order"
                                   name="display_order"
                                   class="form-control"
                                   value="{{ old('display_order', 0) }}">
                        </div>
                    </div>
                    <br>

                    {{-- Featured --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">
                            Featured
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"
                                           name="is_featured"
                                           value="1"
                                           class="flat"
                                           {{ old('is_featured') ? 'checked' : '' }}>
                                    Mark as featured
                                </label>
                            </div>
                        </div>
                    </div>
                    <br>

                    {{-- Status --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="is_active">
                            Status
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <select id="is_active" name="is_active" class="form-control">
                                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="ln_solid"></div>

                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
                            <a href="{{ route('videogallery.list') }}" class="btn btn-secondary">Cancel</a>
                            <button type="reset"  class="btn btn-default">Reset</button>
                            <button type="submit" class="btn btn-success">Create Gallery</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
