@extends('admin.layout.layout')

@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Edit Ticket Type
                    <small>{{ $ticketType->name }} — {{ $show->title }}</small>
                </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a href="{{ route('admin.ticket-types.index', $show) }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Ticket Types
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

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- Read-only Show context --}}
                <div class="card mb-4" style="border-left: 4px solid #26B99A;">
                    <div class="card-body py-2">
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">Show</small><br>
                                <strong>{{ $show->title }}</strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Venue</small><br>
                                <strong>{{ $show->venue->name ?? 'N/A' }}</strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Show Date</small><br>
                                <strong>{{ $show->start_date ? $show->start_date->format('M d, Y H:i') : 'N/A' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="ticket-type-form"
                      action="{{ route('admin.ticket-types.update', $ticketType) }}"
                      class="form-horizontal form-label-left"
                      method="POST"
                      novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="name">
                            Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <input type="text" id="name" name="name" required class="form-control"
                                   value="{{ old('name', $ticketType->name) }}"
                                   placeholder="e.g. VIP, General Admission, Early Bird">
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="description">
                            Description
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <textarea id="description" name="description" class="form-control" rows="3"
                                      placeholder="Optional description">{{ old('description', $ticketType->description) }}</textarea>
                        </div>
                    </div>

                    {{-- Price --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="price">
                            Price <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <input type="number" id="price" name="price" required step="0.01" min="0"
                                   class="form-control"
                                   value="{{ old('price', $ticketType->price) }}">
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
                                   class="form-control"
                                   value="{{ old('capacity', $ticketType->capacity) }}">
                            <small class="form-text text-muted">
                                Leave empty for unlimited.
                                @if($ticketType->sold_tickets > 0)
                                    <strong class="text-warning">
                                        {{ $ticketType->sold_tickets }} ticket(s) already sold — capacity cannot be set below this.
                                    </strong>
                                @endif
                            </small>
                        </div>
                    </div>

                    {{-- Display Order --}}
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="display_order">
                            Display Order
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <input type="number" id="display_order" name="display_order" min="0"
                                   class="form-control"
                                   value="{{ old('display_order', $ticketType->display_order) }}">
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
                                   value="{{ old('seatsio_category_key', $ticketType->seatsio_category_key) }}"
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
                        <label class="col-form-label col-md-3 col-sm-3 label-align">
                            Status <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <select name="is_active" class="form-control" required>
                                <option value="1" {{ old('is_active', $ticketType->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>
                                    Active (visible to customers)
                                </option>
                                <option value="0" {{ old('is_active', $ticketType->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>
                                    Inactive (hidden from customers)
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- Stats (read-only) --}}
                    <div class="x_title"><h4>Current Stats (read-only)</h4><div class="clearfix"></div></div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Tickets Sold</label>
                        <div class="col-md-6 col-sm-6">
                            <p class="form-control-static">
                                <strong>{{ $ticketType->sold_tickets }}</strong>
                                @if($ticketType->capacity)
                                    / {{ $ticketType->capacity }}
                                    @if($ticketType->is_sold_out)
                                        <span class="badge badge-danger ml-2">Sold Out</span>
                                    @else
                                        <span class="badge badge-success ml-2">{{ $ticketType->available_tickets }} available</span>
                                    @endif
                                @else
                                    <span class="badge badge-info ml-2">Unlimited capacity</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="ln_solid"></div>

                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
                            <a href="{{ route('admin.ticket-types.index', $show) }}" class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Update Ticket Type
                            </button>
                        </div>
                    </div>

                </form>
            </div>{{-- /.x_content --}}
        </div>{{-- /.x_panel --}}
    </div>
</div>

@endsection
