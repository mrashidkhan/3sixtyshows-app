@extends('admin.layout.layout')

@section('content')
<style>
/* ══════════════════════════════════════════════════════
   SEARCH BAR
   ══════════════════════════════════════════════════════ */
.vid-search-bar {
    background: #f8f9fa;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 18px 20px 14px;
    margin-bottom: 20px;
}
.vid-search-row {
    display: flex;
    gap: 14px;
    align-items: flex-end;
    flex-wrap: wrap;
}
.vid-search-group {
    display: flex;
    flex-direction: column;
    min-width: 180px;
    flex: 1;
}
.vid-search-group--wide { flex: 2; }
.vid-search-group--btn  { flex: 0; min-width: 120px; }
.vid-search-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #555;
    margin-bottom: 5px;
}
.vid-search-input {
    height: 36px;
    border: 1.5px solid #D8D2CA;
    border-radius: 7px;
    padding: 0 10px;
    font-size: 13.5px;
    color: #333;
    background: #fff;
    outline: none;
    width: 100%;
    transition: border-color .18s;
}
.vid-search-input:focus { border-color: #C8102E; }
.vid-btn-row {
    display: flex;
    gap: 8px;
    align-items: center;
}
.vid-filter-info {
    margin-top: 10px;
    font-size: 12.5px;
    color: #555;
    padding: 6px 10px;
    background: #fff3cd;
    border-left: 3px solid #D4A017;
    border-radius: 0 6px 6px 0;
}
.vid-badge {
    display: inline-block;
    background: #C8102E;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    margin-left: 2px;
}
/* YouTube link cell */
.vid-yt-link {
    font-size: 12px;
    color: #374151;
    text-decoration: none;
    word-break: break-all;
}
.vid-yt-link:hover { color: #C8102E; text-decoration: underline; }

/* ══════════════════════════════════════════════════════
   PAGINATION
   ══════════════════════════════════════════════════════ */
.vid-pg-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 20px;
    padding-top: 14px;
    border-top: 1px solid #f0f0f0;
}
.vid-pg-count {
    font-size: 13px;
    color: #6B7280;
    margin: 0;
}
.vid-pagination {
    display: flex;
    align-items: center;
    gap: 5px;
    flex-wrap: wrap;
}
.vid-pg-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #fff;
    border: 1.5px solid #D8D2CA;
    color: #111827;
    font-size: 12px;
    text-decoration: none;
    transition: all .2s ease;
    flex-shrink: 0;
}
.vid-pg-btn:hover {
    background: #C8102E;
    border-color: #C8102E;
    color: #fff;
    box-shadow: 0 4px 12px rgba(200,16,46,.35);
    transform: translateY(-1px);
    text-decoration: none;
}
.vid-pg-btn--disabled {
    background: #f9f9f6;
    border-color: #e8e2da;
    color: #c0b8b0;
    cursor: not-allowed;
    pointer-events: none;
}
.vid-pg-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 6px;
    border-radius: 8px;
    background: #fff;
    border: 1.5px solid #D8D2CA;
    color: #374151;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all .2s ease;
    flex-shrink: 0;
}
.vid-pg-num:hover {
    background: rgba(200,16,46,.06);
    border-color: #C8102E;
    color: #C8102E;
    transform: translateY(-1px);
    text-decoration: none;
}
.vid-pg-num--active {
    background: linear-gradient(135deg, #C8102E 0%, #9e0b22 100%);
    border-color: #C8102E;
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(200,16,46,.4);
    cursor: default;
    pointer-events: none;
}
.vid-pg-ellipsis {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 36px;
    color: #9CA3AF;
    font-size: 15px;
    font-weight: 700;
    letter-spacing: 1px;
}
</style>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Videos in Gallery Management</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                {{-- ── Search / Filter Bar ───────────────────────────────────── --}}
                <form method="GET" action="{{ route('videosingallery.list') }}" class="vid-search-bar">
                    <div class="vid-search-row">

                        {{-- Gallery dropdown --}}
                        <div class="vid-search-group">
                            <label class="vid-search-label">
                                <i class="fa fa-film"></i> Filter by Gallery
                            </label>
                            <select name="gallery_id" class="vid-search-input" onchange="this.form.submit()">
                                <option value="">— All Galleries —</option>
                                @foreach($galleries as $gallery)
                                    <option value="{{ $gallery->id }}"
                                        {{ $galleryFilter == $gallery->id ? 'selected' : '' }}>
                                        {{ $gallery->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Text search --}}
                        <div class="vid-search-group vid-search-group--wide">
                            <label class="vid-search-label">
                                <i class="fa fa-search"></i> Search Description / YouTube Link
                            </label>
                            <input type="text"
                                   name="search"
                                   value="{{ $search }}"
                                   class="vid-search-input"
                                   placeholder="Search description or YouTube link…">
                        </div>

                        {{-- Buttons --}}
                        <div class="vid-search-group vid-search-group--btn">
                            <label class="vid-search-label">&nbsp;</label>
                            <div class="vid-btn-row">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i> Search
                                </button>
                                @if($search || $galleryFilter)
                                <a href="{{ route('videosingallery.list') }}" class="btn btn-default">
                                    <i class="fa fa-times"></i> Clear
                                </a>
                                @endif
                            </div>
                        </div>

                    </div>

                    {{-- Active filter info --}}
                    @if($search || $galleryFilter)
                    <div class="vid-filter-info">
                        <i class="fa fa-info-circle"></i>
                        Showing <strong>{{ $videos->total() }}</strong> result(s)
                        @if($galleryFilter)
                            &mdash; Gallery: <span class="vid-badge">{{ $galleries->find($galleryFilter)?->title ?? $galleryFilter }}</span>
                        @endif
                        @if($search)
                            &mdash; Keyword: <span class="vid-badge">{{ $search }}</span>
                        @endif
                    </div>
                    @endif
                </form>
                {{-- ── /Search Bar ──────────────────────────────────────────── --}}

                <div class="row mb-3 mt-3">
                    <div class="col-md-12">
                        <a href="{{ route('videosingallery.create') }}" class="btn btn-success">
                            <i class="fa fa-plus"></i> Add New Video
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Thumbnail</th>
                                <th>Video Gallery</th>
                                <th>Description</th>
                                <th>Youtube Link</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($videos as $video)
                                <tr>
                                    <td>{{ $video->id }}</td>
                                    <td>
                                        @if($video->image)
                                            <img src="{{ asset($video->image) }}"
                                                 alt="Video thumbnail"
                                                 style="width:80px;height:60px;object-fit:cover;border-radius:4px;border:1px solid #ddd;">
                                        @else
                                            <div style="width:80px;height:60px;background:#f8f9fa;border:1px solid #ddd;border-radius:4px;display:flex;align-items:center;justify-content:center;">
                                                <small class="text-muted">No Image</small>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $video->videoGallery->title ?? 'N/A' }}</strong>
                                        @if($video->videoGallery)
                                            <br><small class="text-muted">ID: {{ $video->video_gallery_id }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($video->description)
                                            {{ Str::limit($video->description, 50) }}
                                        @else
                                            <span class="text-muted">No description</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($video->youtubelink)
                                            <a href="{{ $video->youtubelink }}" target="_blank"
                                               class="vid-yt-link" title="{{ $video->youtubelink }}">
                                                <i class="fa fa-youtube-play" style="color:#C8102E;margin-right:4px;"></i>
                                                {{ Str::limit($video->youtubelink, 38) }}
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $video->created_at->format('M d, Y') }}</small>
                                        <br>
                                        <small class="text-muted">{{ $video->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('videosingallery.show', $video->id) }}"
                                               class="btn btn-info btn-sm" title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('videosingallery.edit', $video->id) }}"
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('videosingallery.delete', $video->id) }}"
                                                  method="POST" style="display:inline;"
                                                  onsubmit="return confirm('Delete this video? This cannot be undone.');">
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
                                    <td colspan="7" class="text-center">
                                        <div class="py-4">
                                            <i class="fa fa-video-camera fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No videos found</h5>
                                            <p class="text-muted">
                                                @if($search || $galleryFilter)
                                                    No videos match your search. <a href="{{ route('videosingallery.list') }}">Clear filters</a>
                                                @else
                                                    Get started by adding your first video to the gallery.
                                                @endif
                                            </p>
                                            <a href="{{ route('videosingallery.create') }}" class="btn btn-primary">
                                                <i class="fa fa-plus"></i> Add First Video
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ── Pagination ──────────────────────────────────────────── --}}
                @if($videos->hasPages())
                <div class="vid-pg-wrap">
                    <p class="vid-pg-count">
                        Showing <strong>{{ $videos->firstItem() }}</strong> to
                        <strong>{{ $videos->lastItem() }}</strong> of
                        <strong>{{ $videos->total() }}</strong> videos
                    </p>
                    <nav class="vid-pagination" aria-label="Videos pagination">

                        {{-- Prev --}}
                        @if($videos->onFirstPage())
                            <span class="vid-pg-btn vid-pg-btn--disabled"><i class="fa fa-chevron-left"></i></span>
                        @else
                            <a href="{{ $videos->previousPageUrl() }}" class="vid-pg-btn"><i class="fa fa-chevron-left"></i></a>
                        @endif

                        {{-- Page numbers --}}
                        @foreach($videos->getUrlRange(1, $videos->lastPage()) as $page => $url)
                            @if($page == $videos->currentPage())
                                <span class="vid-pg-num vid-pg-num--active">{{ $page }}</span>
                            @elseif($page == 1 || $page == $videos->lastPage() || abs($page - $videos->currentPage()) <= 2)
                                <a href="{{ $url }}" class="vid-pg-num">{{ $page }}</a>
                            @elseif(abs($page - $videos->currentPage()) == 3)
                                <span class="vid-pg-ellipsis">&hellip;</span>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if($videos->hasMorePages())
                            <a href="{{ $videos->nextPageUrl() }}" class="vid-pg-btn"><i class="fa fa-chevron-right"></i></a>
                        @else
                            <span class="vid-pg-btn vid-pg-btn--disabled"><i class="fa fa-chevron-right"></i></span>
                        @endif

                    </nav>
                </div>
                @endif
                {{-- ── /Pagination ─────────────────────────────────────────── --}}

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                if (alert.classList.contains('show')) {
                    alert.classList.remove('show');
                    setTimeout(function() { alert.remove(); }, 150);
                }
            }, 5000);
        });
    });
</script>
@endsection
