@extends('admin.layout.layout')

@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Show Details <small>View show information</small></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br>

                <!-- Action Buttons -->
                <div class="text-right mb-4">
                    <a href="{{ route('show.edit', $show->id) }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-edit"></i> Edit Show
                    </a>
                    <a href="{{ route('show.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left"></i> Back to List
                    </a>
                </div>

                <!-- ================================================ -->
                <!-- Basic Information                                  -->
                <!-- ================================================ -->
                <div class="x_title"><h4>Basic Information</h4><div class="clearfix"></div></div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Title</label>
                    <div class="col-md-6 col-sm-6"><p class="form-control-static">{{ $show->title }}</p></div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Slug</label>
                    <div class="col-md-6 col-sm-6"><p class="form-control-static">{{ $show->slug }}</p></div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Category</label>
                    <div class="col-md-6 col-sm-6"><p class="form-control-static">{{ $show->category->name ?? 'N/A' }}</p></div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Venue</label>
                    <div class="col-md-6 col-sm-6"><p class="form-control-static">{{ $show->venue->name ?? 'N/A' }}</p></div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Short Description</label>
                    <div class="col-md-6 col-sm-6"><p class="form-control-static">{{ $show->short_description }}</p></div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Full Description</label>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-control-static">{!! nl2br(e($show->description)) !!}</div>
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Featured Image</label>
                    <div class="col-md-6 col-sm-6">
                        @if($show->featured_image)
                            <img src="{{ asset('storage/' . $show->featured_image) }}" alt="{{ $show->title }}"
                                 style="max-width: 100%; max-height: 300px; border: 1px solid #ddd; padding: 3px;">
                        @else
                            <p class="form-control-static text-muted">No image available</p>
                        @endif
                    </div>
                </div>

                <!-- ================================================ -->
                <!-- Date & Time                                        -->
                <!-- ================================================ -->
                <div class="x_title"><h4>Date & Time</h4><div class="clearfix"></div></div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Start Date & Time</label>
                    <div class="col-md-6 col-sm-6"><p class="form-control-static">{{ $show->start_date->format('F d, Y h:i A') }}</p></div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">End Date & Time</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">{{ $show->end_date ? $show->end_date->format('F d, Y h:i A') : 'N/A' }}</p>
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Duration</label>
                    <div class="col-md-6 col-sm-6"><p class="form-control-static">{{ $show->duration ?? 'N/A' }}</p></div>
                </div>

                <!-- ================================================ -->
                <!-- Ticket Information                                 -->
                <!-- ================================================ -->
                <div class="x_title"><h4>Ticket Information</h4><div class="clearfix"></div></div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Price</label>
                    <div class="col-md-6 col-sm-6"><p class="form-control-static">{{ $show->formatted_price }}</p></div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Available Tickets</label>
                    <div class="col-md-6 col-sm-6"><p class="form-control-static">{{ $show->available_tickets ?? 'Unlimited' }}</p></div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Tickets Sold</label>
                    <div class="col-md-6 col-sm-6"><p class="form-control-static">{{ $show->sold_tickets ?? 0 }}</p></div>
                </div>

                <!-- ================================================ -->
                <!-- Additional Information                             -->
                <!-- ================================================ -->
                <div class="x_title"><h4>Additional Information</h4><div class="clearfix"></div></div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Performers/Artists</label>
                    <div class="col-md-6 col-sm-6">
                        @if(is_array($show->performers) && count($show->performers) > 0)
                            <ul class="list-unstyled mb-0">
                                @foreach($show->performers as $performer)
                                    <li>{{ $performer }}</li>
                                @endforeach
                            </ul>
                        @elseif(is_string($show->performers) && !empty($show->performers))
                            <p class="form-control-static">{{ $show->performers }}</p>
                        @else
                            <p class="form-control-static text-muted">No performers listed</p>
                        @endif
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Additional Information</label>
                    <div class="col-md-6 col-sm-6">
                        @if(is_array($show->additional_info) && count($show->additional_info) > 0)
                            <dl class="mb-0">
                                @foreach($show->additional_info as $key => $value)
                                    <dt>{{ $key }}</dt><dd>{{ $value }}</dd>
                                @endforeach
                            </dl>
                        @else
                            <p class="form-control-static text-muted">No additional information</p>
                        @endif
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Age Restriction</label>
                    <div class="col-md-6 col-sm-6"><p class="form-control-static">{{ $show->age_restriction ?? 'None' }}</p></div>
                </div>

                <!-- ================================================ -->
                <!-- Settings                                           -->
                <!-- ================================================ -->
                <div class="x_title"><h4>Settings</h4><div class="clearfix"></div></div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Featured</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">
                            @if($show->is_featured)
                                <span class="badge badge-success">Yes</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Status</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">
                            @if($show->status == 'upcoming')     <span class="badge badge-info">Upcoming</span>
                            @elseif($show->status == 'ongoing')  <span class="badge badge-success">Ongoing</span>
                            @elseif($show->status == 'past')     <span class="badge badge-secondary">Past</span>
                            @elseif($show->status == 'cancelled')<span class="badge badge-danger">Cancelled</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Active</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">
                            @if($show->is_active)
                                <span class="badge badge-success">Yes</span>
                            @else
                                <span class="badge badge-danger">No</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- ================================================ -->
                <!-- Redirect Settings                                  -->
                <!-- ================================================ -->
                <div class="x_title"><h4>Redirect Settings</h4><div class="clearfix"></div></div>

                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Redirect Enabled</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">
                            @if($show->redirect)
                                <span class="badge badge-success">Yes</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </p>
                    </div>
                </div>

                @if($show->redirect && $show->redirect_url)
                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Redirect URL</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">
                            <a href="{{ $show->redirect_url }}" target="_blank" rel="noopener">{{ $show->redirect_url }}</a>
                        </p>
                    </div>
                </div>
                @endif

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

                {{-- Ticketing Mode --}}
                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Ticketing Mode</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">
                            @php $mode = $show->ticketing_mode ?? 'general_admission'; @endphp
                            @if($mode === 'reserved')
                                <span class="badge" style="background:#6f42c1;color:#fff;">
                                    <i class="fa fa-map-marker"></i> Reserved Seating (seats.io)
                                </span>
                            @elseif($mode === 'mixed')
                                <span class="badge badge-info">
                                    <i class="fa fa-th"></i> Mixed — GA + Reserved (seats.io)
                                </span>
                            @elseif($mode === 'none')
                                <span class="badge badge-secondary">
                                    <i class="fa fa-external-link"></i> None / External
                                </span>
                            @else
                                <span class="badge badge-light" style="border:1px solid #ccc;">
                                    <i class="fa fa-ticket"></i> General Admission
                                </span>
                            @endif
                        </p>
                    </div>
                </div>

                @if($show->usesSeatsIo())

                {{-- Chart Key --}}
                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Chart Key</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">
                            @if($show->seatsio_chart_key)
                                <code>{{ $show->seatsio_chart_key }}</code>
                                <span class="badge badge-success ml-2"><i class="fa fa-check"></i> Set</span>
                            @else
                                <span class="text-muted">Not set</span>
                                <span class="badge badge-warning ml-2"><i class="fa fa-exclamation-triangle"></i> Required</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Event Key --}}
                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Event Key</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">
                            @if($show->seatsio_event_key)
                                <code>{{ $show->seatsio_event_key }}</code>
                                <span class="badge badge-success ml-2"><i class="fa fa-check"></i> Set</span>
                            @else
                                <span class="text-muted">Not set</span>
                                <span class="badge badge-warning ml-2"><i class="fa fa-exclamation-triangle"></i> Required</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Public Key Override --}}
                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Public Key Override</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">
                            @if($show->getOriginal('seatsio_public_key'))
                                <code>{{ $show->getOriginal('seatsio_public_key') }}</code>
                                <span class="badge badge-info ml-2">Per-show key</span>
                            @else
                                <span class="text-muted">Using global <code>.env</code> key</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Tickets On Sale --}}
                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Tickets On Sale</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">
                            @if($show->tickets_on_sale)
                                <span class="badge badge-success"><i class="fa fa-check"></i> Yes</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Sale Starts At --}}
                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Sale Starts At</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">
                            @if($show->sale_starts_at)
                                {{ $show->sale_starts_at->format('F d, Y h:i A') }}
                                @if($show->sale_starts_at->isFuture())
                                    <span class="badge badge-info ml-2">Upcoming</span>
                                @else
                                    <span class="badge badge-success ml-2">In the past — sale open</span>
                                @endif
                            @else
                                <span class="text-muted">Immediate (no pre-sale delay)</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Overall seats.io readiness indicator --}}
                <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">seats.io Status</label>
                    <div class="col-md-6 col-sm-6">
                        <p class="form-control-static">
                            @if($show->isSeatsIoReady())
                                <span class="badge badge-success">
                                    <i class="fa fa-check-circle"></i> Fully Configured
                                </span>
                            @elseif($show->seatsio_chart_key && !$show->seatsio_event_key)
                                <span class="badge badge-warning">
                                    <i class="fa fa-exclamation-triangle"></i> Chart set — Event key missing
                                </span>
                            @else
                                <span class="badge badge-danger">
                                    <i class="fa fa-times-circle"></i> Not Configured
                                </span>
                            @endif

                            @if($show->isSaleOpen())
                                &nbsp;<span class="badge badge-success">
                                    <i class="fa fa-shopping-cart"></i> On Sale Now
                                </span>
                            @endif
                        </p>
                    </div>
                </div>

                @endif {{-- /usesSeatsIo --}}

                <div class="ln_solid"></div>

                <div class="form-group">
                    <div class="col-md-6 offset-md-3">
                        <a href="{{ route('show.index') }}" class="btn btn-secondary">Back to List</a>
                        <a href="{{ route('show.edit', $show->id) }}" class="btn btn-primary">Edit</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
