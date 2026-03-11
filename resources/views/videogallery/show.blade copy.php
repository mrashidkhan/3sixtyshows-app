{{-- resources/views/videogallery/show.blade.php --}}
@extends('layouts.master')

@section('title', $gallery->title . ' — ' . $year . ' — 3SixtyShows Video Gallery')
@section('meta_description', $gallery->description ?? 'Watch videos from ' . $gallery->title . ' at 3SixtyShows Dallas.')

@push('styles')
<style>
/* ── Hero ─────────────────────────────────────────────────── */
.vglr-hero {
    background: linear-gradient(135deg, #0a0a0a 0%, #00051a 50%, #0a0a0a 100%);
    padding: 72px 0 52px;
    position: relative;
    overflow: hidden;
}
.vglr-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 60% 60% at 50% 0%, rgba(200,16,46,0.18) 0%, transparent 70%);
    pointer-events: none;
}
.vglr-hero__breadcrumb {
    font-size: 0.78rem;
    color: rgba(255,255,255,0.38);
    letter-spacing: 0.07em;
    text-transform: uppercase;
    margin-bottom: 16px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.vglr-hero__breadcrumb a { color: #D4A017; text-decoration: none; }
.vglr-hero__breadcrumb a:hover { text-decoration: underline; }
.vglr-hero__title {
    font-family: 'Oswald', sans-serif;
    font-size: clamp(1.8rem, 4vw, 3rem);
    font-weight: 700;
    letter-spacing: 0.04em;
    color: #fff;
    text-transform: uppercase;
    line-height: 1.1;
}
.vglr-hero__title span { color: #C8102E; }
.vglr-hero__sub {
    color: rgba(255,255,255,0.5);
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 0.95rem;
    margin-top: 10px;
}

/* ── Section wrapper ──────────────────────────────────────── */
.vglr-section {
    background: #F5F5F5;
    padding: 48px 0 72px;
    min-height: 60vh;
}

/* ── Stats bar ────────────────────────────────────────────── */
.vglr-statsbar {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 12px; margin-bottom: 32px;
    padding-bottom: 20px; border-bottom: 1px solid #E5E7EB;
}
.vglr-statsbar__count {
    font-size: 13px; color: #6B7280;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.vglr-statsbar__count strong { color: #111827; font-weight: 700; }
.vglr-back-btn {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 600; color: #374151;
    text-decoration: none; border: 1px solid #E2E2E2;
    border-radius: 8px; padding: 7px 14px; background: #fff;
    transition: all .2s; font-family: var(--font-body, 'DM Sans', sans-serif);
    letter-spacing: .4px; text-transform: uppercase;
}
.vglr-back-btn:hover { border-color: #C8102E; color: #C8102E; text-decoration: none; }

/* ── Video grid ───────────────────────────────────────────── */
.vglr-video-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    align-items: start;
}

/* ── Video card ───────────────────────────────────────────── */
.vglr-video-card {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #E2E2E2;
    box-shadow: 0 1px 3px rgba(0,0,0,.07), 0 3px 12px rgba(0,0,0,.06);
    transition: box-shadow .22s ease, transform .22s ease;
    cursor: pointer;
}
.vglr-video-card:hover {
    box-shadow: 0 8px 32px rgba(0,0,0,.15);
    transform: translateY(-4px);
}

/* ── ① Dark top bar ───────────────────────────────────────── */
.vglr-topbar {
    display: flex; align-items: center; justify-content: space-between;
    background: #111; padding: 8px 13px; height: 38px;
    border-bottom: 2.5px solid rgba(255,255,255,0.70);
}
.vglr-topbar__label {
    font-size: 12.5px; font-weight: 700; color: #fff;
    letter-spacing: .3px; line-height: 1;
    font-family: var(--font-body, 'DM Sans', sans-serif);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    max-width: calc(100% - 28px);
}
.vglr-topbar__icon { color: rgba(255,255,255,.72); font-size: 15px; line-height: 1; flex-shrink: 0; }

/* ── ② Video thumbnail area ───────────────────────────────── */
.vglr-thumb-wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;     /* YouTube is 16:9 */
    background: #111;
    overflow: hidden;
    line-height: 0;
}
.vglr-thumb-wrap img {
    position: absolute;
    inset: 0;
    width: 100%; height: 100%;
    display: block;
    object-fit: cover;
    object-position: center center;
    transition: transform .38s ease;
}
.vglr-video-card:hover .vglr-thumb-wrap img { transform: scale(1.04); }

/* Play button */
.vglr-play-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}
.vglr-play-btn {
    width: 52px; height: 52px;
    border-radius: 50%;
    background: rgba(200,16,46,0.88);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 22px rgba(200,16,46,.55);
    transition: transform .22s ease;
}
.vglr-video-card:hover .vglr-play-btn { transform: scale(1.12); }
.vglr-play-btn i { color: #fff; font-size: 20px; margin-left: 3px; }

/* Hover scrim */
.vglr-thumb-hover {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(10,10,10,.72) 0%, transparent 60%);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding-bottom: 14px;
    opacity: 0;
    transition: opacity .26s ease;
    z-index: 3;
}
.vglr-video-card:hover .vglr-thumb-hover { opacity: 1; }
.vglr-thumb-hover__cta {
    background: linear-gradient(135deg, #C8102E 0%, #9e0b22 100%);
    color: #fff; font-size: 11px; font-weight: 700;
    letter-spacing: 1px; text-transform: uppercase;
    padding: 7px 18px; border-radius: 999px;
    display: inline-flex; align-items: center; gap: 6px;
    box-shadow: 0 4px 18px rgba(200,16,46,.50);
    font-family: var(--font-body, 'DM Sans', sans-serif);
}

/* No-thumbnail placeholder */
.vglr-thumb-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, #00051a 0%, #0d0020 100%);
}
.vglr-thumb-placeholder svg { opacity: 0.18; }

/* ── ③ Card body ──────────────────────────────────────────── */
.vglr-video-body {
    padding: 11px 13px 13px;
    background: #fff;
}
.vglr-video-body__num {
    display: block; font-size: 10.5px; font-weight: 600;
    color: #9CA3AF; text-transform: uppercase; letter-spacing: .8px;
    margin-bottom: 3px; line-height: 1;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.vglr-video-body__caption {
    font-size: 13px; font-weight: 600; color: #374151;
    line-height: 1.35;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
    font-family: var(--font-body, 'DM Sans', sans-serif);
    min-height: 1.5em;
}

/* ── Pagination ───────────────────────────────────────────── */
.vglr-pagination {
    display: flex; align-items: center; justify-content: center;
    gap: 6px; flex-wrap: wrap; margin-top: 56px; padding-bottom: 8px;
}
.vglr-pg-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 40px; height: 40px; border-radius: 50%;
    background: #fff; border: 1.5px solid #D8D2CA; color: #111827;
    font-size: 13px; text-decoration: none; transition: all .2s ease; flex-shrink: 0;
}
.vglr-pg-btn:hover { background: #C8102E; border-color: #C8102E; color: #fff; box-shadow: 0 4px 14px rgba(200,16,46,0.35); transform: translateY(-1px); }
.vglr-pg-btn--disabled { background: #F9F9F9; border-color: #E8E2DA; color: #C0B8B0; cursor: not-allowed; pointer-events: none; }
.vglr-pg-num {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 40px; height: 40px; padding: 0 6px; border-radius: 10px;
    background: #fff; border: 1.5px solid #D8D2CA; color: #374151;
    font-family: var(--font-body, 'DM Sans', sans-serif); font-size: 14px; font-weight: 600;
    text-decoration: none; transition: all .2s ease; flex-shrink: 0;
}
.vglr-pg-num:hover { background: rgba(200,16,46,.06); border-color: #C8102E; color: #C8102E; transform: translateY(-1px); }
.vglr-pg-num--active { background: linear-gradient(135deg, #C8102E 0%, #9e0b22 100%); border-color: #C8102E; color: #fff; box-shadow: 0 4px 14px rgba(200,16,46,.4); cursor: default; pointer-events: none; }
.vglr-pg-ellipsis { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 40px; color: #9CA3AF; font-size: 16px; font-weight: 700; letter-spacing: 1px; }

/* ── Sibling gallery strip ────────────────────────────────── */
.vglr-related { margin-top: 56px; }
.vglr-related__title {
    font-family: 'Oswald', sans-serif;
    font-size: 1rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: #111827; margin-bottom: 16px;
}
.vglr-related-scroll {
    display: flex; gap: 10px; overflow-x: auto;
    padding-bottom: 6px; scrollbar-width: thin;
}
.vglr-related-pill {
    display: inline-flex; align-items: center; gap: 8px;
    background: #fff; border: 1px solid #E2E2E2; border-radius: 8px;
    padding: 6px 12px 6px 6px; text-decoration: none; color: #374151;
    font-family: var(--font-body, 'DM Sans', sans-serif); font-size: 12px; font-weight: 600;
    white-space: nowrap; transition: border-color .2s, color .2s; flex-shrink: 0;
}
.vglr-related-pill:hover { border-color: #C8102E; color: #C8102E; text-decoration: none; }
.vglr-related-pill img {
    width: 32px; height: 32px; border-radius: 4px;
    object-fit: cover; flex-shrink: 0;
}

/* ── YouTube Lightbox ─────────────────────────────────────── */
#vglr-lightbox {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.92);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}
#vglr-lightbox.open { display: flex; }

#vglr-lb-inner {
    position: relative;
    width: 90vw;
    max-width: 960px;
    background: #000;
    border-radius: 8px;
    overflow: hidden;
}
#vglr-lb-iframe-wrap {
    position: relative;
    width: 100%;
    padding-top: 56.25%; /* 16:9 */
}
#vglr-lb-iframe {
    position: absolute;
    inset: 0;
    width: 100%; height: 100%;
    border: none;
}
#vglr-lb-caption {
    padding: 10px 16px;
    color: rgba(255,255,255,.7);
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 13px;
    background: #0d0d0d;
    min-height: 36px;
}
#vglr-lb-close {
    position: absolute;
    top: -40px; right: 0;
    background: none; border: none;
    color: #fff; font-size: 28px;
    cursor: pointer; line-height: 1;
    transition: color .2s;
}
#vglr-lb-close:hover { color: #C8102E; }

.vglr-lb-arrow {
    position: fixed;
    top: 50%; transform: translateY(-50%);
    background: rgba(200,16,46,.80);
    border: none; color: #fff;
    font-size: 32px; line-height: 1;
    width: 48px; height: 64px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; z-index: 10001;
    transition: background .2s;
}
.vglr-lb-arrow:hover { background: #C8102E; }
#vglr-lb-prev { left: 12px; border-radius: 0 6px 6px 0; }
#vglr-lb-next { right: 12px; border-radius: 6px 0 0 6px; }

#vglr-lb-counter {
    text-align: center; padding: 6px;
    color: rgba(255,255,255,.4);
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 12px;
    background: #0d0d0d;
}

/* ── Empty ────────────────────────────────────────────────── */
.vglr-empty {
    grid-column: 1 / -1; text-align: center; padding: 60px 20px;
    background: #FAFAF9; border: 1.5px dashed #E5E7EB; border-radius: 14px;
}

/* ── Responsive ───────────────────────────────────────────── */
@media (max-width: 991px) { .vglr-video-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 767px) { .vglr-video-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; } }
@media (max-width: 480px) { .vglr-video-grid { grid-template-columns: 1fr; gap: 16px; } }
@media (max-width: 576px) { #vglr-lb-prev { left: 4px; } #vglr-lb-next { right: 4px; } }
@media (max-width: 768px) { .vglr-pagination { gap: 5px; margin-top: 40px; } .vglr-pg-btn, .vglr-pg-num { width: 36px; height: 36px; font-size: 13px; } }
</style>
@endpush

@section('content')

<section class="vglr-hero">
    <div class="container">
        <div class="vglr-hero__breadcrumb">
            <a href="{{ route('index') }}">Home</a>
            <span class="mx-2">›</span>
            <a href="{{ route('video-gallery.index') }}">Video Gallery</a>
            <span class="mx-2">›</span>
            <a href="{{ route('video-gallery.year', $year) }}">{{ $year }}</a>
            <span class="mx-2">›</span>
            <span>{{ Str::limit($gallery->title, 40) }}</span>
        </div>
        <h1 class="vglr-hero__title">{{ $gallery->title }}</h1>
        @if($gallery->description)
        <p class="vglr-hero__sub">{{ $gallery->description }}</p>
        @endif
    </div>
</section>

<section class="vglr-section">
    <div class="container">

        {{-- Stats bar --}}
        <div class="vglr-statsbar">
            <span class="vglr-statsbar__count">
                <strong>{{ $videos->total() }}</strong> {{ Str::plural('Video', $videos->total()) }}
                @if($videos->hasPages())
                    &nbsp;·&nbsp; Page {{ $videos->currentPage() }} of {{ $videos->lastPage() }}
                @endif
            </span>
            <a href="{{ route('video-gallery.year', $year) }}" class="vglr-back-btn">
                <i class="fas fa-arrow-left"></i> All {{ $year }} Galleries
            </a>
        </div>

        {{-- Video grid --}}
        <div class="vglr-video-grid" id="vglr-video-grid">

            @forelse($videos as $index => $video)
            @php
                $globalIdx  = $videos->firstItem() + $index - 1;
                $youtubeId  = \App\Http\Controllers\VideoGalleryPageController::youtubeId($video->youtubelink);
                $thumbUrl   = $youtubeId
                                ? "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg"
                                : null;
            @endphp
            <div class="vglr-video-card"
                 data-index="{{ $globalIdx }}"
                 data-youtube-id="{{ $youtubeId ?? '' }}"
                 data-caption="{{ $video->description ?? '' }}"
                 onclick="vglrOpenLightbox({{ $globalIdx }})">

                {{-- ① Dark top bar --}}
                <div class="vglr-topbar">
                    <span class="vglr-topbar__label">{{ $gallery->title }}</span>
                    <span class="vglr-topbar__icon"><i class="fab fa-youtube"></i></span>
                </div>

                {{-- ② Thumbnail --}}
                <div class="vglr-thumb-wrap">
                    @if($thumbUrl)
                        <img src="{{ $thumbUrl }}" alt="{{ $video->description ?? $gallery->title }}" loading="lazy">
                    @else
                        <div class="vglr-thumb-placeholder">
                            <svg width="80" height="60" viewBox="0 0 24 24" fill="white">
                                <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="vglr-play-overlay">
                        <div class="vglr-play-btn"><i class="fas fa-play"></i></div>
                    </div>
                    <div class="vglr-thumb-hover">
                        <span class="vglr-thumb-hover__cta">
                            <i class="fas fa-play"></i> Watch Video
                        </span>
                    </div>
                </div>

                {{-- ③ Body --}}
                <div class="vglr-video-body">
                    <span class="vglr-video-body__num">Video {{ $globalIdx + 1 }}</span>
                    <div class="vglr-video-body__caption">
                        {{ $video->description ?: $gallery->title }}
                    </div>
                </div>

            </div>
            @empty
            <div class="vglr-empty">
                <p>No videos in this gallery yet. Check back soon!</p>
            </div>
            @endforelse

        </div>

        {{-- Pagination --}}
        @if($videos->hasPages())
        <nav class="vglr-pagination" aria-label="Videos pagination">

            @if($videos->onFirstPage())
                <span class="vglr-pg-btn vglr-pg-btn--disabled" aria-disabled="true"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $videos->previousPageUrl() }}" class="vglr-pg-btn" aria-label="Previous page"><i class="fas fa-chevron-left"></i></a>
            @endif

            @foreach($videos->getUrlRange(1, $videos->lastPage()) as $page => $url)
                @if($page == $videos->currentPage())
                    <span class="vglr-pg-num vglr-pg-num--active" aria-current="page">{{ $page }}</span>
                @elseif($page == 1 || $page == $videos->lastPage() || abs($page - $videos->currentPage()) <= 2)
                    <a href="{{ $url }}" class="vglr-pg-num">{{ $page }}</a>
                @elseif(abs($page - $videos->currentPage()) == 3)
                    <span class="vglr-pg-ellipsis">&hellip;</span>
                @endif
            @endforeach

            @if($videos->hasMorePages())
                <a href="{{ $videos->nextPageUrl() }}" class="vglr-pg-btn" aria-label="Next page"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="vglr-pg-btn vglr-pg-btn--disabled" aria-disabled="true"><i class="fas fa-chevron-right"></i></span>
            @endif

        </nav>
        @endif

        {{-- Sibling galleries --}}
        @if($siblingGalleries->isNotEmpty())
        <div class="vglr-related">
            <div class="vglr-related__title">More from {{ $year }}</div>
            <div class="vglr-related-scroll">
                @foreach($siblingGalleries as $sibling)
                <a href="{{ route('video-gallery.show', [$year, $sibling->id]) }}" class="vglr-related-pill">
                    @if($sibling->thumbnail)
                    <img src="{{ str_starts_with($sibling->thumbnail, 'http') ? $sibling->thumbnail : asset('storage/' . $sibling->thumbnail) }}"
                         alt="{{ $sibling->title }}">
                    @endif
                    <span>{{ $sibling->title }}</span>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</section>

{{-- ── YouTube Lightbox ────────────────────────────────────── --}}
<div id="vglr-lightbox" role="dialog" aria-modal="true" aria-label="Video player">
    <button class="vglr-lb-arrow" id="vglr-lb-prev" onclick="vglrMoveLightbox(-1)" aria-label="Previous">&#8249;</button>
    <button class="vglr-lb-arrow" id="vglr-lb-next" onclick="vglrMoveLightbox(1)" aria-label="Next">&#8250;</button>
    <div id="vglr-lb-inner">
        <button id="vglr-lb-close" onclick="vglrCloseLightbox()" aria-label="Close">&#x2715;</button>
        <div id="vglr-lb-iframe-wrap">
            <iframe id="vglr-lb-iframe"
                    src=""
                    allow="autoplay; encrypted-media; fullscreen"
                    allowfullscreen>
            </iframe>
        </div>
        <div id="vglr-lb-caption"></div>
        <div id="vglr-lb-counter"></div>
    </div>
</div>

<script>
(function () {
    var lb      = document.getElementById('vglr-lightbox');
    var iframe  = document.getElementById('vglr-lb-iframe');
    var cap     = document.getElementById('vglr-lb-caption');
    var counter = document.getElementById('vglr-lb-counter');
    var btnPrev = document.getElementById('vglr-lb-prev');
    var btnNext = document.getElementById('vglr-lb-next');

    var videos  = [];
    var current = 0;

    // Build video list from card data attributes
    var cards = document.querySelectorAll('#vglr-video-grid .vglr-video-card');
    cards.forEach(function (el) {
        var ytId = el.dataset.youtubeId;
        if (ytId) {
            videos.push({
                index:     parseInt(el.dataset.index, 10),
                youtubeId: ytId,
                caption:   el.dataset.caption || ''
            });
        }
    });

    window.vglrOpenLightbox = function (globalIndex) {
        if (!videos.length) return;
        var local = videos.findIndex(function(v){ return v.index === globalIndex; });
        current = local >= 0 ? local : 0;
        lb.classList.add('open');
        document.body.style.overflow = 'hidden';
        render();
    };

    window.vglrCloseLightbox = function () {
        lb.classList.remove('open');
        document.body.style.overflow = '';
        // Stop video by clearing src
        iframe.src = '';
    };

    window.vglrMoveLightbox = function (dir) {
        if (!videos.length) return;
        // Stop current video before switching
        iframe.src = '';
        current = (current + dir + videos.length) % videos.length;
        render();
    };

    function render() {
        var v = videos[current];
        if (!v) return;
        // Autoplay via YouTube embed parameter
        iframe.src = 'https://www.youtube.com/embed/' + v.youtubeId + '?autoplay=1&rel=0';
        cap.textContent     = v.caption;
        counter.textContent = (current + 1) + ' / ' + videos.length;
        btnPrev.style.display = videos.length > 1 ? '' : 'none';
        btnNext.style.display = videos.length > 1 ? '' : 'none';
    }

    // Close on backdrop click
    lb.addEventListener('click', function (e) {
        if (e.target === lb) vglrCloseLightbox();
    });

    // Keyboard navigation
    document.addEventListener('keydown', function (e) {
        if (!lb.classList.contains('open')) return;
        if (e.key === 'Escape')     vglrCloseLightbox();
        if (e.key === 'ArrowLeft')  vglrMoveLightbox(-1);
        if (e.key === 'ArrowRight') vglrMoveLightbox(1);
    });
}());
</script>

@endsection

@push('scripts')@endpush
