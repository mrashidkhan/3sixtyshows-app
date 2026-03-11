@extends('layouts.master')

@section('title', $show->title . ' — 3Sixtyshows')
@section('meta_description', $show->short_description ?? 'View details for ' . $show->title . ' on 3Sixtyshows.')
@section('og_title',    $show->title . ' — 3Sixtyshows')
@section('og_description', $show->short_description ?? '')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     SHOW DETAIL PAGE
     File: resources/views/pages/show-details.blade.php
══════════════════════════════════════════════════════════════ --}}

{{-- ── Hero / Poster Banner ──────────────────────────────────── --}}
<section class="sd-hero">
    <div class="sd-hero__backdrop"
         style="background-image: url('{{ $show->featured_image ? asset('storage/' . $show->featured_image) : asset('assets/images/placeholder.jpg') }}');">
    </div>
    <div class="sd-hero__overlay"></div>

    <div class="container sd-hero__inner">
        {{-- Poster --}}
        <div class="sd-hero__poster">
            <img src="{{ $show->featured_image ? asset('storage/' . $show->featured_image) : asset('assets/images/placeholder.jpg') }}"
                 alt="{{ $show->title }}"
                 class="sd-hero__poster-img">
        </div>

        {{-- Info block --}}
        <div class="sd-hero__info">
            @if($show->category)
                <span class="sd-cat-badge">{{ $show->category->name }}</span>
            @endif

            <h1 class="sd-hero__title">{{ $show->title }}</h1>

            @if($show->venue)
                <p class="sd-hero__venue">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ $show->venue->name }}@if($show->venue->city), {{ $show->venue->city }}@endif
                    @if($show->venue->state), {{ $show->venue->state }}@endif
                </p>
            @endif

            <p class="sd-hero__date">
                <i class="fas fa-calendar-alt"></i>
                {{ \Carbon\Carbon::parse($show->start_date)->format('l, F j, Y') }}
                @if($show->end_date && $show->end_date->format('Y-m-d') !== $show->start_date->format('Y-m-d'))
                    &ndash; {{ \Carbon\Carbon::parse($show->end_date)->format('F j, Y') }}
                @endif
            </p>

            @if($show->duration)
                <p class="sd-hero__meta">
                    <i class="fas fa-clock"></i> {{ $show->duration }}
                    @if($show->age_restriction)
                        &nbsp;&bull;&nbsp;
                        <i class="fas fa-user-check"></i> {{ $show->age_restriction }}
                    @endif
                </p>
            @endif

            {{-- Status badge --}}
            @php
                $statusLabel = match($show->status) {
                    'upcoming' => ['label' => 'Upcoming',  'class' => 'sd-badge--upcoming'],
                    'ongoing'  => ['label' => 'Ongoing',   'class' => 'sd-badge--ongoing'],
                    'past','completed' => ['label' => 'Past Event', 'class' => 'sd-badge--past'],
                    default    => ['label' => ucfirst($show->status ?? 'Event'), 'class' => ''],
                };
            @endphp
            <span class="sd-status-badge {{ $statusLabel['class'] }}">
                {{ $statusLabel['label'] }}
            </span>
        </div>
    </div>
</section>

