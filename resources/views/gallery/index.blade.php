{{-- resources/views/gallery/index.blade.php --}}
@extends('layouts.master')

@section('title', 'Photo Gallery — 3SixtyShows')
@section('meta_description', 'Browse our collection of Bollywood event photos from 3SixtyShows — Dallas\'s premier Bollywood entertainment company.')

@push('styles')
<style>
/* ── Hero ─────────────────────────────────────────────────── */
.glr-hero {
    background: #F5F5F5;
    padding: 48px 0 52px;
    position: relative;
}

/* ── Breadcrumb pill ──────────────────────────────────────── */
.glr-breadcrumb {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 28px;
    font-family: 'DM Sans', sans-serif;
}
.glr-breadcrumb__inner {
    display: inline-flex;
    align-items: center;
    flex-wrap: wrap;
    background: #fff;
    border: 1px solid #E2E2E2;
    border-radius: 999px;
    padding: 7px 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,.07);
}
.glr-breadcrumb__link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 600;
    color: #555;
    text-decoration: none;
    letter-spacing: .4px;
    text-transform: uppercase;
    transition: color .18s ease;
    white-space: nowrap;
}
.glr-breadcrumb__link i { font-size: 10px; color: #C8102E; }
.glr-breadcrumb__link:hover { color: #C8102E; text-decoration: none; }
.glr-breadcrumb__sep {
    display: inline-flex;
    align-items: center;
    margin: 0 8px;
    color: #D4A017;
    font-size: 11px;
    font-weight: 700;
    user-select: none;
}
.glr-breadcrumb__current {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 700;
    color: #C8102E;
    letter-spacing: .4px;
    text-transform: uppercase;
    max-width: 240px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* ── Bordered heading box ─────────────────────────────────── */
.glr-hero__head {
    position: relative;
    max-width: 720px;
    margin: 0 auto;
    border: 1.5px solid #1a1a1a;
    padding: 36px 48px 32px;
    text-align: center;
    background: #fff;
}
/* Gold corner brackets */
.glr-hero__head::before,
.glr-hero__head::after {
    content: '';
    position: absolute;
    width: 22px;
    height: 22px;
    border-color: #D4A017;
    border-style: solid;
}
.glr-hero__head::before { top: -3px; left: -3px; border-width: 3px 0 0 3px; }
.glr-hero__head::after  { bottom: -3px; right: -3px; border-width: 0 3px 3px 0; }

/* Gold italic label */
.glr-hero__label {
    display: block;
    font-family: 'Playfair Display', serif;
    font-size: clamp(.9rem, 1.5vw, 1.1rem);
    font-weight: 700;
    font-style: italic;
    color: #D4A017;
    letter-spacing: .03em;
    margin: 0 0 12px;
}
/* Crimson title */
.glr-hero__title {
    font-family: 'Oswald', sans-serif;
    font-size: clamp(1.6rem, 4vw, 2.6rem);
    font-weight: 700;
    letter-spacing: .06em;
    color: #C8102E;
    text-transform: uppercase;
    line-height: 1.08;
    margin: 0;
}
/* Gold underbar */
.glr-hero__title::after {
    content: '';
    display: block;
    width: 56px;
    height: 3px;
    background: #D4A017;
    border-radius: 2px;
    margin: 14px auto 0;
}
/* Description — gold left bar */
.glr-hero__sub {
    display: inline-block;
    font-family: 'DM Sans', sans-serif;
    font-size: .92rem;
    font-weight: 500;
    color: #444;
    margin: 20px auto 0;
    line-height: 1.55;
    padding: 9px 24px;
    background: #FDF9F2;
    border: none !important;
    border-left: 3px solid #D4A017 !important;
    outline: none !important;
    border-radius: 0 6px 6px 0;
    letter-spacing: .01em;
}

@media (max-width: 576px) {
    .glr-hero__head { padding: 28px 24px 24px; }
    .glr-breadcrumb__inner { padding: 6px 14px; border-radius: 12px; }
    .glr-breadcrumb__link,
    .glr-breadcrumb__current { font-size: 10px; }
    .glr-breadcrumb__sep { margin: 0 5px; }
}

/* ── Section wrapper ──────────────────────────────────────── */
.glr-section {
    background: #F5F5F5;
    padding: 52px 0 72px;
    min-height: 60vh;
}

/* ── Grid: mirrors .otc-grid ──────────────────────────────── */
.glr-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    align-items: start;
}

/* ── Card shell: mirrors .otc-card ───────────────────────── */
.glr-card {
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
.glr-card:hover {
    box-shadow: 0 8px 32px rgba(0,0,0,.15);
    transform: translateY(-4px);
    text-decoration: none;
}

/* ── ① Dark top bar: mirrors .otc-topbar ─────────────────── */
.glr-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #111;
    padding: 8px 13px;
    height: 38px;
    border-bottom: 2.5px solid rgba(255,255,255,0.70);
}
.glr-topbar__label {
    font-size: 12.5px;
    font-weight: 700;
    color: #fff;
    letter-spacing: .3px;
    line-height: 1;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.glr-topbar__icon {
    color: rgba(255,255,255,.72);
    font-size: 15px;
    line-height: 1;
}

/* ── ② Poster image: mirrors .otc-poster ─────────────────── */
.glr-poster {
    position: relative;
    width: 100%;
    background: #111;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    line-height: 0;
}
.glr-poster__img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    display: block;
    object-fit: contain;
    object-position: center center;
    transition: transform .38s ease;
}
.glr-card:hover .glr-poster__img { transform: scale(1.04); }

.glr-poster__placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #1a0005 0%, #200010 100%);
}
.glr-poster__placeholder svg { opacity: 0.14; }

.glr-poster__hover {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(10,10,10,.82) 0%, rgba(10,10,10,.20) 45%, transparent 100%);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding-bottom: 20px;
    opacity: 0;
    transition: opacity .26s ease;
    z-index: 2;
}
.glr-card:hover .glr-poster__hover { opacity: 1; }
.glr-poster__cta {
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
.glr-poster__badge {
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
    z-index: 3;
    backdrop-filter: blur(4px);
}

/* ── ③ Card body: mirrors .otc-body ──────────────────────── */
.glr-body {
    padding: 12px 14px 14px;
    background: #fff;
}
.glr-body__cat {
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
.glr-body__title {
    font-size: 15px;
    font-weight: 700;
    color: #111827;
    line-height: 1.35;
    margin: 0 0 7px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.glr-body__hr {
    border: none;
    border-top: 1px solid #F0F0F0;
    margin: 10px 0;
}
.glr-body__meta {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 12px;
    color: #6B7280;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.glr-body__meta i { color: #C8102E; font-size: 11px; }
.glr-body__cta {
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
.glr-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 70px 20px;
    background: #FAFAF9;
    border: 1.5px dashed #E5E7EB;
    border-radius: 14px;
}
.glr-empty__icon { font-size: 3rem; margin-bottom: 16px; }
.glr-empty h3 {
    font-size: 1.25rem; font-weight: 700;
    color: #111827; margin-bottom: 10px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.glr-empty p {
    font-size: .9rem; color: #6B7280; line-height: 1.7;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}

/* ── Responsive ───────────────────────────────────────────── */
@media (max-width: 991px) { .glr-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 767px) { .glr-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; } }
@media (max-width: 480px) { .glr-grid { grid-template-columns: 1fr; gap: 16px; } }
</style>
@endpush

@section('content')

<section class="glr-hero">
    <div class="container">

        {{-- Breadcrumb pill --}}
        <nav class="glr-breadcrumb" aria-label="Breadcrumb">
            <div class="glr-breadcrumb__inner">
                <a href="{{ route('index') }}" class="glr-breadcrumb__link">
                    <i class="fas fa-home"></i> Home
                </a>
                <span class="glr-breadcrumb__sep">&#8250;</span>
                <span class="glr-breadcrumb__current">
                    <i class="fas fa-images"></i> Photo Gallery
                </span>
            </div>
        </nav>

        {{-- Bordered heading box --}}
        <div class="glr-hero__head">
            <span class="glr-hero__label">Browse & Relive</span>
            <h1 class="glr-hero__title">Photo Gallery</h1>
            <div class="glr-hero__sub">Relive the magic — browse all our Bollywood events by year.</div>
        </div>

    </div>
</section>

<section class="glr-section">
    <div class="container">
        <div class="glr-grid">

            @forelse($galleryYears as $data)
            <a href="{{ route('gallery.year', $data['year']) }}" class="glr-card">

                {{-- ① Dark top bar --}}
                <div class="glr-topbar">
                    <span class="glr-topbar__label">{{ $data['year'] }} Events</span>
                    <span class="glr-topbar__icon"><i class="fas fa-images"></i></span>
                </div>

                {{-- ② Cover image --}}
                <div class="glr-poster">
                    @if($data['cover'])
                        <img src="{{ $data['cover'] }}" alt="{{ $data['year'] }} Events" class="glr-poster__img" loading="lazy">
                    @else
                        <div class="glr-poster__placeholder">
                            <svg width="90" height="90" viewBox="0 0 24 24" fill="white">
                                <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="glr-poster__hover">
                        <span class="glr-poster__cta"><i class="fas fa-images"></i> View Galleries</span>
                    </div>
                    <span class="glr-poster__badge">{{ $data['count'] }} {{ Str::plural('Gallery', $data['count']) }}</span>
                </div>

                {{-- ③ Body --}}
                <div class="glr-body">
                    <span class="glr-body__cat">Photo Gallery</span>
                    <div class="glr-body__title">{{ $data['year'] }} Events</div>
                    <hr class="glr-body__hr">
                    <div class="glr-body__meta">
                        <i class="fas fa-layer-group"></i>
                        <span>{{ $data['count'] }} {{ Str::plural('gallery', $data['count']) }}</span>
                    </div>
                    @if(!empty($data['earliest_show_date']))
                    <div class="glr-body__meta" style="margin-top:4px;">
                        <i class="far fa-calendar-alt" style="color:#C8102E;"></i>
                        <span>From {{ \Carbon\Carbon::parse($data['earliest_show_date'])->format('M j, Y') }}</span>
                    </div>
                    @endif
                    <div class="glr-body__cta">
                        Browse Year <i class="fas fa-chevron-right" style="font-size:10px;margin-left:2px;"></i>
                    </div>
                </div>

            </a>
            @empty
            <div class="glr-empty">
                <div class="glr-empty__icon">📷</div>
                <h3>No Galleries Yet</h3>
                <p>Check back after our upcoming events!</p>
            </div>
            @endforelse

        </div>
    </div>
</section>

@endsection
