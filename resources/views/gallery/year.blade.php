
{{-- resources/views/gallery/year.blade.php --}}
@extends('layouts.master')

@section('title', $year . ' Photo Galleries — 3SixtyShows')
@section('meta_description', 'Browse all ' . $year . ' Bollywood event photo galleries from 3SixtyShows Dallas.')

@push('styles')
<style>
/* ── Hero ─────────────────────────────────────────────────── */
.glr-hero {
    background: linear-gradient(135deg, #0a0a0a 0%, #1a0005 50%, #0a0a0a 100%);
    padding: 72px 0 52px;
    position: relative;
    overflow: hidden;
}
.glr-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 60% 60% at 50% 0%, rgba(200,16,46,0.18) 0%, transparent 70%);
    pointer-events: none;
}
.glr-hero__breadcrumb {
    font-size: 0.78rem;
    color: rgba(255,255,255,0.38);
    letter-spacing: 0.07em;
    text-transform: uppercase;
    margin-bottom: 16px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.glr-hero__breadcrumb a { color: #D4A017; text-decoration: none; }
.glr-hero__breadcrumb a:hover { text-decoration: underline; }
.glr-hero__title {
    font-family: 'Oswald', sans-serif;
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 700;
    letter-spacing: 0.04em;
    color: #fff;
    text-transform: uppercase;
    line-height: 1;
}
.glr-hero__title span { color: #C8102E; }
.glr-hero__sub {
    color: rgba(255,255,255,0.5);
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 1rem;
    margin-top: 10px;
}

/* Year nav pills */
.glr-year-nav {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 22px;
}
.glr-year-pill {
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
.glr-year-pill:hover,
.glr-year-pill--active {
    background: #C8102E;
    border-color: #C8102E;
    color: #fff;
    text-decoration: none;
}

/* ── Section wrapper ──────────────────────────────────────── */
.glr-section {
    background: #F5F5F5;
    padding: 52px 0 72px;
    min-height: 60vh;
}

/* ── Grid ─────────────────────────────────────────────────── */
.glr-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    align-items: start;
}

/* ── Card shell ───────────────────────────────────────────── */
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

/* ── ① Dark top bar ───────────────────────────────────────── */
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

/* ── ② Poster image ───────────────────────────────────────── */
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
    object-fit: contain;      /* shrink-to-fit — full image always visible */
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

/* ── ③ Card body ──────────────────────────────────────────── */
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
    margin: 0 0 4px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.glr-body__desc {
    font-size: 12px;
    color: #6B7280;
    line-height: 1.5;
    margin: 0 0 4px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
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
    gap: 6px;
    font-size: 12px;
    color: #6B7280;
    margin-bottom: 4px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.glr-body__meta i { color: #C8102E; font-size: 11px; flex-shrink: 0; }
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

/* ── Pagination — matches past-events style ───────────────── */
.glr-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    flex-wrap: wrap;
    margin-top: 48px;
    padding-bottom: 8px;
}
.glr-pg-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px; height: 40px;
    border-radius: 50%;
    background: #fff;
    border: 1.5px solid #D8D2CA;
    color: #111827;
    font-size: 13px;
    text-decoration: none;
    transition: all .2s ease;
    flex-shrink: 0;
}
.glr-pg-btn:hover {
    background: #C8102E;
    border-color: #C8102E;
    color: #fff;
    box-shadow: 0 4px 14px rgba(200,16,46,0.35);
    transform: translateY(-1px);
}
.glr-pg-btn--disabled {
    background: #F9F9F9;
    border-color: #E8E2DA;
    color: #C0B8B0;
    cursor: not-allowed;
    pointer-events: none;
}
.glr-pg-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px; height: 40px;
    padding: 0 6px;
    border-radius: 10px;
    background: #fff;
    border: 1.5px solid #D8D2CA;
    color: #374151;
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all .2s ease;
    flex-shrink: 0;
}
.glr-pg-num:hover {
    background: rgba(200,16,46,0.06);
    border-color: #C8102E;
    color: #C8102E;
    transform: translateY(-1px);
}
.glr-pg-num--active {
    background: linear-gradient(135deg, #C8102E 0%, #9e0b22 100%);
    border-color: #C8102E;
    color: #fff;
    box-shadow: 0 4px 14px rgba(200,16,46,0.4);
    cursor: default;
    pointer-events: none;
}
.glr-pg-ellipsis {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px; height: 40px;
    color: #9CA3AF;
    font-size: 16px;
    font-weight: 700;
    letter-spacing: 1px;
}

/* ── Responsive pagination ────────────────────────────────── */
@media (max-width: 768px) {
    .glr-pagination { gap: 5px; margin-top: 36px; }
    .glr-pg-btn, .glr-pg-num { width: 36px; height: 36px; font-size: 13px; }
}
@media (max-width: 480px) {
    .glr-pagination { gap: 4px; }
}
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
.glr-empty p a { color: #C8102E; font-weight: 600; text-decoration: none; }

/* ── Responsive ───────────────────────────────────────────── */
@media (max-width: 991px) { .glr-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 767px) { .glr-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; } }
@media (max-width: 480px) { .glr-grid { grid-template-columns: 1fr; gap: 16px; } }
</style>
@endpush

@section('content')

<section class="glr-hero">
    <div class="container">
        <div class="glr-hero__breadcrumb">
            <a href="{{ route('index') }}">Home</a>
            <span class="mx-2">›</span>
            <a href="{{ route('gallery.index') }}">Gallery</a>
            <span class="mx-2">›</span>
            <span>{{ $year }}</span>
        </div>
        <h1 class="glr-hero__title"><span>{{ $year }}</span> Galleries</h1>
        <p class="glr-hero__sub">
            {{ $galleries->total() }} {{ Str::plural('event gallery', $galleries->total()) }} from {{ $year }}
        </p>

        @if($availableYears->count() > 1)
        <div class="glr-year-nav">
            @foreach($availableYears as $yr)
            <a href="{{ route('gallery.year', $yr) }}"
               class="glr-year-pill {{ $yr == $year ? 'glr-year-pill--active' : '' }}">
                {{ $yr }}
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>

<section class="glr-section">
    <div class="container">
        <div class="glr-grid">

            @forelse($galleries as $gallery)
            <a href="{{ route('gallery.show', [$year, $gallery->id]) }}" class="glr-card">

                {{-- ① Dark top bar --}}
                <div class="glr-topbar">
                    <span class="glr-topbar__label">
                        {{ $gallery->show ? \Carbon\Carbon::parse($gallery->show->start_date)->format('Y M d') : $gallery->created_at->format('Y M d') }}
                    </span>
                    <span class="glr-topbar__icon"><i class="fas fa-camera"></i></span>
                </div>

                {{-- ② Cover image — card <a> handles navigation --}}
                <div class="glr-poster">
                    @if($gallery->image)
                        <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" class="glr-poster__img" loading="lazy">
                    @else
                        <div class="glr-poster__placeholder">
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="white">
                                <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="glr-poster__hover">
                        <span class="glr-poster__cta"><i class="fas fa-camera"></i> View Photos</span>
                    </div>
                    <span class="glr-poster__badge">
                        {{ $gallery->photos->count() }} {{ Str::plural('Photo', $gallery->photos->count()) }}
                    </span>
                </div>

                {{-- Card body navigates to the gallery --}}
                {{-- ③ Body --}}
                <div class="glr-body">
                    <span class="glr-body__cat">{{ $year }} Gallery</span>
                    <div class="glr-body__title">{{ $gallery->title }}</div>
                    @if($gallery->description)
                    <div class="glr-body__desc">{{ $gallery->description }}</div>
                    @endif
                    <hr class="glr-body__hr">
                    <div class="glr-body__meta">
                        <i class="fas fa-images"></i>
                        <span>{{ $gallery->photos->count() }} {{ Str::plural('photo', $gallery->photos->count()) }}</span>
                    </div>
                    <div class="glr-body__meta" style="margin-top:4px;">
                        <i class="far fa-calendar-alt"></i>
                        <span>
                            {{ $gallery->show
                                ? \Carbon\Carbon::parse($gallery->show->start_date)->format('M j, Y')
                                : $gallery->created_at->format('M j, Y') }}
                        </span>
                    </div>
                    <div class="glr-body__cta">
                        View Photos <i class="fas fa-chevron-right" style="font-size:10px;margin-left:2px;"></i>
                    </div>
                </div>

            </a>
            @empty
            <div class="glr-empty">
                <div class="glr-empty__icon">📷</div>
                <h3>No Galleries for {{ $year }}</h3>
                <p><a href="{{ route('gallery.index') }}">Browse all years →</a></p>
            </div>
            @endforelse

        </div>

        @if($galleries->hasPages())
        <nav class="glr-pagination" aria-label="Gallery pagination">

            {{-- Prev arrow --}}
            @if($galleries->onFirstPage())
                <span class="glr-pg-btn glr-pg-btn--disabled" aria-disabled="true">
                    <i class="fas fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $galleries->previousPageUrl() }}" class="glr-pg-btn" aria-label="Previous page">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            {{-- Page numbers --}}
            @php
                $current  = $galleries->currentPage();
                $last     = $galleries->lastPage();
                $from     = max(1, $current - 2);
                $to       = min($last, $current + 2);
            @endphp

            {{-- First page + leading ellipsis --}}
            @if($from > 1)
                <a href="{{ $galleries->url(1) }}" class="glr-pg-num">1</a>
                @if($from > 2)
                    <span class="glr-pg-ellipsis">&hellip;</span>
                @endif
            @endif

            {{-- Window of pages around current --}}
            @for($p = $from; $p <= $to; $p++)
                @if($p == $current)
                    <span class="glr-pg-num glr-pg-num--active" aria-current="page">{{ $p }}</span>
                @else
                    <a href="{{ $galleries->url($p) }}" class="glr-pg-num">{{ $p }}</a>
                @endif
            @endfor

            {{-- Trailing ellipsis + last page --}}
            @if($to < $last)
                @if($to < $last - 1)
                    <span class="glr-pg-ellipsis">&hellip;</span>
                @endif
                <a href="{{ $galleries->url($last) }}" class="glr-pg-num">{{ $last }}</a>
            @endif

            {{-- Next arrow --}}
            @if($galleries->hasMorePages())
                <a href="{{ $galleries->nextPageUrl() }}" class="glr-pg-btn" aria-label="Next page">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="glr-pg-btn glr-pg-btn--disabled" aria-disabled="true">
                    <i class="fas fa-chevron-right"></i>
                </span>
            @endif

        </nav>
        @endif
    </div>
</section>

@endsection
