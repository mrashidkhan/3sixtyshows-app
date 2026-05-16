{{-- resources/views/videogallery/index.blade.php --}}
@extends('layouts.master')

@section('title', 'Video Gallery — 3SixtyShows')
@section('meta_description', 'Watch videos from 3SixtyShows Bollywood events — Dallas\'s premier Bollywood entertainment company.')

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
}
.vglr-topbar__icon {
    color: rgba(255,255,255,.72);
    font-size: 15px;
    line-height: 1;
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

/* Play button overlay on thumbnail */
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
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: rgba(200,16,46,0.88);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 22px rgba(200,16,46,.55);
    transition: transform .22s ease;
}
.vglr-card:hover .vglr-poster__play-btn { transform: scale(1.12); }
.vglr-poster__play-btn i {
    color: #fff;
    font-size: 20px;
    margin-left: 3px; /* optical centre for triangle */
}

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
    top: 12px;
    right: 12px;
    background: rgba(200,16,46,0.92);
    color: #fff;
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: .6px;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 20px;
    z-index: 4;
    backdrop-filter: blur(4px);
}

/* ── ③ Card body ──────────────────────────────────────────── */
.vglr-body {
    padding: 12px 14px 14px;
    background: #fff;
}
.vglr-body__cat {
    display: block;
    font-size: 10.5px;
    font-weight: 600;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: .8px;
    margin-bottom: 4px;
    line-height: 1;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.vglr-body__title {
    font-size: 15px;
    font-weight: 700;
    color: #111827;
    line-height: 1.35;
    margin: 0 0 7px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.vglr-body__hr {
    border: none;
    border-top: 1px solid #F0F0F0;
    margin: 10px 0;
}
.vglr-body__meta {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 12px;
    color: #6B7280;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.vglr-body__meta i { color: #C8102E; font-size: 11px; }
.vglr-body__cta {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 4px;
    font-size: 11.5px;
    font-weight: 700;
    color: #C8102E;
    letter-spacing: .6px;
    text-transform: uppercase;
    font-family: var(--font-body, 'DM Sans', sans-serif);
    margin-top: 8px;
}

/* ── Empty state ──────────────────────────────────────────── */
.vglr-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 70px 20px;
    background: #FAFAF9;
    border: 1.5px dashed #E5E7EB;
    border-radius: 14px;
}
.vglr-empty__icon { font-size: 3rem; margin-bottom: 16px; }
.vglr-empty h3 {
    font-size: 1.25rem; font-weight: 700;
    color: #111827; margin-bottom: 10px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.vglr-empty p {
    font-size: .9rem; color: #6B7280; line-height: 1.7;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}

/* ── Responsive ───────────────────────────────────────────── */
@media (max-width: 991px) { .vglr-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 767px) { .vglr-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; } }
@media (max-width: 480px) { .vglr-grid { grid-template-columns: 1fr; gap: 16px; } }
</style>
@endpush

@section('content')

<section class="vglr-hero">
    <div class="container">
        <div class="vglr-hero__breadcrumb">
            <a href="{{ route('index') }}">Home</a>
            <span class="mx-2">›</span>
            <span>Video Gallery</span>
        </div>
        <h1 class="vglr-hero__title">Video <span>Gallery</span></h1>
        <p class="vglr-hero__sub">Relive the magic — watch highlights from all our Bollywood events by year.</p>
    </div>
</section>

<section class="vglr-section">
    <div class="container">
        <div class="vglr-grid">

            @forelse($galleryYears as $data)
            <a href="{{ route('video-gallery.year', $data['year']) }}" class="vglr-card">

                {{-- ① Dark top bar --}}
                <div class="vglr-topbar">
                    <span class="vglr-topbar__label">{{ $data['year'] }} Events</span>
                    <span class="vglr-topbar__icon"><i class="fas fa-film"></i></span>
                </div>

                {{-- ② Thumbnail / cover --}}
                <div class="vglr-poster">
                    @if($data['cover'])
                        <img src="{{ $data['cover'] }}" alt="{{ $data['year'] }} Events" class="vglr-poster__img" loading="lazy">
                    @else
                        <div class="vglr-poster__placeholder">
                            <svg width="90" height="90" viewBox="0 0 24 24" fill="white">
                                <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/>
                            </svg>
                        </div>
                    @endif

                    {{-- Play icon shown when thumbnail is present --}}
                    @if($data['cover'])
                    <div class="vglr-poster__play">
                        <div class="vglr-poster__play-btn">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    @endif

                    <div class="vglr-poster__hover">
                        <span class="vglr-poster__cta"><i class="fas fa-film"></i> View Videos</span>
                    </div>
                    <span class="vglr-poster__badge">{{ $data['count'] }} {{ Str::plural('Gallery', $data['count']) }}</span>
                </div>

                {{-- ③ Body --}}
                <div class="vglr-body">
                    <span class="vglr-body__cat">Video Gallery</span>
                    <div class="vglr-body__title">{{ $data['year'] }} Events</div>
                    <hr class="vglr-body__hr">
                    <div class="vglr-body__meta">
                        <i class="fas fa-layer-group"></i>
                        <span>{{ $data['count'] }} {{ Str::plural('gallery', $data['count']) }}</span>
                    </div>
                    @if(!empty($data['earliest_show_date']))
                    <div class="vglr-body__meta" style="margin-top:4px;">
                        <i class="far fa-calendar-alt"></i>
                        <span>From {{ \Carbon\Carbon::parse($data['earliest_show_date'])->format('M j, Y') }}</span>
                    </div>
                    @endif
                    <div class="vglr-body__cta">
                        Browse Year <i class="fas fa-chevron-right" style="font-size:10px;margin-left:2px;"></i>
                    </div>
                </div>

            </a>
            @empty
            <div class="vglr-empty">
                <div class="vglr-empty__icon">🎬</div>
                <h3>No Video Galleries Yet</h3>
                <p>Check back after our upcoming events!</p>
            </div>
            @endforelse

        </div>
    </div>
</section>

@endsection
