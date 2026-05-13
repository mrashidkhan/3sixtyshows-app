@extends('admin.layout.layout')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Shows <small>Manage all shows</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li>
                            <a href="{{ route('show.create') }}" class="btn btn-success btn-sm">
                                <i class="fa fa-plus"></i> Add New Show
                            </a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Filter Options -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#filterCollapse" aria-expanded="true">
                                            <i class="fa fa-filter"></i> Filters
                                        </button>
                                    </h5>
                                </div>
                                <div id="filterCollapse" class="collapse show">
                                    <div class="card-body">
                                        <form action="{{ route('show.index') }}" method="GET" id="filterForm">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="category">Category</label>
                                                        <select name="category" id="category" class="form-control form-control-sm">
                                                            <option value="">All Categories</option>
                                                            @foreach($categories as $category)
                                                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                                    {{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="venue">Venue</label>
                                                        <select name="venue" id="venue" class="form-control form-control-sm">
                                                            <option value="">All Venues</option>
                                                            @foreach($venues as $venue)
                                                                <option value="{{ $venue->id }}" {{ request('venue') == $venue->id ? 'selected' : '' }}>
                                                                    {{ $venue->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="status">Status</label>
                                                        <select name="status" id="status" class="form-control form-control-sm">
                                                            <option value="">All Statuses</option>
                                                            <option value="upcoming"  {{ request('status') == 'upcoming'  ? 'selected' : '' }}>Upcoming</option>
                                                            <option value="ongoing"   {{ request('status') == 'ongoing'   ? 'selected' : '' }}>Ongoing</option>
                                                            <option value="past"      {{ request('status') == 'past'      ? 'selected' : '' }}>Past</option>
                                                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="is_active">Active</label>
                                                        <select name="is_active" id="is_active" class="form-control form-control-sm">
                                                            <option value="">All</option>
                                                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active</option>
                                                            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                {{-- NEW: seats.io ticketing mode filter --}}
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="ticketing_mode">Ticketing Mode</label>
                                                        <select name="ticketing_mode" id="ticketing_mode" class="form-control form-control-sm">
                                                            <option value="">All Modes</option>
                                                            <option value="general_admission" {{ request('ticketing_mode') == 'general_admission' ? 'selected' : '' }}>General Admission</option>
                                                            <option value="reserved"          {{ request('ticketing_mode') == 'reserved'          ? 'selected' : '' }}>Reserved (seats.io)</option>
                                                            <option value="mixed"             {{ request('ticketing_mode') == 'mixed'             ? 'selected' : '' }}>Mixed (seats.io)</option>
                                                            <option value="none"              {{ request('ticketing_mode') == 'none'              ? 'selected' : '' }}>None / External</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="search">Search</label>
                                                        <input type="text" name="search" id="search" class="form-control form-control-sm" placeholder="Search titles..." value="{{ request('search') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="fa fa-search"></i> Apply Filters
                                                    </button>
                                                    <a href="{{ route('show.index') }}" class="btn btn-default btn-sm">
                                                        <i class="fa fa-refresh"></i> Reset
                                                    </a>
                                                    <span class="ml-3 text-muted">
                                                        Showing {{ $shows->count() }} show(s)
                                                        @if(request()->anyFilled(['category', 'venue', 'status', 'is_active', 'ticketing_mode', 'search']))
                                                            (filtered)
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped jambo_table bulk_action">
                            <thead>
                                <tr class="headings">
                                    <th><b>ID</b></th>
                                    <th><b>Image</b></th>
                                    <th><b>Title</b></th>
                                    <th><b>Category</b></th>
                                    <th><b>Venue</b></th>
                                    <th><b>Date</b></th>
                                    <th><b>Status</b></th>
                                    <th><b>Price</b></th>
                                    <th><b>Tickets</b></th>
                                    {{-- NEW column --}}
                                    <th><b>Ticketing</b></th>
                                    <th class="no-link last"><b>Actions</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shows as $show)
                                <tr class="{{ $loop->even ? 'even' : 'odd' }} pointer">
                                    <td>{{ $show->id }}</td>
                                    <td>
                                        @if($show->featured_image)
                                            <img src="{{ asset('storage/' . $show->featured_image) }}" alt="{{ $show->title }}"
                                                style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                                        @else
                                            <span class="badge badge-secondary">No Image</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $show->title }}</strong>
                                        <div class="mt-1">
                                            @if($show->is_featured)
                                                <span class="badge badge-warning">Featured</span>
                                            @endif
                                            @if($show->redirect)
                                                <span class="badge badge-info" title="{{ $show->redirect_url }}">Redirect</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td><span class="badge badge-light">{{ $show->category->name ?? 'None' }}</span></td>
                                    <td><span class="badge badge-light">{{ $show->venue->name ?? 'None' }}</span></td>
                                    <td>
                                        <small>
                                            <strong>Start:</strong> {{ $show->start_date->format('M d, Y H:i') }}<br>
                                            @if($show->end_date)
                                                <strong>End:</strong> {{ $show->end_date->format('M d, Y H:i') }}
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <div>
                                            @if($show->status == 'upcoming')
                                                <span class="badge badge-info">Upcoming</span>
                                            @elseif($show->status == 'ongoing')
                                                <span class="badge badge-success">Ongoing</span>
                                            @elseif($show->status == 'past')
                                                <span class="badge badge-secondary">Past</span>
                                            @elseif($show->status == 'cancelled')
                                                <span class="badge badge-danger">Cancelled</span>
                                            @endif
                                        </div>
                                        <div class="mt-1">
                                            @if(!$show->is_active)
                                                <span class="badge badge-dark">Inactive</span>
                                            @else
                                                <span class="badge badge-success">Active</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td><strong>{{ $show->formatted_price }}</strong></td>
                                    <td>
                                        @if($show->available_tickets)
                                            <small>
                                                <strong>Sold:</strong> {{ $show->sold_tickets ?? 0 }}/{{ $show->available_tickets }}<br>
                                                @if($show->sold_out)
                                                    <span class="badge badge-danger">Sold Out</span>
                                                @else
                                                    <span class="badge badge-success">Available</span>
                                                @endif
                                            </small>
                                        @else
                                            <span class="badge badge-info">Unlimited</span>
                                        @endif
                                    </td>
                                    {{-- NEW: ticketing mode column --}}
                                    <td>
                                        @php $mode = $show->ticketing_mode ?? 'general_admission'; @endphp
                                        @if($mode === 'reserved')
                                            <span class="badge badge-purple" style="background:#6f42c1;color:#fff;" title="seats.io Reserved Seating">
                                                <i class="fa fa-map-marker"></i> Reserved
                                            </span>
                                            @if($show->tickets_on_sale)
                                                <br><span class="badge badge-success mt-1">On Sale</span>
                                            @else
                                                <br><span class="badge badge-secondary mt-1">Off Sale</span>
                                            @endif
                                        @elseif($mode === 'mixed')
                                            <span class="badge" style="background:#17a2b8;color:#fff;" title="seats.io Mixed">
                                                <i class="fa fa-th"></i> Mixed
                                            </span>
                                            @if($show->tickets_on_sale)
                                                <br><span class="badge badge-success mt-1">On Sale</span>
                                            @else
                                                <br><span class="badge badge-secondary mt-1">Off Sale</span>
                                            @endif
                                        @elseif($mode === 'none')
                                            <span class="badge badge-secondary">
                                                <i class="fa fa-external-link"></i> External
                                            </span>
                                        @else
                                            <span class="badge badge-light" style="border:1px solid #ccc;">
                                                <i class="fa fa-ticket"></i> GA
                                            </span>
                                        @endif
                                    </td>
                                    <td class="last">
                                        <div class="btn-group">
                                            <a href="{{ route('show.edit', $show->id) }}" class="btn btn-info btn-sm" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{ route('shows.show', $show->id) }}" class="btn btn-primary btn-sm" title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <form action="{{ route('show.delete', $show->id) }}" method="POST" style="display: inline;"
                                                  onsubmit="return confirm('Are you sure you want to delete this show? This action cannot be undone.')">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center">
                                        <div class="py-4">
                                            <i class="fa fa-calendar-times-o fa-3x text-muted mb-3"></i>
                                            <h5>No shows found</h5>
                                            <p class="text-muted">
                                                @if(request()->anyFilled(['category', 'venue', 'status', 'is_active', 'ticketing_mode', 'search']))
                                                    Try adjusting your filters or <a href="{{ route('show.index') }}">clear all filters</a>
                                                @else
                                                    <a href="{{ route('show.create') }}" class="btn btn-primary">Create your first show</a>
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- Uncomment for pagination --}}
                        {{-- <div class="d-flex justify-content-center">
                            {{ $shows->appends(request()->query())->links() }}
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit filter selects on change
        document.querySelectorAll('#filterForm select').forEach(function(select) {
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });

        // Search on Enter key
        document.getElementById('search').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.form.submit();
            }
        });
    });
</script>
@endsection
