@extends('admin.layout.layout')

@section('content')
<div class="row">
    <div class="col-md-12">

        <h2>Video Galleries</h2>

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <a href="{{ route('videogallery.create') }}" class="btn btn-success">Add New Video Gallery</a>

        <form action="{{ route('videogallery.list') }}" method="GET" class="form-inline mt-2 mb-2">
            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary ml-2">Search</button>
        </form>

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Video Type</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($galleries as $gallery)
                <tr>
                    <td>{{ $gallery->id }}</td>
                    <td>{{ $gallery->title }}</td>
                    <td>{{ ucfirst($gallery->video_type) }}</td>
                    <td>
                        @if($gallery->is_featured)
                            <span class="badge badge-success">Yes</span>
                        @else
                            <span class="badge badge-secondary">No</span>
                        @endif
                    </td>
                    <td>
                        @if($gallery->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('videogallery.show', $gallery->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('videogallery.edit', $gallery->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('videogallery.delete', $gallery->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this gallery?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No video galleries found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $galleries->links() }}

    </div>
</div>
@endsection
