@extends('admin.layout.layout')

@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Add Show <small>Create a new show</small></h2>
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
                <form id="show-form" action="{{ route('show.store') }}" class="form-horizontal form-label-left" method="post" enctype="multipart/form-data" novalidate>
                    @csrf

                    <!-- ================================================ -->
                    <!-- Basic Information                                  -->
                    <!-- ================================================ -->
                    <div class="x_title"><h4>Basic Information</h4><div class="clearfix"></div></div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="title">Title <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <input type="text" id="title" name="title" required class="form-control" value="{{ old('title') }}">
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="slug">Slug <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <input type="text" id="slug" name="slug" required class="form-control" value="{{ old('slug') }}">
                            <small class="form-text text-muted">Auto-generated from the title if left empty.</small>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="category_id">Category <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <select id="category_id" name="category_id" required class="form-control">
                                <option value="" disabled selected>Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="venue_id">Venue <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <select id="venue_id" name="venue_id" required class="form-control">
                                <option value="" disabled selected>Select Venue</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>{{ $venue->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="short_description">Short Description <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <textarea id="short_description" name="short_description" required class="form-control" rows="3">{{ old('short_description') }}</textarea>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="description">Full Description <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <textarea id="description" name="description" required class="form-control" rows="6">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="featured_image">Featured Image <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <input type="file" id="featured_image" name="featured_image" class="form-control" accept="image/*" required>
                            <small class="form-text text-muted">Recommended: 1200×800 px. Max 2 MB.</small>
                        </div>
                    </div>

                    <!-- ================================================ -->
                    <!-- Dates and Tickets                                  -->
                    <!-- ================================================ -->
                    <div class="x_title"><h4>Dates and Tickets</h4><div class="clearfix"></div></div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="start_date">Start Date <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <input type="datetime-local" id="start_date" name="start_date" required class="form-control" value="{{ old('start_date') }}">
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="end_date">End Date <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <input type="datetime-local" id="end_date" name="end_date" required class="form-control" value="{{ old('end_date') }}">
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="price">Price</label>
                        <div class="col-md-6 col-sm-6">
                            <input type="number" id="price" name="price" step="0.01" min="0" class="form-control" value="{{ old('price') }}">
                            <small class="form-text text-muted">Leave empty or 0 for free events.</small>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="available_tickets">Available Tickets</label>
                        <div class="col-md-6 col-sm-6">
                            <input type="number" id="available_tickets" name="available_tickets" min="0" class="form-control" value="{{ old('available_tickets') }}">
                            <small class="form-text text-muted">Leave empty for unlimited tickets.</small>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="duration">Duration (minutes)</label>
                        <div class="col-md-6 col-sm-6">
                            <input type="number" id="duration" name="duration" min="0" class="form-control" value="{{ old('duration') }}">
                        </div>
                    </div>

                    <!-- ================================================ -->
                    <!-- Additional Information                             -->
                    <!-- ================================================ -->
                    <div class="x_title"><h4>Additional Information</h4><div class="clearfix"></div></div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="performers">Performers</label>
                        <div class="col-md-6 col-sm-6">
                            <textarea id="performers" name="performers" class="form-control" rows="3">{{ old('performers') }}</textarea>
                            <small class="form-text text-muted">One performer per line.</small>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="additional_info">Additional Info</label>
                        <div class="col-md-6 col-sm-6">
                            <textarea id="additional_info" name="additional_info" class="form-control" rows="3">{{ old('additional_info') }}</textarea>
                            <small class="form-text text-muted">Format: "Title: Description" (one per line).</small>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="age_restriction">Age Restriction</label>
                        <div class="col-md-6 col-sm-6">
                            <input type="text" id="age_restriction" name="age_restriction" class="form-control" value="{{ old('age_restriction') }}">
                            <small class="form-text text-muted">E.g. "18+", "All ages"</small>
                        </div>
                    </div>

                    <!-- ================================================ -->
                    <!-- Settings                                           -->
                    <!-- ================================================ -->
                    <div class="x_title"><h4>Settings</h4><div class="clearfix"></div></div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Featured Show</label>
                        <div class="col-md-6 col-sm-6">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    Display this show in featured areas
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="status">Status <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <select id="status" name="status" required class="form-control">
                                <option value="" disabled selected>Select Status</option>
                                <option value="upcoming"  {{ old('status') == 'upcoming'  ? 'selected' : '' }}>Upcoming</option>
                                <option value="ongoing"   {{ old('status') == 'ongoing'   ? 'selected' : '' }}>Ongoing</option>
                                <option value="past"      {{ old('status') == 'past'      ? 'selected' : '' }}>Past</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="is_active">Active <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <select id="is_active" name="is_active" required class="form-control">

                                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <small class="form-text text-muted">Inactive shows won't be visible on the website.</small>
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
                                    <input type="checkbox" id="redirect-checkbox" name="redirect" value="1" {{ old('redirect') ? 'checked' : '' }}>
                                    Redirect users to an external URL when clicking on this show
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="item form-group redirect-url-group" id="redirect-url-container" style="display: none;">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="redirect_url">Redirect URL</label>
                        <div class="col-md-6 col-sm-6">
                            <input type="url" id="redirect_url" name="redirect_url" class="form-control" value="{{ old('redirect_url') }}">
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
                        Only fill these fields if you are using <strong>seats.io reserved seating</strong> for this show.
                        For standard general admission shows leave <em>Ticketing Mode</em> as <strong>General Admission</strong>.
                    </div>

                    {{-- Ticketing Mode --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="ticketing_mode">
                            Ticketing Mode <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <select id="ticketing_mode" name="ticketing_mode" class="form-control" required>
                                <option value="general_admission" {{ old('ticketing_mode', 'general_admission') == 'general_admission' ? 'selected' : '' }}>
                                    General Admission (default)
                                </option>
                                <option value="reserved" {{ old('ticketing_mode') == 'reserved' ? 'selected' : '' }}>
                                    Reserved Seating (seats.io)
                                </option>
                                <option value="mixed" {{ old('ticketing_mode') == 'mixed' ? 'selected' : '' }}>
                                    Mixed — GA + Reserved (seats.io)
                                </option>
                                <option value="none" {{ old('ticketing_mode') == 'none' ? 'selected' : '' }}>
                                    None / External (redirect only)
                                </option>
                            </select>
                            <small class="form-text text-muted">
                                Choose <strong>Reserved</strong> or <strong>Mixed</strong> to activate the seats.io widget.
                            </small>
                        </div>
                    </div>

                    {{-- seats.io fields — shown/hidden by JS --}}
                    <div id="seatsio-fields" style="display: none;">

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="seatsio_chart_key">
                                Chart Key
                            </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" id="seatsio_chart_key" name="seatsio_chart_key" class="form-control"
                                       value="{{ old('seatsio_chart_key') }}" placeholder="e.g. 3fa6f...">
                                <small class="form-text text-muted">
                                    Copy from your <strong>seats.io Designer</strong> → Charts → select chart → copy the key shown in the URL.
                                </small>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="seatsio_event_key">
                                Event Key
                            </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" id="seatsio_event_key" name="seatsio_event_key" class="form-control"
                                       value="{{ old('seatsio_event_key') }}" placeholder="Created via seats.io API">
                                <small class="form-text text-muted">
                                    Generated automatically by the seats.io API when you publish an event.
                                    Leave blank if not yet created; update later once you have it.
                                </small>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="seatsio_public_key">
                                Public Key Override
                            </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" id="seatsio_public_key" name="seatsio_public_key" class="form-control"
                                       value="{{ old('seatsio_public_key') }}" placeholder="Leave blank to use .env default">
                                <small class="form-text text-muted">
                                    Only set this if this show uses a <em>different</em> seats.io workspace than the global one in <code>.env</code>.
                                </small>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Tickets On Sale</label>
                            <div class="col-md-6 col-sm-6">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="tickets_on_sale" name="tickets_on_sale" value="1"
                                               {{ old('tickets_on_sale') ? 'checked' : '' }}>
                                        Allow ticket purchases to go through (seats.io widget will be active)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="sale_starts_at">
                                Sale Starts At
                            </label>
                            <div class="col-md-6 col-sm-6">
                                <input type="datetime-local" id="sale_starts_at" name="sale_starts_at" class="form-control"
                                       value="{{ old('sale_starts_at') }}">
                                <small class="form-text text-muted">
                                    Optional pre-sale date. Leave blank to open sales immediately when "Tickets On Sale" is checked.
                                </small>
                            </div>
                        </div>

                    </div>{{-- /seatsio-fields --}}

                    <div class="ln_solid"></div>

                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
                            <button type="button" class="btn btn-primary" onclick="window.history.back();">Cancel</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" class="btn btn-success">Submit</button>
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

    // ---- Auto-generate slug from title ----
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
    var redirectCheckbox   = document.getElementById('redirect-checkbox');
    var redirectUrlGroup   = document.getElementById('redirect-url-container');
    var redirectUrlInput   = document.getElementById('redirect_url');

    function syncRedirect() {
        redirectUrlGroup.style.display = redirectCheckbox.checked ? 'block' : 'none';
        if (!redirectCheckbox.checked) redirectUrlInput.value = '';
    }
    syncRedirect(); // set initial state
    redirectCheckbox.addEventListener('change', syncRedirect);

    // ---- seats.io fields toggle ----
    var ticketingModeSelect = document.getElementById('ticketing_mode');
    var seatsioFields       = document.getElementById('seatsio-fields');

    function syncSeatsioFields() {
        var mode = ticketingModeSelect.value;
        seatsioFields.style.display = (mode === 'reserved' || mode === 'mixed') ? 'block' : 'none';
    }
    syncSeatsioFields(); // set initial state
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