{{-- ── Main Content ─────────────────────────────────────────── --}}
<section class="sd-body">
    <div class="container sd-body__grid">

        {{-- ── Left: Description + Gallery + Videos ──────────────── --}}
        <div class="sd-body__main">

            @if($show->description)
                <div class="sd-block">
                    <h2 class="sd-block__heading">About This Show</h2>
                    <div class="sd-description">{!! nl2br(e($show->description)) !!}</div>
                </div>
            @endif

            @if($show->performers)
                <div class="sd-block">
                    <h2 class="sd-block__heading"><i class="fas fa-star"></i> Performers</h2>
                    <p class="sd-performers">{{ $show->performers }}</p>
                </div>
            @endif

            {{-- Photo Gallery strip --}}
            @if($show->photos && $show->photos->count() > 0)
                <div class="sd-block">
                    <h2 class="sd-block__heading"><i class="fas fa-images"></i> Gallery</h2>
                    <div class="sd-photo-strip">
                        @foreach($show->photos->take(6) as $photo)
                            <div class="sd-photo-strip__item">
                                <img src="{{ $photo->image_url ?? asset('storage/' . $photo->image) }}"
                                     alt="{{ $show->title }}"
                                     loading="lazy"
                                     class="sd-photo-strip__img">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- ── Right: Tickets sidebar ──────────────────────────── --}}
        <aside class="sd-sidebar">
            <div class="sd-ticket-box">
                <h3 class="sd-ticket-box__heading">
                    <i class="fas fa-ticket-alt"></i> Tickets
                </h3>

                @php
                    $activeTypes = $show->activeTicketTypes ?? collect();
                @endphp

                @if($activeTypes->count() > 0)
                    <ul class="sd-ticket-list">
                        @foreach($activeTypes as $type)
                            <li class="sd-ticket-item">
                                <span class="sd-ticket-item__name">{{ $type->name }}</span>
                                <span class="sd-ticket-item__price">
                                    @if($type->price > 0)
                                        ${{ number_format($type->price, 2) }}
                                    @else
                                        Free
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>

                    @if(in_array($show->status, ['upcoming', 'ongoing']))
                        <a href="{{ route('contactus', $show) }}"
                           class="sd-book-btn">
                            <i class="fas fa-ticket-alt"></i> Contact Us
                        </a>
                    @else
                        <div class="sd-sold-out-notice">
                            <i class="fas fa-history"></i> This show has ended.
                        </div>
                    @endif

                @else
                    <p class="sd-no-tickets">Ticket information not available yet.</p>
                @endif
            </div>

            {{-- Venue card --}}
            @if($show->venue)
                <div class="sd-venue-card">
                    <h4 class="sd-venue-card__heading">
                        <i class="fas fa-map-marker-alt"></i> Venue
                    </h4>
                    <p class="sd-venue-card__name">{{ $show->venue->name }}</p>
                    @if($show->venue->address)
                        <p class="sd-venue-card__addr">{{ $show->venue->address }}</p>
                    @endif
                    @if($show->venue->city)
                        <p class="sd-venue-card__addr">
                            {{ $show->venue->city }}@if($show->venue->state), {{ $show->venue->state }}@endif
                            @if($show->venue->zip_code) {{ $show->venue->zip_code }}@endif
                        </p>
                    @endif
                </div>
            @endif
        </aside>

    </div>{{-- /.sd-body__grid --}}
</section>

{{-- ── Back link ─────────────────────────────────────────────── --}}
<div class="container sd-back-row">
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('pastevents') }}"
       class="sd-back-link">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

{{-- ══════════════════════════════════════════════════════════════
     INTERNAL CSS — all .sd- prefixed, zero bleed to global styles
══════════════════════════════════════════════════════════════ --}}
<style>

/* ── Hero ───────────────────────────────────────────────────── */
.sd-hero {
    position: relative;
    overflow: hidden;
    min-height: 380px;
    display: flex;
    align-items: center;
}

.sd-hero__backdrop {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center top;
    filter: blur(14px) brightness(0.35);
    transform: scale(1.08);
    z-index: 0;
}

.sd-hero__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom,
        rgba(13,13,13,0.30) 0%,
        rgba(13,13,13,0.82) 100%);
    z-index: 1;
}

.sd-hero__inner {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: flex-end;
    gap: 36px;
    padding-top: 60px;
    padding-bottom: 48px;
}

/* Poster */
.sd-hero__poster {
    flex-shrink: 0;
    width: 200px;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 12px 48px rgba(0,0,0,0.6);
    border: 2px solid rgba(255,255,255,0.12);
    background: #111;
    aspect-ratio: 2/3;
}

.sd-hero__poster-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* Info */
.sd-hero__info { flex: 1; min-width: 0; }

.sd-cat-badge {
    display: inline-block;
    background: rgba(200,16,46,0.85);
    color: #fff;
    font-family: 'DM Sans', sans-serif;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    padding: 4px 14px;
    border-radius: 999px;
    margin-bottom: 12px;
}

.sd-hero__title {
    font-family: 'Oswald', sans-serif;
    font-size: clamp(26px, 5vw, 46px);
    font-weight: 700;
    color: #fff;
    line-height: 1.15;
    margin: 0 0 16px;
    text-transform: uppercase;
    letter-spacing: .5px;
}

