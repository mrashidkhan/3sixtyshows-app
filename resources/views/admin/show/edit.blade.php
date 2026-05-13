@extends('admin.layout.layout')

@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Edit Show <small>Update show details</small></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <br>
                <form id="show-form" action="{{ route('show.update', $show->id) }}" class="form-horizontal form-label-left" method="post" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')

                    <!-- ================================================ -->
                    <!-- Basic Information                                  -->
                    <!-- ================================================ -->
                    <div class="x_title"><h4>Basic Information</h4><div class="clearfix"></div></div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="title">Title <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <input type="text" id="title" name="title" required class="form-control" value="{{ old('title', $show->title) }}">
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="slug">Slug <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <input type="text" id="slug" name="slug" required class="form-control" value="{{ old('slug', $show->slug) }}">
                            <small class="form-text text-muted">Used in the URL.</small>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="category_id">Category <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <select id="category_id" name="category_id" required class="form-control">
                                <option value="" disabled>Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $show->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="venue_id">Venue <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <select id="venue_id" name="venue_id" required class="form-control">
                                <option value="" disabled>Select a venue</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}" {{ old('venue_id', $show->venue_id) == $venue->id ? 'selected' : '' }}>
                                        {{ $venue->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="short_description">Short Description <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <textarea id="short_description" name="short_description" required class="form-control" rows="2">{{ old('short_description', $show->short_description) }}</textarea>
                            <small class="form-text text-muted">Brief summary shown in listings (max 255 characters)</small>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="description">Full Description <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <textarea id="description" name="description" required class="form-control" rows="6">{{ old('description', $show->description) }}</textarea>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="featured_image">Featured Image</label>
                        <div class="col-md-6 col-sm-6">
                            @if($show->featured_image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $show->featured_image) }}" alt="{{ $show->title }}"
                                         style="max-width: 300px; max-height: 200px; border: 1px solid #ddd; padding: 3px;">
                                    <p class="text-muted mt-1">Current image</p>
                                </div>
                            @endif
                            <input type="file" id="featured_image" name="featured_image" class="form-control" accept="image/*">
                            <small class="form-text text-muted">Leave empty to keep the current image. Recommended: 1200×800 px. Max 2 MB.</small>
                        </div>
                    </div>

                    <!-- ================================================ -->
                    <!-- Date & Time                                        -->
                    <!-- ================================================ -->
                    <div class="x_title"><h4>Date & Time</h4><div class="clearfix"></div></div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="start_date">Start Date & Time <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <input type="datetime-local" id="start_date" name="start_date" required class="form-control"
                                   value="{{ old('start_date', $show->start_date ? $show->start_date->format('Y-m-d\TH:i') : '') }}">
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="end_date">End Date & Time <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <input type="datetime-local" id="end_date" name="end_date" required class="form-control"
                                   value="{{ old('end_date', $show->end_date ? $show->end_date->format('Y-m-d\TH:i') : '') }}">
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="duration">Duration</label>
                        <div class="col-md-6 col-sm-6">
                            <input type="text" id="duration" name="duration" class="form-control"
                                   value="{{ old('duration', $show->duration) }}">
                            <small class="form-text text-muted">e.g. "2 hours 30 minutes"</small>
                        </div>
                    </div>

                    <!-- ================================================ -->
                    <!-- Ticket Information                                 -->
                    <!-- ================================================ -->
                    <div class="x_title"><h4>Ticket Information</h4><div class="clearfix"></div></div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="price">Price <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <input type="number" id="price" name="price" required class="form-control" step="0.01" min="0"
                                   value="{{ old('price', $show->price) }}">
                            <small class="form-text text-muted">Set to 0 for free events</small>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="available_tickets">Available Tickets</label>
                        <div class="col-md-6 col-sm-6">
                            <input type="number" id="available_tickets" name="available_tickets" class="form-control" min="0"
                                   value="{{ old('available_tickets', $show->available_tickets) }}">
                            <small class="form-text text-muted">Leave empty for unlimited tickets</small>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Tickets Sold</label>
                        <div class="col-md-6 col-sm-6">
                            <p class="form-control-static">{{ $show->sold_tickets ?? 0 }}</p>
                        </div>
                    </div>

                    <!-- ================================================ -->
                    <!-- Additional Information                             -->
                    <!-- ================================================ -->
                    <div class="x_title"><h4>Additional Information</h4><div class="clearfix"></div></div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="performers">Performers/Artists</label>
                        <div class="col-md-6 col-sm-6">
                            <textarea id="performers" name="performers" class="form-control" rows="3">{{ old('performers', is_array($show->performers) ? implode("\n", $show->performers) : $show->performers) }}</textarea>
                            <small class="form-text text-muted">One performer per line</small>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="additional_info">Additional Information</label>
                        <div class="col-md-6 col-sm-6">
                            <textarea id="additional_info" name="additional_info" class="form-control" rows="4">{{ old('additional_info', is_array($show->additional_info) ? collect($show->additional_info)->map(fn($v,$k) => "$k: $v")->implode("\n") : $show->additional_info) }}</textarea>
                            <small class="form-text text-muted">Format: "Key: Value" (one per line)</small>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="age_restriction">Age Restriction</label>
                        <div class="col-md-6 col-sm-6">
                            <input type="text" id="age_restriction" name="age_restriction" class="form-control"
                                   value="{{ old('age_restriction', $show->age_restriction) }}">
                            <small class="form-text text-muted">e.g. "18+", "All ages"</small>
                        </div>
                    </div>

                    <!-- ================================================ -->
                    <!-- Settings                                           -->
                    <!-- ================================================ -->
                    <div class="x_title"><h4>Settings</h4><div class="clearfix"></div></div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="is_featured">Featured</label>
                        <div class="col-md-6 col-sm-6">
                            <select id="is_featured" name="is_featured" class="form-control">
                                <option value="1" {{ old('is_featured', $show->is_featured) == 1 ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('is_featured', $show->is_featured) == 0 ? 'selected' : '' }}>No</option>
                            </select>
                            <small class="form-text text-muted">Featured shows appear on the homepage</small>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="status">Status <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <select id="status" name="status" required class="form-control">
                                <option value="upcoming"  {{ old('status', $show->status) == 'upcoming'  ? 'selected' : '' }}>Upcoming</option>
                                <option value="ongoing"   {{ old('status', $show->status) == 'ongoing'   ? 'selected' : '' }}>Ongoing</option>
                                <option value="past"      {{ old('status', $show->status) == 'past'      ? 'selected' : '' }}>Past</option>
                                <option value="cancelled" {{ old('status', $show->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="is_active">Active <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <select id="is_active" name="is_active" required class="form-control">
                                <option value="1" {{ old('is_active', $show->is_active) == 1 ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('is_active', $show->is_active) == 0 ? 'selected' : '' }}>No</option>
                            </select>
                            <small class="form-text text-muted">Inactive shows won't be visible on the website</small>
                        </div>
                    </div>

                    <!-- ================================================ -->
                    <!-- Redirect Settings                                  -->
                    <!-- ================================================ -->
                    <div class="x_title"><h4>Redirect Settings</h4><div class="clearfix"></div></div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="redirect">Enable Redirect</label>
                        <div class="col-md-6 col-sm-6">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="redirect-checkbox" name="redirect" value="1"
                                           {{ old('redirect', $show->redirect) ? 'checked' : '' }}>
                                    Redirect users to external URL when clicking on this show
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="item form-group redirect-url-group"
                         style="{{ old('redirect', $show->redirect) ? '' : 'display: none;' }}">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="redirect_url">Redirect URL</label>
                        <div class="col-md-6 col-sm-6">
                            <input type="url" id="redirect_url" name="redirect_url" class="form-control"
                                   value="{{ old('redirect_url', $show->redirect_url) }}">
                            <small class="form-text text-muted">Full URL including https://</small>
                        </div>
                    </div>

                    <!-- ================================================ -->
                    <!-- seats.io Ticketing Settings (NEW)                 -->
                    <!-- ================================================ -->
                    <div class="x_title">
                        <h4>
                            <i class="fa fa-map-marker" style="color:#6f42c1;"></i>
                            seats.io Ticketing Settings
                        </h4>
                        <div class="clearfix"></div>
                    </div>
                    <div class="alert alert-info" style="margin: 0 15px 15px;">
                        <i class="fa fa-info-circle"></i>
                        Only fill these fields if you are using <strong>seats.io reserved seating</strong>.
                        For general admission shows leave <em>Ticketing Mode</em> as <strong>General Admission</strong>.
                    </div>

                    {{-- Ticketing Mode --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="ticketing_mode">
                            Ticketing Mode <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <select id="ticketing_mode" name="ticketing_mode" class="form-control" required>
                                <option value="general_admission" {{ old('ticketing_mode', $show->ticketing_mode) == 'general_admission' ? 'selected' : '' }}>
                                    General Admission (default)
                                </option>
                                <option value="reserved" {{ old('ticketing_mode', $show->ticketing_mode) == 'reserved' ? 'selected' : '' }}>
                                    Reserved Seating (seats.io)
                                </option>
                                <option value="mixed" {{ old('ticketing_mode', $show->ticketing_mode) == 'mixed' ? 'selected' : '' }}>
                                    Mixed — GA + Reserved (seats.io)
                                </option>
                                <option value="none" {{ old('ticketing_mode', $show->ticketing_mode) == 'none' ? 'selected' : '' }}>
                                    None / External (redirect only)
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- seats.io detail fields — shown/hidden by JS --}}
                    @php
                        $showSeatsio = in_array(old('ticketing_mode', $show->ticketing_mode), ['reserved', 'mixed']);
                    @endphp
                    <div id="seatsio-fields" style="{{ $showSeatsio ? '' : 'display: none;' }}">

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="seatsio_chart_key">Chart Key</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" id="seatsio_chart_key" name="seatsio_chart_key" class="form-control"
                                       value="{{ old('seatsio_chart_key', $show->seatsio_chart_key) }}"
                                       placeholder="e.g. 3fa6f...">
                                <small class="form-text text-muted">
                                    From seats.io Designer → Charts → chart URL.
                                </small>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="seatsio_event_key">Event Key</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" id="seatsio_event_key" name="seatsio_event_key" class="form-control"
                                       value="{{ old('seatsio_event_key', $show->seatsio_event_key) }}"
                                       placeholder="Generated by seats.io API">
                                <small class="form-text text-muted">
                                    Auto-generated when you publish the event via the seats.io API.
                                </small>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="seatsio_public_key">Public Key Override</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" id="seatsio_public_key" name="seatsio_public_key" class="form-control"
                                       value="{{ old('seatsio_public_key', $show->seatsio_public_key ?? '') }}"
                                       placeholder="Leave blank to use .env default">
                                <small class="form-text text-muted">
                                    Only set for a different seats.io workspace than the global <code>.env</code> setting.
                                </small>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Tickets On Sale</label>
                            <div class="col-md-6 col-sm-6">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="tickets_on_sale" name="tickets_on_sale" value="1"
                                               {{ old('tickets_on_sale', $show->tickets_on_sale) ? 'checked' : '' }}>
                                        Allow ticket purchases (seats.io widget active)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="sale_starts_at">Sale Starts At</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="datetime-local" id="sale_starts_at" name="sale_starts_at" class="form-control"
                                       value="{{ old('sale_starts_at', $show->sale_starts_at ? $show->sale_starts_at->format('Y-m-d\TH:i') : '') }}">
                                <small class="form-text text-muted">
                                    Optional. Leave blank to open sales immediately when "Tickets On Sale" is checked.
                                </small>
                            </div>
                        </div>

                        {{-- Read-only status indicator --}}
                        @if($show->seatsio_chart_key || $show->seatsio_event_key)
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">seats.io Status</label>
                            <div class="col-md-6 col-sm-6">
                                <p class="form-control-static">
                                    @if($show->seatsio_chart_key && $show->seatsio_event_key)
                                        <span class="badge badge-success"><i class="fa fa-check"></i> Chart &amp; Event configured</span>
                                    @elseif($show->seatsio_chart_key)
                                        <span class="badge badge-warning"><i class="fa fa-exclamation-triangle"></i> Chart key set — Event key missing</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fa fa-times"></i> Not configured</span>
                                    @endif
                                    @if($show->isSaleOpen())
                                        &nbsp;<span class="badge badge-success">On Sale Now</span>
                                    @elseif($show->tickets_on_sale && $show->sale_starts_at && $show->sale_starts_at->isFuture())
                                        &nbsp;<span class="badge badge-info">Sale opens {{ $show->sale_starts_at->format('M d, Y H:i') }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endif

                    </div>{{-- /seatsio-fields --}}

                    <div class="ln_solid"></div>

                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
                            <a href="{{ route('show.index') }}" class="btn btn-primary">Cancel</a>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
window.addEventListener('DOMContentLoaded', function () {

    // ---- Auto-generate slug ----
    var titleInput = document.getElementById('title');
    var slugInput  = document.getElementById('slug');
    if (titleInput && slugInput) {
        titleInput.addEventListener('keyup', function () {
            if (!slugInput.value || slugInput._autoGenerated) {
                slugInput.value = this.value
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/--+/g, '-')
                    .trim();
                slugInput._autoGenerated = true;
            }
        });
        slugInput.addEventListener('input', function () { this._autoGenerated = false; });
    }

    // ---- Redirect URL toggle ----
    var redirectCheckbox = document.getElementById('redirect-checkbox');
    var redirectUrlGroup = document.querySelector('.redirect-url-group');
    var redirectUrlInput = document.getElementById('redirect_url');

    function syncRedirect() {
        redirectUrlGroup.style.display = redirectCheckbox.checked ? 'block' : 'none';
        if (!redirectCheckbox.checked) redirectUrlInput.value = '';
    }
    redirectCheckbox.addEventListener('change', syncRedirect);

    // ---- seats.io fields toggle ----
    var ticketingModeSelect = document.getElementById('ticketing_mode');
    var seatsioFields       = document.getElementById('seatsio-fields');

    function syncSeatsioFields() {
        var mode = ticketingModeSelect.value;
        seatsioFields.style.display = (mode === 'reserved' || mode === 'mixed') ? 'block' : 'none';
    }
    ticketingModeSelect.addEventListener('change', syncSeatsioFields);

    // ---- Form submit: parse additional_info into JSON ----
    document.getElementById('show-form').addEventListener('submit', function () {
        var field = document.getElementById('additional_info');
        if (field && field.value.trim()) {
            var obj = {};
            field.value.trim().split('\n').forEach(function (line) {
                var parts = line.split(':');
                if (parts.length >= 2) {
                    obj[parts[0].trim()] = parts.slice(1).join(':').trim();
                }
            });
            field.value = JSON.stringify(obj);
        }
    });

});
</script>
@endsection
