@extends('admin.layout.layout')

@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Add Ticket Type <small>Create a new ticket type for a show</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a href="{{ route('admin.ticket-types.all') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to All Ticket Types
                        </a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <br>
                <form action="{{ route('admin.ticket-types.store') }}" class="form-horizontal form-label-left" method="POST" novalidate>
                    @csrf

                    {{-- Show --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="show_id">
                            Show <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <select id="show_id" name="show_id" required class="form-control">
                                <option value="" disabled selected>Select Show</option>
                                @foreach($shows as $show)
                                    <option value="{{ $show->id }}" {{ old('show_id') == $show->id ? 'selected' : '' }}>
                                        {{ $show->title }} — {{ $show->venue->name ?? 'No Venue' }}
                                        ({{ \Carbon\Carbon::parse($show->start_date)->format('M d, Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Name --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="name">
                            Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <input type="text" id="name" name="name" required class="form-control"
                                   value="{{ old('name') }}" placeholder="e.g. VIP, General Admission, Early Bird">
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="description">
                            Description
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <textarea id="description" name="description" class="form-control" rows="3"
                                      placeholder="Optional description of what this ticket type includes">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    {{-- Price --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="price">
                            Price <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <input type="number" id="price" name="price" required step="0.01" min="0"
                                   class="form-control" value="{{ old('price', 0) }}">
                            <small class="form-text text-muted">Set to 0 for free tickets.</small>
                        </div>
                    </div>

                    {{-- Capacity --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="capacity">
                            Capacity
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <input type="number" id="capacity" name="capacity" min="1"
                                   class="form-control" value="{{ old('capacity') }}">
                            <small class="form-text text-muted">Leave empty for unlimited.</small>
                        </div>
                    </div>

                    {{-- Display Order --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="display_order">
                            Display Order
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <input type="number" id="display_order" name="display_order" min="0"
                                   class="form-control" value="{{ old('display_order', 0) }}">
                            <small class="form-text text-muted">Lower numbers appear first.</small>
                        </div>
                    </div>

                    {{-- Seats.io Category Key --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="seatsio_category_key">
                            Seats.io Category Key
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <input type="text" id="seatsio_category_key" name="seatsio_category_key"
                                   class="form-control"
                                   value="{{ old('seatsio_category_key') }}"
                                   placeholder="e.g. vip, general, balcony">
                            <small class="form-text text-muted">
                                Must <strong>exactly</strong> match the category key in your
                                <strong>seats.io chart designer</strong>. Used to automatically
                                match seat selections to this ticket type and apply the correct price.
                                Leave empty if this show does not use reserved seating.
                            </small>
                        </div>
                    </div>

                    {{-- Is Active --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Status</label>
                        <div class="col-md-6 col-sm-6">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="is_active" value="1"
                                           {{ old('is_active', '1') ? 'checked' : '' }}>
                                    Active (visible to customers)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="ln_solid"></div>

                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
                            <button type="button" class="btn btn-default" onclick="window.history.back();">Cancel</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Create Ticket Type
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
