{{-- resources/views/videogallery/year.blade.php --}}
@extends('layouts.master')

@section('title', $year . ' Video Galleries — 3SixtyShows')
@section('meta_description', 'Watch all ' . $year . ' Bollywood event video galleries from 3SixtyShows Dallas.')

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
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 700;
    letter-spacing: 0.04em;
    color: #fff;
    text-transform: uppercase;
    line-height: 1;
}
.vglr-hero__title span { color: #C8102E; }
.vglr-hero__sub {
    color: rgba(255,255,255,0.5);
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 1rem;
    margin-top: 10px;
}

/* Year nav pills */
.vglr-year-nav {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 22px;
}
.vglr-year-pill {
    font-family: 'Oswald', sans-serif;
    font-size: 0.88rem;
    letter-spacing: 0.06em;
    padding: 5px 16px;
    border-radius: 30px;
    border: 1px solid rgba(255,255,255,0.18);
    color: rgba(255,255,255,0.5);
    text-decoration: none;
    transition: all .22s ease;
}
.vglr-year-pill:hover,
.vglr-year-pill--active {
    background: #C8102E;
    border-color: #C8102E;
    color: #fff;
    text-decoration: none;
}

/* ── Section wrapper ──────────────────────────────────────── */
.vglr-section {
    background: #F5F5F5;
    padding: 52px 0 72px;
    min-height: 60vh;
}

/* ── Grid ─────────────────────────────────────────────────── */
.vglr-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    align-items: start;
}

/* ── Card shell ───────────────────────────────────────────── */
.vglr-card {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #E2E2E2;
    box-shadow: 0 1px 3px rgba(0,0,0,.07), 0 3px 12px rgba(0,0,0,.06);
    transition: box-shadow .22s ease, transform .22s ease;
    text-decoration: none;
    color: inherit;
    display: block;
}
.vglr-card:hover {
    box-shadow: 0 8px 32px rgba(0,0,0,.15);
    transform: translateY(-4px);
    text-decoration: none;
}

/* ── ① Dark top bar ───────────────────────────────────────── */
.vglr-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #111;
    padding: 8px 13px;
    height: 38px;
    border-bottom: 2.5px solid rgba(255,255,255,0.70);
}
.vglr-topbar__label {
    font-size: 12.5px;
    font-weight: 700;
    color: #fff;
    letter-spacing: .3px;
    line-height: 1;
    font-family: var(--font-body, 'DM Sans', sans-serif);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: calc(100% - 28px);
}
.vglr-topbar__icon {
    color: rgba(255,255,255,.72);
    font-size: 15px;
    line-height: 1;
    flex-shrink: 0;
}

/* ── ② Poster / thumbnail ─────────────────────────────────── */
.vglr-poster {
    position: relative;
    width: 100%;
    background: #111;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    line-height: 0;
}
.vglr-poster__img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    display: block;
    object-fit: contain;
    object-position: center center;
    transition: transform .38s ease;
}
.vglr-card:hover .vglr-poster__img { transform: scale(1.04); }

.vglr-poster__play {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
    pointer-events: none;
}
.vglr-poster__play-btn {
    width: 52px; height: 52px;
    border-radius: 50%;
    background: rgba(200,16,46,0.88);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 22px rgba(200,16,46,.55);
    transition: transform .22s ease;
}
.vglr-card:hover .vglr-poster__play-btn { transform: scale(1.12); }
.vglr-poster__play-btn i { color: #fff; font-size: 20px; margin-left: 3px; }

.vglr-poster__placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #00051a 0%, #0d0020 100%);
}
.vglr-poster__placeholder svg { opacity: 0.14; }

