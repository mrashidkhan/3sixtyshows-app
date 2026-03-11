{{--
    resources/views/videogallery/show.blade.php
    Rendered by: VideoGalleryPageController::show()
    Variables:   $gallery (VideoGallery), $videos (Paginator of VideosinGallery),
                 $year (int), $siblingGalleries (Collection)
--}}
@extends('layouts.master')

@section('title', $gallery->title . ' — ' . $year . ' — 3SixtyShows Video Gallery')
@section('meta_description', $gallery->description ?? 'Watch videos from ' . $gallery->title . ' at 3SixtyShows Dallas.')

<style>
/* ─── Hero ───────────────────────────────────────────────────────── */
.vshow-hero {
    background: linear-gradient(135deg, #0a0a0a 0%, #00051a 50%, #0a0a0a 100%);
    padding: 56px 0 40px;
    position: relative;
    overflow: hidden;
}
.vshow-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 60% 60% at 50% 0%,
                rgba(200,16,46,.18) 0%, transparent 70%);
    pointer-events: none;
}
.vshow-breadcrumb {
    font-size: .78rem;
    color: rgba(255,255,255,.38);
    letter-spacing: .07em;
    text-transform: uppercase;
    margin-bottom: 14px;
    font-family: var(--font-body,'DM Sans',sans-serif);
}
.vshow-breadcrumb a { color: #D4A017; text-decoration: none; }
.vshow-breadcrumb a:hover { text-decoration: underline; }
.vshow-hero__title {
    font-family: 'Oswald', sans-serif;
    font-size: clamp(1.6rem,4vw,2.8rem);
    font-weight: 700;
    letter-spacing: .04em;
    color: #fff;
    text-transform: uppercase;
    line-height: 1.1;
    margin: 0 0 8px;
}
.vshow-hero__sub {
    color: rgba(255,255,255,.5);
    font-family: var(--font-body,'DM Sans',sans-serif);
    font-size: .95rem;
}

/* ─── Section ────────────────────────────────────────────────────── */
.vshow-section {
    background: #F5F5F5;
    padding: 44px 0 72px;
    min-height: 60vh;
}

/* ─── Stats bar ──────────────────────────────────────────────────── */
.vshow-statsbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 28px;
    padding-bottom: 18px;
    border-bottom: 1px solid #E5E7EB;
}
.vshow-statsbar__count {
    font-size: 13px;
    color: #6B7280;
    font-family: var(--font-body,'DM Sans',sans-serif);
}
.vshow-statsbar__count strong { color: #111827; font-weight: 700; }
.vshow-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #374151;
    text-decoration: none;
    border: 1px solid #E2E2E2;
    border-radius: 8px;
    padding: 7px 14px;
    background: #fff;
    transition: all .2s;
    font-family: var(--font-body,'DM Sans',sans-serif);
    letter-spacing: .4px;
    text-transform: uppercase;
}
.vshow-back-btn:hover { border-color: #C8102E; color: #C8102E; text-decoration: none; }

/* ─── Video grid ─────────────────────────────────────────────────── */
.vshow-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    align-items: start;
}
@media (max-width: 991px) { .vshow-grid { grid-template-columns: repeat(3,1fr); } }
@media (max-width: 767px) { .vshow-grid { grid-template-columns: repeat(2,1fr); gap: 14px; } }
@media (max-width: 480px) { .vshow-grid { grid-template-columns: 1fr; gap: 16px; } }

/* ─── Card ───────────────────────────────────────────────────────── */
.vshow-card {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #E2E2E2;
    box-shadow: 0 1px 3px rgba(0,0,0,.07), 0 3px 12px rgba(0,0,0,.06);
    transition: box-shadow .22s ease, transform .22s ease;
    cursor: pointer;
}
.vshow-card:hover {
    box-shadow: 0 8px 32px rgba(0,0,0,.15);
    transform: translateY(-4px);
}
/* Disable hover lift once a video is playing inside */
.vshow-card.is-playing {
    transform: none !important;
    box-shadow: 0 0 0 3px #C8102E, 0 8px 28px rgba(200,16,46,.30) !important;
    cursor: default;
}