.sd-hero__venue,
.sd-hero__date,
.sd-hero__meta {
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    color: rgba(255,255,255,0.78);
    margin: 0 0 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.sd-hero__venue i,
.sd-hero__date i,
.sd-hero__meta i { color: #D4A017; font-size: 13px; }

.sd-status-badge {
    display: inline-flex;
    align-items: center;
    margin-top: 14px;
    font-family: 'DM Sans', sans-serif;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 5px 16px;
    border-radius: 999px;
    gap: 6px;
}

.sd-badge--upcoming { background: rgba(212,160,23,0.18); color: #D4A017; border: 1px solid rgba(212,160,23,0.4); }
.sd-badge--ongoing  { background: rgba(34,197,94,0.15);  color: #22c55e; border: 1px solid rgba(34,197,94,0.35); }
.sd-badge--past     { background: rgba(156,163,175,0.15); color: #9CA3AF; border: 1px solid rgba(156,163,175,0.3); }

/* ── Body layout ────────────────────────────────────────────── */
.sd-body {
    background: #F7F4F0;
    padding: 48px 0 64px;
}

.sd-body__grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 40px;
    align-items: start;
}

/* ── Content blocks ─────────────────────────────────────────── */
.sd-block {
    background: #fff;
    border-radius: 12px;
    padding: 28px 30px;
    margin-bottom: 24px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}

.sd-block__heading {
    font-family: 'Oswald', sans-serif;
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin: 0 0 16px;
    display: flex;
    align-items: center;
    gap: 9px;
}

.sd-block__heading i { color: #C8102E; }

.sd-description {
    font-family: 'DM Sans', sans-serif;
    font-size: 15px;
    color: #374151;
    line-height: 1.75;
}

.sd-performers {
    font-family: 'DM Sans', sans-serif;
    font-size: 15px;
    color: #374151;
    line-height: 1.7;
    margin: 0;
}

/* ── Photo strip ────────────────────────────────────────────── */
.sd-photo-strip {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
}

.sd-photo-strip__item {
    aspect-ratio: 4/3;
    border-radius: 8px;
    overflow: hidden;
    background: #111;
}

.sd-photo-strip__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .3s ease;
}

.sd-photo-strip__item:hover .sd-photo-strip__img { transform: scale(1.06); }

/* ── Sidebar ────────────────────────────────────────────────── */
.sd-ticket-box {
    background: #0D0D0D;
    border-radius: 14px;
    padding: 28px 24px;
    margin-bottom: 20px;
    border: 1px solid rgba(200,16,46,0.25);
    box-shadow: 0 8px 32px rgba(0,0,0,0.25);
}

.sd-ticket-box__heading {
    font-family: 'Oswald', sans-serif;
    font-size: 17px;
    font-weight: 600;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: .8px;
    margin: 0 0 18px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.sd-ticket-box__heading i { color: #D4A017; }

.sd-ticket-list {
    list-style: none;
    margin: 0 0 22px;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.sd-ticket-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 8px;
    padding: 10px 14px;
}

.sd-ticket-item__name {
    font-family: 'DM Sans', sans-serif;
    font-size: 13.5px;
    color: rgba(255,255,255,0.85);
    font-weight: 500;
}

.sd-ticket-item__price {
    font-family: 'DM Sans', sans-serif;
    font-size: 15px;
    font-weight: 800;
    color: #D4A017;
}

.sd-book-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    width: 100%;
    background: linear-gradient(135deg, #C8102E 0%, #9e0b22 100%);
    color: #fff;
    text-decoration: none;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 14px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(200,16,46,0.45);
    transition: transform .2s, box-shadow .2s;
}

.sd-book-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(200,16,46,0.60);
    color: #fff;
    text-decoration: none;
}

.sd-sold-out-notice,
.sd-no-tickets {
    font-family: 'DM Sans', sans-serif;
    font-size: 13.5px;
    color: rgba(255,255,255,0.55);
    text-align: center;
    padding: 12px 0 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

/* ── Venue card ─────────────────────────────────────────────── */
.sd-venue-card {
    background: #fff;
    border-radius: 12px;
    padding: 22px 22px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.07);
}

.sd-venue-card__heading {
    font-family: 'Oswald', sans-serif;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .8px;
    color: #C8102E;
    margin: 0 0 10px;
    display: flex;
    align-items: center;
    gap: 7px;
}

.sd-venue-card__name {
    font-family: 'DM Sans', sans-serif;
    font-size: 15px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 5px;
}

.sd-venue-card__addr {
    font-family: 'DM Sans', sans-serif;
    font-size: 13.5px;
    color: #6B7280;
    margin: 0 0 3px;
    line-height: 1.5;
}

/* ── Back link ──────────────────────────────────────────────── */
.sd-back-row {
    padding: 20px 0 40px;
}

.sd-back-link {
    font-family: 'DM Sans', sans-serif;
    font-size: 13.5px;
    font-weight: 600;
    color: #6B7280;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    transition: color .2s;
}

.sd-back-link:hover { color: #C8102E; text-decoration: none; }

/* ── Responsive ─────────────────────────────────────────────── */
@media (max-width: 1024px) {
    .sd-body__grid { grid-template-columns: 1fr; }
    .sd-sidebar { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .sd-ticket-box, .sd-venue-card { margin-bottom: 0; }
}

@media (max-width: 768px) {
    .sd-hero__inner { flex-direction: column; align-items: flex-start; padding-top: 80px; }
    .sd-hero__poster { width: 130px; }
    .sd-hero__title  { font-size: 26px; }
    .sd-sidebar      { grid-template-columns: 1fr; }
    .sd-photo-strip  { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 480px) {
    .sd-block { padding: 20px 16px; }
    .sd-hero__poster { width: 100px; }
}

</style>

@endsection
