@extends('layouts.master')

@section('title', $show->title . ' — 3Sixtyshows')
@section('meta_description', $show->short_description ?? 'View details for ' . $show->title . ' on 3Sixtyshows.')
@section('og_title',    $show->title . ' — 3Sixtyshows')
@section('og_description', $show->short_description ?? '')

@section('content')

<style>
/* ══════════════════════════════════════════════════════════════
   SHOW DETAIL PAGE — Complete Redesign
   Layout: Breadcrumb → Heading Box → Full Poster → Detail Body
   ══════════════════════════════════════════════════════════════ */

/* ── 1. Hero wrapper ─────────────────────────────────────────── */
.sd-hero {
    background: #F5F5F5;
    padding: 36px 0 52px;
}

/* ── 2. Breadcrumb pill ──────────────────────────────────────── */
.sd-breadcrumb {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 28px;
    font-family: 'DM Sans', sans-serif;
}
.sd-breadcrumb__inner {
    display: inline-flex;
    align-items: center;
    flex-wrap: wrap;
    background: #fff;
    border: 1px solid #E2E2E2;
    border-radius: 999px;
    padding: 7px 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,.07);
    gap: 0;
}
.sd-breadcrumb__link {
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
.sd-breadcrumb__link i { font-size: 10px; color: #C8102E; }
.sd-breadcrumb__link:hover { color: #C8102E; text-decoration: none; }
.sd-breadcrumb__sep {
    display: inline-flex;
    align-items: center;
    margin: 0 8px;
    color: #D4A017;
    font-size: 11px;
    font-weight: 700;
    user-select: none;
}
.sd-breadcrumb__current {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 700;
    color: #C8102E;
    letter-spacing: .4px;
    text-transform: uppercase;
    max-width: 260px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* ── 3. Heading box (bordered, gold corners) ─────────────────── */
.sd-heading-box {
    position: relative;
    max-width: 820px;
    margin: 0 auto 36px;
    background: #fff;
    border: 1.5px solid #1a1a1a;
    padding: 32px 44px 32px;
    text-align: center;
}
.sd-heading-box::before,
.sd-heading-box::after {
    content: '';
    position: absolute;
    width: 22px;
    height: 22px;
    border-color: #D4A017;
    border-style: solid;
}
.sd-heading-box::before { top: -3px; left: -3px; border-width: 3px 0 0 3px; }
.sd-heading-box::after  { bottom: -3px; right: -3px; border-width: 0 3px 3px 0; }

.sd-cat-badge {
    display: block;
    font-family: 'Playfair Display', serif;
    font-size: clamp(.9rem, 1.5vw, 1.1rem);
    font-weight: 700;
    font-style: italic;
    color: #D4A017;
    letter-spacing: .03em;
    margin: 0 0 12px;
    background: none;
    padding: 0;
    border-radius: 0;
    text-transform: none;
}
.sd-heading-box__title {
    font-family: 'Oswald', sans-serif;
    font-size: clamp(1.6rem, 4vw, 2.6rem);
    font-weight: 700;
    color: #C8102E;
    text-transform: uppercase;
    letter-spacing: .06em;
    line-height: 1.08;
    margin: 0;
}
.sd-heading-box__bar {
    display: block;
    width: 56px;
    height: 3px;
    background: #D4A017;
    border-radius: 2px;
    margin: 14px auto 0;
}

/* ── 4. Full poster section ──────────────────────────────────── */
.sd-poster-section {
    background: #F5F5F5;
    padding: 0 0 48px;
}
/* Poster: centred, max 480px wide, max 80vh tall — always fits viewport */
.sd-poster-wrap {
    max-width: 480px;
    margin: 0 auto;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 40px rgba(0,0,0,.22), 0 2px 8px rgba(0,0,0,.10);
    border: 1px solid #E2E2E2;
    background: #111;
    line-height: 0;
}
.sd-poster-wrap img {
    width: 100%;
    max-height: 80vh;     /* never taller than 80% of viewport height */
    display: block;
    object-fit: contain;  /* full poster visible, no crop */
    object-position: center top;
}

/* ── 5. Body section ─────────────────────────────────────────── */
.sd-body {
    background: #F7F4F0;
    padding: 52px 0 64px;
}
.sd-body__grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 36px;
    align-items: start;
}

/* ── 6. Info strip — gold left-bar (matches .sh-desc / About Us) */
.sd-info-strip {
    display: inline-flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px 20px;
    margin-top: 20px;
    padding: 10px 22px;
    background: #FDF9F2;
    border: none !important;
    border-left: 3px solid #D4A017 !important;
    border-radius: 0 6px 6px 0;
    outline: none !important;
    box-shadow: none !important;
}
.sd-info-strip__item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    color: #444;
    font-weight: 500;
}
.sd-info-strip__item i {
    color: #C8102E;
    font-size: 12px;
    width: 15px;
    text-align: center;
    flex-shrink: 0;
}
.sd-info-strip__item strong { color: #111827; }
/* Gold dot divider between items */
.sd-info-strip__item + .sd-info-strip__item::before {
    content: '·';
    color: #D4A017;
    font-size: 16px;
    font-weight: 700;
    margin-right: 6px;
    line-height: 1;
}

/* Status badge */
.sd-status-badge {
    display: inline-flex;
    align-items: center;
    font-family: 'DM Sans', sans-serif;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 4px 14px;
    border-radius: 999px;
}
.sd-badge--upcoming { background: rgba(212,160,23,.12); color: #b88a00; border: 1px solid rgba(212,160,23,.4); }
.sd-badge--ongoing  { background: rgba(34,197,94,.12);  color: #16a34a; border: 1px solid rgba(34,197,94,.35); }
.sd-badge--past     { background: rgba(156,163,175,.12); color: #6B7280; border: 1px solid rgba(156,163,175,.3); }

/* ── 7. Content blocks ───────────────────────────────────────── */
.sd-block {
    background: #fff;
    border-radius: 12px;
    padding: 28px 30px;
    margin-bottom: 24px;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
}
.sd-block:last-child { margin-bottom: 0; }
.sd-block__heading {
    font-family: 'Oswald', sans-serif;
    font-size: 17px;
    font-weight: 700;
    color: #111827;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin: 0 0 16px;
    display: flex;
    align-items: center;
    gap: 9px;
    padding-bottom: 12px;
    border-bottom: 2px solid #F0EDE8;
}
.sd-block__heading i { color: #C8102E; }
.sd-description {
    font-family: 'DM Sans', sans-serif;
    font-size: 15px;
    color: #374151;
    line-height: 1.8;
}
.sd-performers {
    font-family: 'DM Sans', sans-serif;
    font-size: 15px;
    color: #374151;
    line-height: 1.7;
    margin: 0;
}
.sd-photo-strip { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; }
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

/* ── 8. Sidebar ──────────────────────────────────────────────── */
.sd-ticket-box {
    background: #0D0D0D;
    border-radius: 14px;
    padding: 26px 22px;
    margin-bottom: 20px;
    border: 1px solid rgba(200,16,46,.25);
    box-shadow: 0 8px 32px rgba(0,0,0,.22);
}
.sd-ticket-box__heading {
    font-family: 'Oswald', sans-serif;
    font-size: 16px;
    font-weight: 700;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: .8px;
    margin: 0 0 18px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.sd-ticket-box__heading i { color: #D4A017; }
.sd-ticket-list { list-style: none; margin: 0 0 20px; padding: 0; display: flex; flex-direction: column; gap: 10px; }
.sd-ticket-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 8px;
    padding: 10px 14px;
}
.sd-ticket-item__name  { font-family: 'DM Sans',sans-serif; font-size: 13.5px; color: rgba(255,255,255,.85); font-weight: 500; }
.sd-ticket-item__price { font-family: 'DM Sans',sans-serif; font-size: 15px; font-weight: 800; color: #D4A017; }
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
    box-shadow: 0 4px 20px rgba(200,16,46,.45);
    transition: transform .2s, box-shadow .2s;
}
.sd-book-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(200,16,46,.60); color: #fff; text-decoration: none; }
.sd-sold-out-notice,
.sd-no-tickets {
    font-family: 'DM Sans', sans-serif;
    font-size: 13.5px;
    color: rgba(255,255,255,.55);
    text-align: center;
    padding: 12px 0 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.sd-venue-card {
    background: #fff;
    border-radius: 12px;
    padding: 22px;
    box-shadow: 0 1px 4px rgba(0,0,0,.07);
    border-top: 3px solid #C8102E;
}
.sd-venue-card__heading {
    font-family: 'Oswald', sans-serif;
    font-size: 14px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .8px;
    color: #C8102E;
    margin: 0 0 12px;
    display: flex;
    align-items: center;
    gap: 7px;
}
.sd-venue-card__name { font-family: 'DM Sans',sans-serif; font-size: 15px; font-weight: 700; color: #111827; margin: 0 0 5px; }
.sd-venue-card__addr { font-family: 'DM Sans',sans-serif; font-size: 13.5px; color: #6B7280; margin: 0 0 3px; line-height: 1.5; }

/* ── 9. Back link ────────────────────────────────────────────── */
.sd-back-row { padding: 20px 0 40px; }
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

/* ── 10. Responsive ──────────────────────────────────────────── */
@media (max-width: 1024px) {
    .sd-body__grid { grid-template-columns: 1fr; }
    .sd-sidebar { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .sd-ticket-box, .sd-venue-card { margin-bottom: 0; }
}
@media (max-width: 768px) {
    .sd-hero { padding: 24px 0 36px; }
    .sd-heading-box { padding: 24px 22px 20px; margin-bottom: 24px; }
    .sd-poster-section { padding: 0 0 36px; }
    .sd-poster-wrap { border-radius: 8px; }
    .sd-info-strip { gap: 6px 12px; padding: 9px 16px; }
    .sd-body { padding: 32px 0 48px; }
    .sd-block { padding: 20px 18px; }
    .sd-sidebar { grid-template-columns: 1fr; }
    .sd-photo-strip { grid-template-columns: repeat(2,1fr); }
    .sd-breadcrumb__inner { padding: 6px 14px; border-radius: 12px; }
    .sd-breadcrumb__link, .sd-breadcrumb__current { font-size: 10px; }
    .sd-breadcrumb__sep { margin: 0 5px; }
}
@media (max-width: 480px) {
    .sd-heading-box { padding: 20px 16px 18px; }
    .sd-info-strip { flex-direction: column; gap: 6px; }
    .sd-info-strip__item + .sd-info-strip__item::before { display: none; }
    .sd-photo-strip { grid-template-columns: 1fr; }
    .sd-block { padding: 18px 14px; }
}
</style>

{{-- ── SECTION 1: Hero — Breadcrumb + Heading Box ───────────────── --}}
<section class="sd-hero">
    <div class="container">

        {{-- Breadcrumb pill --}}
        <nav class="sd-breadcrumb" aria-label="Breadcrumb">
            <div class="sd-breadcrumb__inner">
                <a href="{{ route('index') }}" class="sd-breadcrumb__link">
                    <i class="fas fa-home"></i> Home
                </a>
                <span class="sd-breadcrumb__sep">&#8250;</span>
                @if($show->start_date && \Carbon\Carbon::parse($show->start_date)->isPast())
                    <a href="{{ route('pastevents') }}" class="sd-breadcrumb__link">
                        <i class="fas fa-history"></i> Past Events
                    </a>
                @else
                    <a href="{{ route('events') }}" class="sd-breadcrumb__link">
                        <i class="fas fa-calendar-alt"></i> Events
                    </a>
                @endif
                <span class="sd-breadcrumb__sep">&#8250;</span>
                <span class="sd-breadcrumb__current">
                    <i class="fas fa-ticket-alt"></i> {{ Str::limit($show->title, 35) }}
                </span>
            </div>
        </nav>

        {{-- Bordered heading box — with info strip inside --}}
        @php
            $statusLabel = match($show->status) {
                'upcoming'         => ['label' => 'Upcoming',   'class' => 'sd-badge--upcoming'],
                'ongoing'          => ['label' => 'Ongoing',    'class' => 'sd-badge--ongoing'],
                'past','completed' => ['label' => 'Past Event', 'class' => 'sd-badge--past'],
                default            => ['label' => ucfirst($show->status ?? 'Event'), 'class' => ''],
            };
        @endphp
        <div class="sd-heading-box">
            @if($show->category)
                <span class="sd-cat-badge">{{ $show->category->name }}</span>
            @endif
            <h1 class="sd-heading-box__title">{{ $show->title }}</h1>
            <span class="sd-heading-box__bar"></span>

            {{-- Info strip inside box --}}
            <div class="sd-info-strip">
                @if($show->venue)
                <div class="sd-info-strip__item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $show->venue->name }}{{ $show->venue->city ? ', '.$show->venue->city : '' }}{{ $show->venue->state ? ', '.$show->venue->state : '' }}</span>
                </div>
                @endif
                <div class="sd-info-strip__item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ \Carbon\Carbon::parse($show->start_date)->format('l, F j, Y') }}</span>
                </div>
                @if($show->duration)
                <div class="sd-info-strip__item">
                    <i class="fas fa-clock"></i>
                    <span>{{ $show->duration }}{{ $show->age_restriction ? ' · '.$show->age_restriction : '' }}</span>
                </div>
                @endif
                <div class="sd-info-strip__item">
                    <span class="sd-status-badge {{ $statusLabel['class'] }}">{{ $statusLabel['label'] }}</span>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- ── SECTION 2: Full Event Poster ─────────────────────────────── --}}
<section class="sd-poster-section">
    <div class="container">
        <div class="sd-poster-wrap">
            <img src="{{ $show->featured_image ? asset('storage/' . $show->featured_image) : asset('assets/images/placeholder.jpg') }}"
                 alt="{{ $show->title }}"
                 loading="eager">
        </div>
    </div>
</section>

{{-- ── SECTION 3: Detail Body ────────────────────────────────────── --}}
<section class="sd-body">
    <div class="container">

        {{-- Main grid: left content + right sidebar --}}
        <div class="sd-body__grid">

            {{-- Left: description, performers, gallery --}}
            <div class="sd-body__main">
                @if($show->description)
                    <div class="sd-block">
                        <h2 class="sd-block__heading"><i class="fas fa-info-circle"></i> About This Show</h2>
                        <div class="sd-description">{!! nl2br(e($show->description)) !!}</div>
                    </div>
                @endif

                @if($show->performers)
                    <div class="sd-block">
                        <h2 class="sd-block__heading"><i class="fas fa-star"></i> Performers</h2>
                        <p class="sd-performers">{{ $show->performers }}</p>
                    </div>
                @endif

                @if($show->photos && $show->photos->count() > 0)
                    <div class="sd-block">
                        <h2 class="sd-block__heading"><i class="fas fa-images"></i> Gallery</h2>
                        <div class="sd-photo-strip">
                            @foreach($show->photos->take(6) as $photo)
                                <div class="sd-photo-strip__item">
                                    <img src="{{ $photo->image_url ?? asset('storage/' . $photo->image) }}"
                                         alt="{{ $show->title }}" loading="lazy" class="sd-photo-strip__img">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right: sidebar --}}
            <aside class="sd-sidebar">
                <div class="sd-ticket-box">
                    <h3 class="sd-ticket-box__heading">
                        <i class="fas fa-ticket-alt"></i> Tickets
                    </h3>

                    @php $activeTypes = $show->activeTicketTypes ?? collect(); @endphp

                    @if($activeTypes->count() > 0)
                        <ul class="sd-ticket-list">
                            @foreach($activeTypes as $type)
                                <li class="sd-ticket-item">
                                    <span class="sd-ticket-item__name">{{ $type->name }}</span>
                                    <span class="sd-ticket-item__price">
                                        {{ $type->price > 0 ? '$'.number_format($type->price,2) : 'Free' }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if($eventPassed)
                        <div class="sd-sold-out-notice">
                            <i class="fas fa-history"></i> This show has ended.
                        </div>
                    @elseif($isSoldOut)
                        <div class="sd-sold-out-notice">
                            <i class="fas fa-times-circle"></i> Sold Out
                        </div>
                    @elseif($show->isSeatsIoReady() && $show->isSaleOpen())
                        @include('partials.seating-map', ['show' => $show, 'holdToken' => $holdToken])
                    @elseif(in_array($show->ticketing_mode, ['general_admission','mixed']) && $show->isSaleOpen())
                        <a href="{{ route('ga-booking.tickets', $show->slug) }}" class="sd-book-btn">
                            <i class="fas fa-ticket-alt"></i> Get Tickets
                        </a>
                    @elseif($activeTypes->count() > 0)
                        <div class="sd-sold-out-notice">
                            <i class="fas fa-clock"></i> Tickets coming soon.
                        </div>
                    @else
                        <p class="sd-no-tickets">Ticket information not available yet.</p>
                    @endif
                </div>

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
                                {{ $show->venue->city }}{{ $show->venue->state ? ', '.$show->venue->state : '' }}{{ $show->venue->zip_code ? ' '.$show->venue->zip_code : '' }}
                            </p>
                        @endif
                    </div>
                @endif
            </aside>

        </div>
    </div>
</section>

{{-- Back link --}}
<div class="container sd-back-row">
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('pastevents') }}" class="sd-back-link">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

@endsection
