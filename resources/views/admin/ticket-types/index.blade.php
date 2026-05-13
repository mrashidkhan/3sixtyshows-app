@extends('admin.layout.layout')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    Ticket Types
                    <small>{{ $show->title }}</small>
                </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a href="{{ route('admin.ticket-types.create-for-show', $show) }}" class="btn btn-success btn-sm">
                            <i class="fa fa-plus"></i> Add Ticket Type
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('show.index') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Shows
                        </a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">

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

                {{-- Show Summary Card --}}
                <div class="card mb-3" style="border-left: 4px solid #26B99A;">
                    <div class="card-body py-2">
                        <div class="row">
                            <div class="col-md-3">
                                <small class="text-muted">Venue</small><br>
                                <strong>{{ $show->venue->name ?? 'N/A' }}</strong>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Start Date</small><br>
                                <strong>{{ $show->start_date ? $show->start_date->format('M d, Y H:i') : 'N/A' }}</strong>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Status</small><br>
                                @if($show->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                                <span class="badge badge-info ml-1">{{ ucfirst($show->status ?? 'N/A') }}</span>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Ticket Types</small><br>
                                <strong>{{ $ticketTypes->count() }}</strong> defined
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ticket Types Table --}}
                <div class="table-responsive">
                    <table class="table table-striped jambo_table">
                        <thead>
                            <tr class="headings">
                                <th><b>#</b></th>
                                <th><b>Name</b></th>
                                <th><b>Price</b></th>
                                <th><b>Capacity</b></th>
                                <th><b>Sold</b></th>
                                <th><b>Available</b></th>
                                <th><b>Seats.io Category</b></th>
                                <th><b>Order</b></th>
                                <th><b>Status</b></th>
                                <th class="no-link last"><b>Actions</b></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ticketTypes as $ticketType)
                            <tr class="{{ $loop->even ? 'even' : 'odd' }}">
                                <td>{{ $ticketType->id }}</td>
                                <td>
                                    <strong>{{ $ticketType->name }}</strong>
                                    @if($ticketType->description)
                                        <br><small class="text-muted">{{ Str::limit($ticketType->description, 40) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($ticketType->price == 0)
                                        <span class="badge badge-success">Free</span>
                                    @else
                                        <strong>${{ number_format($ticketType->price, 2) }}</strong>
                                    @endif
                                </td>
                                <td>
                                    @if($ticketType->capacity)
                                        {{ number_format($ticketType->capacity) }}
                                    @else
                                        <span class="badge badge-info">Unlimited</span>
                                    @endif
                                </td>
                                <td>{{ $ticketType->sold_tickets }}</td>
                                <td>
                                    @if($ticketType->capacity)
                                        @if($ticketType->is_sold_out)
                                            <span class="badge badge-danger">Sold Out</span>
                                        @else
                                            <span class="badge badge-success">{{ $ticketType->available_tickets }}</span>
                                        @endif
                                    @else
                                        <span class="badge badge-info">Unlimited</span>
                                    @endif
                                </td>
                                <td>
                                    @if($ticketType->seatsio_category_key)
                                        <code>{{ $ticketType->seatsio_category_key }}</code>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $ticketType->display_order }}</td>
                                <td>
                                    @if($ticketType->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="last">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.ticket-types.edit', $ticketType) }}"
                                           class="btn btn-info btn-sm" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.ticket-types.delete', $ticketType) }}"
                                              method="POST" style="display:inline;"
                                              onsubmit="return confirm('Delete ticket type \'{{ addslashes($ticketType->name) }}\'? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="fa fa-ticket fa-3x text-muted mb-3"></i>
                                    <h5>No ticket types yet</h5>
                                    <p class="text-muted">
                                        <a href="{{ route('admin.ticket-types.create-for-show', $show) }}" class="btn btn-primary btn-sm">
                                            <i class="fa fa-plus"></i> Add First Ticket Type
                                        </a>
                                    </p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>{{-- /.x_content --}}
        </div>{{-- /.x_panel --}}
    </div>
</div>
@endsection
