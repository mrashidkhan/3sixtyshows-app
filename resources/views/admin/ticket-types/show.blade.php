@extends('admin.layout.layout')

@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Ticket Type Details
                    <small>{{ $ticketType->name }}</small>
                </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a href="{{ route('admin.ticket-types.edit', $ticketType) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.ticket-types.index', $ticketType->show) }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Ticket Types
                        </a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <br>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif

                <!-- ================================================ -->
                <!-- Show Context                                       -->
                <!-- ================================================ -->
                <div class="x_title"><h4>Show</h4><div class="clearfix"></div></div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Show Title</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static"><strong>{{ $ticketType->show->title ?? 'N/A' }}</strong></p>
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Venue</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">{{ $ticketType->show->venue->name ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Show Date</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">
                            {{ $ticketType->show->start_date ? $ticketType->show->start_date->format('F d, Y h:i A') : 'N/A' }}
                        </p>
                    </div>
                </div>

                <!-- ================================================ -->
                <!-- Ticket Type Details                                -->
                <!-- ================================================ -->
                <div class="x_title"><h4>Ticket Type Details</h4><div class="clearfix"></div></div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Name</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static"><strong>{{ $ticketType->name }}</strong></p>
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Description</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">{{ $ticketType->description ?: '—' }}</p>
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Price</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">
                            @if($ticketType->price == 0)
                                <span class="badge badge-success">Free</span>
                            @else
                                <strong>${{ number_format($ticketType->price, 2) }}</strong>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Capacity</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">
                            @if($ticketType->capacity)
                                {{ number_format($ticketType->capacity) }}
                            @else
                                <span class="badge badge-info">Unlimited</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Display Order</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">{{ $ticketType->display_order }}</p>
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Status</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">
                            @if($ticketType->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- ================================================ -->
                <!-- Seats.io Integration                               -->
                <!-- ================================================ -->
                <div class="x_title"><h4>Seats.io Integration</h4><div class="clearfix"></div></div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Category Key</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">
                            @if($ticketType->seatsio_category_key)
                                <code>{{ $ticketType->seatsio_category_key }}</code>
                                <span class="badge badge-success ml-2">
                                    <i class="fa fa-check"></i> Configured
                                </span>
                            @else
                                <span class="text-muted">Not set</span>
                                <span class="badge badge-secondary ml-2">GA / not linked</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Booking Lookup</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static text-muted" style="font-size:0.875rem;">
                            @if($ticketType->seatsio_category_key)
                                When a customer selects a seat whose category key is
                                <code>{{ $ticketType->seatsio_category_key }}</code>,
                                this ticket type (<strong>{{ $ticketType->name }}</strong>,
                                <strong>{{ $ticketType->formatted_price }}</strong>) will be applied automatically.
                            @else
                                No seats.io category linked. Set a category key to enable automatic
                                seat-to-ticket-type matching for reserved seating.
                            @endif
                        </p>
                    </div>
                </div>

                <!-- ================================================ -->
                <!-- Sales Stats                                        -->
                <!-- ================================================ -->
                <div class="x_title"><h4>Sales Stats</h4><div class="clearfix"></div></div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Tickets Sold</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static"><strong>{{ $ticketType->sold_tickets }}</strong></p>
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Tickets Available</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">
                            @if($ticketType->capacity)
                                @if($ticketType->is_sold_out)
                                    <span class="badge badge-danger">Sold Out</span>
                                @else
                                    <strong>{{ $ticketType->available_tickets }}</strong> remaining
                                @endif
                            @else
                                <span class="badge badge-info">Unlimited</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Revenue</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">
                            <strong>${{ number_format($ticketType->sold_tickets * $ticketType->price, 2) }}</strong>
                        </p>
                    </div>
                </div>

                <!-- ================================================ -->
                <!-- Record Info                                        -->
                <!-- ================================================ -->
                <div class="x_title"><h4>Record Info</h4><div class="clearfix"></div></div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Created At</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">{{ $ticketType->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Last Updated</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">{{ $ticketType->updated_at->format('F d, Y h:i A') }}</p>
                    </div>
                </div>

                <div class="ln_solid"></div>

                <div class="form-group">
                    <div class="col-md-6 offset-md-3">
                        <a href="{{ route('admin.ticket-types.index', $ticketType->show) }}" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> Back to Ticket Types
                        </a>
                        <a href="{{ route('admin.ticket-types.edit', $ticketType) }}" class="btn btn-primary">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                    </div>
                </div>

            </div>{{-- /.x_content --}}
        </div>{{-- /.x_panel --}}
    </div>
</div>

@endsection