/* ─── Top bar ────────────────────────────────────────────────────── */
.vshow-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #111;
    padding: 8px 13px;
    height: 38px;
    border-bottom: 2.5px solid rgba(255,255,255,.70);
}
.vshow-topbar__label {
    font-size: 12px;
    font-weight: 700;
    color: #fff;
    letter-spacing: .3px;
    line-height: 1;
    font-family: var(--font-body,'DM Sans',sans-serif);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: calc(100% - 26px);
}
.vshow-topbar__icon { color: rgba(255,255,255,.72); font-size: 15px; flex-shrink: 0; }

/* ─── Thumbnail area ─────────────────────────────────────────────── */
.vshow-thumb {
    position: relative;         /* keeps iframe children in place         */
    width: 100%;
    aspect-ratio: 16 / 9;       /* YouTube native ratio                    */
    background: #111;
    overflow: hidden;
    cursor: pointer;
}
/* Thumbnail image fills the box */
.vshow-thumb img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    transition: transform .38s ease;
}
.vshow-card:hover .vshow-thumb img { transform: scale(1.04); }

/* Play-button overlay on top of thumbnail */
.vshow-play-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
    pointer-events: none;       /* overlay doesn't eat click events        */
}
.vshow-play-btn {
    width: 54px;
    height: 54px;
    border-radius: 50%;
    background: rgba(200,16,46,.88);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 22px rgba(200,16,46,.55);
    transition: transform .22s ease;
}
.vshow-card:hover .vshow-play-btn { transform: scale(1.12); }
.vshow-play-btn i { color: #fff; font-size: 22px; margin-left: 3px; }

/* Hover scrim with CTA text */
.vshow-thumb-hover {
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
    pointer-events: none;
}
.vshow-card:hover .vshow-thumb-hover { opacity: 1; }
.vshow-thumb-cta {
    background: linear-gradient(135deg, #C8102E 0%, #9e0b22 100%);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 7px 18px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 4px 18px rgba(200,16,46,.50);
    font-family: var(--font-body,'DM Sans',sans-serif);
}

/* Placeholder when no thumbnail */
.vshow-thumb-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, #00051a 0%, #0d0020 100%);
}
.vshow-thumb-placeholder svg { opacity: .18; }

/* ─── Iframe (injected by JS) ────────────────────────────────────── */
/* Fills the thumbnail container completely — no default 300×150px browser size */
.vshow-thumb iframe {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    border: 0;
    display: block;
    z-index: 10;
}
/* While a video is playing, disable pointer events on the overlay elements
   so they don't sit on top of the iframe and intercept clicks */
.vshow-card.is-playing .vshow-play-overlay,
.vshow-card.is-playing .vshow-thumb-hover { display: none; }
.vshow-card.is-playing .vshow-thumb { cursor: default; }

/* ─── Card body ──────────────────────────────────────────────────── */
.vshow-body {
    padding: 10px 13px 13px;
    background: #fff;
}
.vshow-body__num {
    display: block;
    font-size: 10.5px;
    font-weight: 600;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: .8px;
    margin-bottom: 3px;
    font-family: var(--font-body,'DM Sans',sans-serif);
}
.vshow-body__caption {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-family: var(--font-body,'DM Sans',sans-serif);
    min-height: 1.5em;
}

/* ─── Empty state ────────────────────────────────────────────────── */
.vshow-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    background: #FAFAF9;
    border: 1.5px dashed #E5E7EB;
    border-radius: 14px;
    font-family: var(--font-body,'DM Sans',sans-serif);
    color: #6B7280;
}