.vglr-poster__hover {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(10,10,10,.82) 0%, rgba(10,10,10,.20) 45%, transparent 100%);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding-bottom: 20px;
    opacity: 0;
    transition: opacity .26s ease;
    z-index: 3;
}
.vglr-card:hover .vglr-poster__hover { opacity: 1; }
.vglr-poster__cta {
    background: linear-gradient(135deg, #C8102E 0%, #9e0b22 100%);
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 9px 22px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    box-shadow: 0 4px 18px rgba(200,16,46,.50);
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.vglr-poster__badge {
    position: absolute;
    top: 12px; right: 12px;
    background: rgba(200,16,46,0.92);
    color: #fff;
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 10.5px; font-weight: 700;
    letter-spacing: .6px; text-transform: uppercase;
    padding: 4px 10px; border-radius: 20px;
    z-index: 4; backdrop-filter: blur(4px);
}

/* ── ③ Card body ──────────────────────────────────────────── */
.vglr-body {
    padding: 12px 14px 14px;
    background: #fff;
}
.vglr-body__cat {
    display: block; font-size: 10.5px; font-weight: 600;
    color: #9CA3AF; text-transform: uppercase; letter-spacing: .8px;
    margin-bottom: 4px; line-height: 1;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.vglr-body__title {
    font-size: 15px; font-weight: 700; color: #111827;
    line-height: 1.35; margin: 0 0 4px;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.vglr-body__desc {
    font-size: 12px; color: #6B7280; line-height: 1.5;
    margin: 0 0 4px;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.vglr-body__hr { border: none; border-top: 1px solid #F0F0F0; margin: 10px 0; }
.vglr-body__meta {
    display: flex; align-items: center; gap: 6px;
    font-size: 12px; color: #6B7280; margin-bottom: 4px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.vglr-body__meta i { color: #C8102E; font-size: 11px; }
.vglr-body__cta {
    display: flex; align-items: center; justify-content: flex-end; gap: 4px;
    font-size: 11.5px; font-weight: 700; color: #C8102E;
    letter-spacing: .6px; text-transform: uppercase;
    font-family: var(--font-body, 'DM Sans', sans-serif); margin-top: 8px;
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
.vglr-pg-btn:hover {
    background: #C8102E; border-color: #C8102E; color: #fff;
    box-shadow: 0 4px 14px rgba(200,16,46,.35); transform: translateY(-1px);
}
.vglr-pg-btn--disabled {
    background: #F9F9F9; border-color: #E8E2DA; color: #C0B8B0;
    cursor: not-allowed; pointer-events: none;
}
.vglr-pg-num {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 40px; height: 40px; padding: 0 6px; border-radius: 10px;
    background: #fff; border: 1.5px solid #D8D2CA; color: #374151;
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 14px; font-weight: 600; text-decoration: none;
    transition: all .2s ease; flex-shrink: 0;
}
.vglr-pg-num:hover {
    background: rgba(200,16,46,.06); border-color: #C8102E; color: #C8102E;
    transform: translateY(-1px);
}
.vglr-pg-num--active {
    background: linear-gradient(135deg, #C8102E 0%, #9e0b22 100%);
    border-color: #C8102E; color: #fff;
    box-shadow: 0 4px 14px rgba(200,16,46,.4);
    cursor: default; pointer-events: none;
}
.vglr-pg-ellipsis {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 40px; color: #9CA3AF;
    font-size: 16px; font-weight: 700; letter-spacing: 1px;
}

/* ── Empty state ──────────────────────────────────────────── */
.vglr-empty {
    grid-column: 1 / -1; text-align: center;
    padding: 70px 20px; background: #FAFAF9;
    border: 1.5px dashed #E5E7EB; border-radius: 14px;
}
.vglr-empty__icon { font-size: 3rem; margin-bottom: 16px; }
.vglr-empty h3 {
    font-size: 1.25rem; font-weight: 700; color: #111827; margin-bottom: 10px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.vglr-empty p {
    font-size: .9rem; color: #6B7280; line-height: 1.7;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.vglr-empty p a { color: #C8102E; font-weight: 600; text-decoration: none; }

/* ── Responsive ───────────────────────────────────────────── */
@media (max-width: 991px) { .vglr-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 767px) { .vglr-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; } }
@media (max-width: 480px) { .vglr-grid { grid-template-columns: 1fr; gap: 16px; } }
@media (max-width: 768px) { .vglr-pagination { gap: 5px; margin-top: 36px; } .vglr-pg-btn, .vglr-pg-num { width: 36px; height: 36px; font-size: 13px; } }
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
            <span>{{ $year }}</span>
        </div>
        <h1 class="vglr-hero__title"><span>{{ $year }}</span> Video Galleries</h1>
        <p class="vglr-hero__sub">
            {{ $galleries->total() }} {{ Str::plural('event gallery', $galleries->total()) }} from {{ $year }}
        </p>

        @if($availableYears->count() > 1)
        <div class="vglr-year-nav">
            @foreach($availableYears as $yr)
            <a href="{{ route('video-gallery.year', $yr) }}"
               class="vglr-year-pill {{ $yr == $year ? 'vglr-year-pill--active' : '' }}">
                {{ $yr }}
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>

<section class="vglr-section">
    <div class="container">
        <div class="vglr-grid">

            @forelse($galleries as $gallery)
            <a href="{{ route('video-gallery.show', [$year, $gallery->id]) }}" class="vglr-card">

                {{-- ① Dark top bar --}}
                <div class="vglr-topbar">
                    <span class="vglr-topbar__label">
                        {{ $gallery->show ? \Carbon\Carbon::parse($gallery->show->start_date)->format('Y M d') : $gallery->created_at->format('Y M d') }}
                    </span>
                    <span class="vglr-topbar__icon"><i class="fas fa-video"></i></span>
                </div>

                {{-- ② Thumbnail --}}
                <div class="vglr-poster">
                    @if($gallery->thumbnail)
                        <img src="{{ str_starts_with($gallery->thumbnail, 'http') ? $gallery->thumbnail : asset('storage/' . $gallery->thumbnail) }}"
                             alt="{{ $gallery->title }}" class="vglr-poster__img" loading="lazy">
                        <div class="vglr-poster__play">
                            <div class="vglr-poster__play-btn"><i class="fas fa-play"></i></div>
                        </div>
                    @else
                        <div class="vglr-poster__placeholder">
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="white">
                                <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="vglr-poster__hover">
                        <span class="vglr-poster__cta"><i class="fas fa-video"></i> Watch Videos</span>
                    </div>
                    <span class="vglr-poster__badge">
                        {{ $gallery->videos->count() }} {{ Str::plural('Video', $gallery->videos->count()) }}
                    </span>
                </div>

                {{-- ③ Body --}}
                <div class="vglr-body">
                    <span class="vglr-body__cat">{{ $year }} Video Gallery</span>
                    <div class="vglr-body__title">{{ $gallery->title }}</div>
                    @if($gallery->description)
                    <div class="vglr-body__desc">{{ $gallery->description }}</div>
                    @endif
                    <hr class="vglr-body__hr">
                    <div class="vglr-body__meta">
                        <i class="fas fa-play-circle"></i>
                        <span>{{ $gallery->videos->count() }} {{ Str::plural('video', $gallery->videos->count()) }}</span>
                    </div>
                    <div class="vglr-body__meta" style="margin-top:4px;">
                        <i class="far fa-calendar-alt"></i>
                        <span>
                            {{ $gallery->show
                                ? \Carbon\Carbon::parse($gallery->show->start_date)->format('M j, Y')
                                : $gallery->created_at->format('M j, Y') }}
                        </span>
                    </div>
                    <div class="vglr-body__cta">
                        Watch Now <i class="fas fa-chevron-right" style="font-size:10px;margin-left:2px;"></i>
                    </div>
                </div>

            </a>
            @empty
            <div class="vglr-empty">
                <div class="vglr-empty__icon">🎬</div>
                <h3>No Video Galleries for {{ $year }}</h3>
                <p><a href="{{ route('video-gallery.index') }}">Browse all years →</a></p>
            </div>
            @endforelse

        </div>

        {{-- Pagination --}}
        @if($galleries->hasPages())
        <nav class="vglr-pagination" aria-label="Gallery pagination">

            @if($galleries->onFirstPage())
                <span class="vglr-pg-btn vglr-pg-btn--disabled" aria-disabled="true"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $galleries->previousPageUrl() }}" class="vglr-pg-btn" aria-label="Previous page"><i class="fas fa-chevron-left"></i></a>
            @endif

            @php $current = $galleries->currentPage(); $last = $galleries->lastPage(); $from = max(1, $current - 2); $to = min($last, $current + 2); @endphp

            @if($from > 1)
                <a href="{{ $galleries->url(1) }}" class="vglr-pg-num">1</a>
                @if($from > 2)<span class="vglr-pg-ellipsis">&hellip;</span>@endif
            @endif

            @for($p = $from; $p <= $to; $p++)
                @if($p == $current)
                    <span class="vglr-pg-num vglr-pg-num--active" aria-current="page">{{ $p }}</span>
                @else
                    <a href="{{ $galleries->url($p) }}" class="vglr-pg-num">{{ $p }}</a>
                @endif
            @endfor

            @if($to < $last)
                @if($to < $last - 1)<span class="vglr-pg-ellipsis">&hellip;</span>@endif
                <a href="{{ $galleries->url($last) }}" class="vglr-pg-num">{{ $last }}</a>
            @endif

            @if($galleries->hasMorePages())
                <a href="{{ $galleries->nextPageUrl() }}" class="vglr-pg-btn" aria-label="Next page"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="vglr-pg-btn vglr-pg-btn--disabled" aria-disabled="true"><i class="fas fa-chevron-right"></i></span>
            @endif

        </nav>
        @endif

    </div>
</section>

@endsection