/* ─── Pagination ─────────────────────────────────────────────────── */
.vshow-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    flex-wrap: wrap;
    margin-top: 48px;
}
.vshow-pg-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 40px; height: 40px; border-radius: 50%;
    background: #fff; border: 1.5px solid #D8D2CA; color: #374151;
    text-decoration: none; transition: all .2s; flex-shrink: 0; font-size: 13px;
}
.vshow-pg-btn:hover { background: #C8102E; border-color: #C8102E; color: #fff; }
.vshow-pg-btn--disabled { background: #F9F9F9; border-color: #E8E2DA; color: #C0B8B0; pointer-events: none; }
.vshow-pg-num {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 40px; height: 40px; padding: 0 6px; border-radius: 10px;
    background: #fff; border: 1.5px solid #D8D2CA; color: #374151;
    font-family: var(--font-body,'DM Sans',sans-serif); font-size: 14px; font-weight: 600;
    text-decoration: none; transition: all .2s;
}
.vshow-pg-num:hover { background: rgba(200,16,46,.06); border-color: #C8102E; color: #C8102E; }
.vshow-pg-num--active { background: linear-gradient(135deg,#C8102E 0%,#9e0b22 100%); border-color: #C8102E; color: #fff; pointer-events: none; }
.vshow-pg-ellipsis { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 40px; color: #9CA3AF; font-size: 16px; font-weight: 700; }

/* ─── Sibling strip ──────────────────────────────────────────────── */
.vshow-related { margin-top: 52px; }
.vshow-related__title {
    font-family: 'Oswald', sans-serif;
    font-size: 1rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: #111827; margin-bottom: 14px;
}
.vshow-related-scroll { display: flex; gap: 10px; overflow-x: auto; padding-bottom: 6px; scrollbar-width: thin; }
.vshow-related-pill {
    display: inline-flex; align-items: center; gap: 8px;
    background: #fff; border: 1px solid #E2E2E2; border-radius: 8px;
    padding: 6px 12px 6px 6px; text-decoration: none; color: #374151;
    font-family: var(--font-body,'DM Sans',sans-serif); font-size: 12px; font-weight: 600;
    white-space: nowrap; transition: border-color .2s, color .2s; flex-shrink: 0;
}
.vshow-related-pill:hover { border-color: #C8102E; color: #C8102E; text-decoration: none; }
.vshow-related-pill img { width: 32px; height: 32px; border-radius: 4px; object-fit: cover; }
</style>

@section('content')

{{-- ── Hero ──────────────────────────────────────────────────────── --}}
<section class="vshow-hero">
    <div class="container">
        <div class="vshow-breadcrumb">
            <a href="{{ route('index') }}">Home</a>
            <span class="mx-2">›</span>
            <a href="{{ route('video-gallery.index') }}">Video Gallery</a>
            <span class="mx-2">›</span>
            <a href="{{ route('video-gallery.year', $year) }}">{{ $year }}</a>
            <span class="mx-2">›</span>
            <span>{{ Str::limit($gallery->title, 40) }}</span>
        </div>
        <h1 class="vshow-hero__title">{{ $gallery->title }}</h1>
        @if($gallery->description)
        <p class="vshow-hero__sub">{{ $gallery->description }}</p>
        @endif
    </div>
</section>

{{-- ── Main content ────────────────────────────────────────────────── --}}
<section class="vshow-section">
    <div class="container">

        {{-- Stats bar --}}
        <div class="vshow-statsbar">
            <span class="vshow-statsbar__count">
                @php
                    $total = $videos->total() > 0 ? $videos->total()
                           : (!empty($gallery->video_url) ? 1 : 0);
                @endphp
                <strong>{{ $total }}</strong> {{ Str::plural('Video', $total) }}
                @if($videos->hasPages())
                    &nbsp;·&nbsp; Page {{ $videos->currentPage() }} of {{ $videos->lastPage() }}
                @endif
            </span>
            <a href="{{ route('video-gallery.year', $year) }}" class="vshow-back-btn">
                <i class="fas fa-arrow-left"></i> All {{ $year }} Galleries
            </a>
        </div>

        {{-- Video grid --}}
        <div class="vshow-grid" id="vshow-grid">

            {{-- ── Virtual row: if NO VideosinGallery rows exist but the
                   VideoGallery itself has a video_url stored at parent level ── --}}
            @php
                $videoRows = $videos->getCollection();
                if ($videoRows->isEmpty() && !empty($gallery->video_url)) {
                    $virtual              = new \stdClass();
                    $virtual->youtubelink = $gallery->video_url;
                    $virtual->description = $gallery->description ?? $gallery->title;
                    $videoRows            = collect([$virtual]);
                }
            @endphp

            @forelse($videoRows as $index => $video)
            @php
                $rawLink   = $video->youtubelink ?? '';
                $youtubeId = null;
                if ($rawLink) {
                    preg_match(
                        '/(?:youtube(?:-nocookie)?\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/|live\/)|youtu\.be\/)([A-Za-z0-9_\-]{11})/i',
                        $rawLink, $m
                    );
                    $youtubeId = $m[1] ?? null;
                }
                $thumbUrl = $youtubeId
                    ? "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg"
                    : null;
            @endphp

            @if($youtubeId)
            <div class="vshow-card"
                 data-youtube-id="{{ $youtubeId }}"
                 data-caption="{{ $video->description ?? '' }}">

                {{-- Dark top bar --}}
                <div class="vshow-topbar">
                    <span class="vshow-topbar__label">{{ $gallery->title }}</span>
                    <span class="vshow-topbar__icon"><i class="fab fa-youtube"></i></span>
                </div>

                {{-- Thumbnail — click triggers JS playVideo() --}}
                <div class="vshow-thumb" onclick="vsPlayVideo(this)">
                    @if($thumbUrl)
                        <img src="{{ $thumbUrl }}"
                             alt="{{ $video->description ?? $gallery->title }}"
                             loading="lazy">
                    @else
                        <div class="vshow-thumb-placeholder">
                            <svg width="70" height="52" viewBox="0 0 24 24" fill="white">
                                <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/>
                            </svg>
                        </div>
                    @endif

                    <div class="vshow-play-overlay">
                        <div class="vshow-play-btn">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>

                    <div class="vshow-thumb-hover">
                        <span class="vshow-thumb-cta">
                            <i class="fas fa-play"></i> Watch Video
                        </span>
                    </div>
                </div>

                {{-- Caption --}}
                <div class="vshow-body">
                    <span class="vshow-body__num">Video {{ $index + 1 }}</span>
                    <div class="vshow-body__caption">
                        {{ $video->description ?: $gallery->title }}
                    </div>
                </div>

            </div>
            @endif

            @empty
            <div class="vshow-empty">
                <p>No videos in this gallery yet. Check back soon!</p>
            </div>
            @endforelse

        </div>

        {{-- Pagination --}}
        @if($videos->hasPages())
        <nav class="vshow-pagination" aria-label="Videos pagination">
            @if($videos->onFirstPage())
                <span class="vshow-pg-btn vshow-pg-btn--disabled"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $videos->previousPageUrl() }}" class="vshow-pg-btn"><i class="fas fa-chevron-left"></i></a>
            @endif

            @foreach($videos->getUrlRange(1, $videos->lastPage()) as $page => $url)
                @if($page == $videos->currentPage())
                    <span class="vshow-pg-num vshow-pg-num--active">{{ $page }}</span>
                @elseif($page == 1 || $page == $videos->lastPage() || abs($page - $videos->currentPage()) <= 2)
                    <a href="{{ $url }}" class="vshow-pg-num">{{ $page }}</a>
                @elseif(abs($page - $videos->currentPage()) == 3)
                    <span class="vshow-pg-ellipsis">&hellip;</span>
                @endif
            @endforeach

            @if($videos->hasMorePages())
                <a href="{{ $videos->nextPageUrl() }}" class="vshow-pg-btn"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="vshow-pg-btn vshow-pg-btn--disabled"><i class="fas fa-chevron-right"></i></span>
            @endif
        </nav>
        @endif

        {{-- Sibling galleries --}}
        @if($siblingGalleries->isNotEmpty())
        <div class="vshow-related">
            <div class="vshow-related__title">More from {{ $year }}</div>
            <div class="vshow-related-scroll">
                @foreach($siblingGalleries as $sibling)
                <a href="{{ route('video-gallery.show', [$year, $sibling->id]) }}" class="vshow-related-pill">
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


{{-- Script is placed directly in @section('content') because
     master.blade.php has no @stack('scripts') directive. --}}
<script>
(function () {
    'use strict';

    /* ── vsPlayVideo ────────────────────────────────────────────────
       Called when the user clicks a .vshow-thumb element.
       el  = the .vshow-thumb div that was clicked.
    ──────────────────────────────────────────────────────────────── */
    window.vsPlayVideo = function (el) {

        var card      = el.closest('.vshow-card');
        var youtubeId = card ? card.dataset.youtubeId : null;

        /* Guard: no YouTube ID — nothing to play */
        if (!youtubeId) return;

        /* Guard: already playing in this card — ignore re-click */
        if (card.classList.contains('is-playing')) return;

        /* Stop every other currently-playing card first */
        vsPauseOthers(card);

        /* ── Build the iframe ──────────────────────────────────────
           origin=   : YouTube requires this to match the embedding
                       page's domain; without it autoplay is blocked
           enablejsapi: lets us send postMessage stop commands later
           playsinline: prevents iOS Safari full-screen hijack
        ────────────────────────────────────────────────────────── */
        var iframe = document.createElement('iframe');
        iframe.src =
            'https://www.youtube.com/embed/' + youtubeId +
            '?autoplay=1' +
            '&rel=0' +
            '&modestbranding=1' +
            '&playsinline=1' +
            '&enablejsapi=1' +
            '&origin=' + encodeURIComponent(window.location.origin);

        iframe.allow =
            'autoplay; clipboard-write; encrypted-media; ' +
            'gyroscope; picture-in-picture; web-share; fullscreen';
        iframe.allowFullscreen = true;
        iframe.setAttribute('referrerpolicy', 'strict-origin-when-cross-origin');

        /* Replace thumbnail with iframe */
        el.innerHTML = '';
        el.appendChild(iframe);

        /* Mark card as playing (CSS removes hover-lift, overlays) */
        card.classList.add('is-playing');

        /* Remove click so re-tapping the thumb doesn't reload */
        el.onclick = null;
    };

    /* ── vsPauseOthers ──────────────────────────────────────────────
       Stops all playing cards except the one just clicked.
       Restores the original thumbnail so the card can be replayed.
    ──────────────────────────────────────────────────────────────── */
    function vsPauseOthers(exceptCard) {
        document.querySelectorAll('#vshow-grid .vshow-card.is-playing')
            .forEach(function (card) {
                if (card === exceptCard) return;

                var frame = card.querySelector('.vshow-thumb iframe');
                if (frame) {
                    /* Ask YouTube to stop via the Player API */
                    try {
                        frame.contentWindow.postMessage(
                            '{"event":"command","func":"stopVideo","args":""}', '*'
                        );
                    } catch (e) {}
                    frame.src = '';
                }

                /* Restore thumbnail + play button */
                var savedId = card.dataset.youtubeId;
                var thumb   = card.querySelector('.vshow-thumb');
                if (thumb && savedId) {
                    thumb.innerHTML =
                        '<img src="https://img.youtube.com/vi/' + savedId +
                        '/hqdefault.jpg" loading="lazy" alt="video">' +
                        '<div class="vshow-play-overlay"><div class="vshow-play-btn">' +
                        '<i class="fas fa-play"></i></div></div>' +
                        '<div class="vshow-thumb-hover"><span class="vshow-thumb-cta">' +
                        '<i class="fas fa-play"></i> Watch Video</span></div>';
                    thumb.onclick = function () { vsPlayVideo(this); };
                }

                card.classList.remove('is-playing');
            });
    }

}());
</script>
@endsection
