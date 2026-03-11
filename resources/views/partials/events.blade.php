{{-- ============================================================
     PARTIAL: resources/views/partials/events.blade.php
     Card layout: OpalTickets portrait-poster style
     NOTE: All card classes prefixed .otc- to prevent ANY
           inheritance from global style.css (.event-card,
           .event-image-wrapper etc. are NOT used here)
============================================================ --}}
<section class="events" id="events">
    <div class="container">

        {{-- Section Header --}}
        <div class="section-header">
            <h2 class="section-title">
                @if($isSearching) Search Results @else Upcoming Events @endif
            </h2>
            <p class="section-subtitle">
                @if($isSearching)
                    @php
                        $filters = [];
                        if($location) $filters[] = 'Location: <strong>' . e($location) . '</strong>';
                        if($date)     $filters[] = 'Date: <strong>' . \Carbon\Carbon::parse($date)->format('M j, Y') . '</strong>';
                        if($query)    $filters[] = 'Keyword: <strong>' . e($query) . '</strong>';
                    @endphp
                    {!! implode(' &nbsp;·&nbsp; ', $filters) !!}
                    &nbsp;—&nbsp;
                    <a href="{{ route('events') }}" class="search-clear-link">
                        <i class="fas fa-times-circle"></i> Clear Search
                    </a>
                @else
                    Featured Concerts &amp; Shows
                @endif
            </p>
        </div>

        {{-- Results count --}}
        @if($isSearching)
            <p class="search-results-count">
                @if($shows->count() > 0)
                    {{ $shows->count() }} event{{ $shows->count() === 1 ? '' : 's' }} found
                @endif
            </p>
        @endif

        {{-- ══════════════════════════════════════
             EVENTS GRID
        ══════════════════════════════════════ --}}
        <div class="otc-grid">

            @forelse($shows as $show)
                @php
                    $isExternal = $show->redirect && $show->redirect_url;
                    $href       = $isExternal ? $show->redirect_url : route('contactus', $show->id);
                    $target     = $isExternal ? '_blank' : '_self';

                    // Prices from ticket types
                    $activeTypes = ($show->ticketTypes ?? collect())->where('is_active', true);
                    $minPrice    = $activeTypes->count() ? $activeTypes->min('price') : null;
                    $maxPrice    = $activeTypes->count() ? $activeTypes->max('price') : null;
                    $hasDiscount = $maxPrice && $maxPrice > $minPrice;

                    // Countdown
                    $startDt  = \Carbon\Carbon::parse($show->start_date);
                    $daysLeft = (int) \Carbon\Carbon::now()->diffInDays($startDt, false);
                    $isToday  = $daysLeft === 0;
                    $isUrgent = $daysLeft > 0 && $daysLeft <= 7;
                    $isPast   = $daysLeft < 0;
                @endphp

                <div class="otc-card">
                    <a href="{{ $href }}"
                       target="{{ $target }}"
                       @if($isExternal) rel="noopener noreferrer" @endif
                       class="otc-card__link">

                        {{-- ① DARK TOP BAR --}}
                        <div class="otc-topbar">
                            <span class="otc-topbar__date">
                                {{ \Carbon\Carbon::parse($show->start_date)->format('Y M d') }}
                            </span>
                            <span class="otc-topbar__icon">
                                <i class="fas {{ $isExternal ? 'fa-sync-alt' : 'fa-ticket-alt' }}"></i>
                            </span>
                        </div>

                        {{-- ② PORTRAIT POSTER IMAGE --}}
                        <div class="otc-poster">
                            <img
                                src="{{ $show->featured_image
                                        ? asset('storage/' . $show->featured_image)
                                        : asset('assets/images/placeholder.jpg') }}"
                                alt="{{ $show->title }}"
                                class="otc-poster__img"
                                loading="lazy"
                            />
                            <div class="otc-poster__hover">
                                <span class="otc-poster__cta">
                                    <i class="fas {{ $isExternal ? 'fa-ticket-alt' : 'fa-envelope' }}"></i>
                                    {{ $isExternal ? 'Book Now' : 'Contact Us' }}
                                </span>
                            </div>
                        </div>

                        {{-- ③ CARD BODY --}}
                        <div class="otc-body">

                            <span class="otc-body__cat">
                                {{ ($show->category ?? null) ? $show->category->name : 'Shows' }}
                            </span>

                            <h3 class="otc-body__title">{{ $show->title }}</h3>

                            @if($show->venue)
                                <p class="otc-body__venue">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $show->venue->name }}@if($show->venue->city), {{ $show->venue->city }}@endif</span>
                                </p>
                            @endif

                            <hr class="otc-body__hr" />

                            @if($minPrice !== null)
                                <div class="otc-price">
                                    <span class="otc-price__label">Starts</span>
                                    <span class="otc-price__amount">{{ number_format($minPrice, 2) }}</span>
                                    @if($hasDiscount)
                                        <span class="otc-price__was">{{ number_format($maxPrice, 2) }}</span>
                                    @endif
                                    <span class="otc-price__currency">USD</span>
                                </div>
                            @endif

                            @if(!$isPast)
                                <div class="otc-cd {{ $isUrgent ? 'otc-cd--urgent' : '' }} {{ $isToday ? 'otc-cd--today' : '' }}">
                                    <span class="otc-cd__dot {{ $isToday ? 'otc-cd__dot--green' : ($isUrgent ? 'otc-cd__dot--red' : 'otc-cd__dot--grey') }}"></span>
                                    @if($isToday)
                                        <span>On Today</span>
                                    @elseif($isUrgent)
                                        <span>Sale Ends In &nbsp;<strong>{{ $daysLeft }} Day{{ $daysLeft === 1 ? '' : 's' }}</strong></span>
                                    @else
                                        <span><strong>{{ $daysLeft }}</strong> Day{{ $daysLeft === 1 ? '' : 's' }} Away</span>
                                    @endif
                                </div>
                            @endif

                        </div>{{-- /.otc-body --}}
                    </a>
                </div>{{-- /.otc-card --}}

            @empty
                <div class="otc-empty">
                    @if($isSearching)
                        <div class="otc-empty__icon">🔍</div>
                        <h3>No Events Found</h3>
                        <p>No upcoming events match your search.<br>Try adjusting your filters or <a href="{{ route('events') }}">browse all events</a>.</p>
                        <div class="otc-empty__tags">
                            @if($location) <span class="otc-tag"><i class="fas fa-map-marker-alt"></i> {{ $location }}</span> @endif
                            @if($date)     <span class="otc-tag"><i class="far fa-calendar"></i> {{ \Carbon\Carbon::parse($date)->format('M j, Y') }}</span> @endif
                            @if($query)    <span class="otc-tag"><i class="fas fa-search"></i> {{ $query }}</span> @endif
                        </div>
                        <a href="{{ route('events') }}" class="otc-browse-btn"><i class="fas fa-list"></i> Browse All Events</a>
                    @else
                        <div class="otc-empty__icon">🎭</div>
                        <h3>No Upcoming Events</h3>
                        <p>No upcoming events at this time. Check back soon!</p>
                    @endif
                </div>
            @endforelse

        </div>{{-- /.otc-grid --}}
    </div>
</section>


{{-- ============================================================
     INTERNAL CSS
     All rules use .otc- prefix — zero bleed from/to style.css
     The global .event-image-wrapper { aspect-ratio:16/9 } rule
     in style.css does NOT apply here because that class is
     never used in this template.
============================================================ --}}
<style>

/* ── Grid ──────────────────────────────────────────────────── */
/*
   Desktop  (≥ 769px) : exactly 4 equal columns
   Tablet   (576–768px): exactly 2 columns
   Mobile   (≤ 575px) : 1 full-width column
*/
.otc-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    align-items: start;
}

/* ── Card shell ────────────────────────────────────────────── */
.otc-card {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #E2E2E2;
    box-shadow: 0 1px 3px rgba(0,0,0,.07), 0 3px 12px rgba(0,0,0,.06);
    transition: box-shadow .22s ease, transform .22s ease;
    /* Do NOT set a fixed height — let content define it */
}

.otc-card:hover {
    box-shadow: 0 8px 32px rgba(0,0,0,.15);
    transform: translateY(-4px);
}

.otc-card__link {
    display: block;
    text-decoration: none;
    color: inherit;
}

/* ── ① Dark top bar ────────────────────────────────────────── */
.otc-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #111;
    padding: 8px 13px;
    height: 38px;
    flex-shrink: 0;
    border-bottom: 2.5px solid rgba(255,255,255,0.70);  /* white separator */
}

.otc-topbar__date {
    font-size: 12.5px;
    font-weight: 700;
    color: #fff;
    letter-spacing: .3px;
    line-height: 1;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}

.otc-topbar__icon {
    color: rgba(255,255,255,.72);
    font-size: 15px;
    line-height: 1;
}

/* ── ② Portrait poster image ───────────────────────────────── */
/*
   KEY FIX:
   - No aspect-ratio set here — the image renders at its
     natural dimensions (portrait posters are already taller
     than wide).
   - width:100% + height:auto means the card height is driven
     entirely by the image's own proportions.
   - overflow:hidden on .otc-card clips any slight overflows.
*/
.otc-poster {
    position: relative;
    width: 100%;
    line-height: 0;
    overflow: hidden;
    background: #111111;
    aspect-ratio: 1 / 1;
    padding-top: 6px;        /* gap between separator and poster top */
}

.otc-poster__img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: contain;       /* ← show full poster, no crop */
    object-position: center center;
    transition: transform .38s ease;
}

.otc-card:hover .otc-poster__img {
    transform: scale(1.04);
}

/* Hover scrim */
.otc-poster__hover {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to top,
        rgba(10,10,10,.82) 0%,
        rgba(10,10,10,.20) 45%,
        transparent        100%
    );
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding-bottom: 20px;
    opacity: 0;
    transition: opacity .26s ease;
    z-index: 2;
}

.otc-card:hover .otc-poster__hover { opacity: 1; }

.otc-poster__cta {
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

/* ── ③ Card body ───────────────────────────────────────────── */
.otc-body {
    padding: 12px 14px 14px;
    background: #fff;
}

/* "Shows" grey label */
.otc-body__cat {
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

/* Bold title — 2-line clamp */
.otc-body__title {
    font-size: 15px;
    font-weight: 700;
    color: #111827;
    line-height: 1.35;
    margin: 0 0 7px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}

/* Venue */
.otc-body__venue {
    display: flex;
    align-items: flex-start;
    gap: 5px;
    font-size: 12.5px;
    color: #6B7280;
    line-height: 1.4;
    margin: 0;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}

.otc-body__venue i {
    color: #C8102E;
    font-size: 11px;
    margin-top: 2px;
    flex-shrink: 0;
}

/* Thin divider */
.otc-body__hr {
    border: none;
    border-top: 1px solid #F0F0F0;
    margin: 10px 0;
}

/* ── Price row ─────────────────────────────────────────────── */
.otc-price {
    display: flex;
    align-items: baseline;
    flex-wrap: wrap;
    gap: 5px;
    margin-bottom: 7px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}

.otc-price__label   { font-size: 12px;   color: #6B7280; font-weight: 400; }
.otc-price__amount  { font-size: 16.5px; color: #111827; font-weight: 800; letter-spacing: -.3px; }
.otc-price__was     { font-size: 13px;   color: #9CA3AF; font-weight: 500; text-decoration: line-through; }
.otc-price__currency{ font-size: 12px;   color: #9CA3AF; font-weight: 600; }

/* ── Countdown ─────────────────────────────────────────────── */
.otc-cd {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 12px;
    color: #6B7280;
    line-height: 1.3;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}

.otc-cd--urgent { color: #DC2626; }
.otc-cd--today  { color: #16A34A; }
.otc-cd strong  { font-weight: 700; color: #374151; }
.otc-cd--urgent strong { color: #DC2626; }

.otc-cd__dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
    display: inline-block;
}

.otc-cd__dot--red {
    background: #EF4444;
    box-shadow: 0 0 0 3px rgba(239,68,68,.22);
    animation: otc-pulse 1.6s infinite;
}

.otc-cd__dot--green {
    background: #22C55E;
    box-shadow: 0 0 0 3px rgba(34,197,94,.22);
}

.otc-cd__dot--grey { background: #D1D5DB; }

@keyframes otc-pulse {
    0%,100% { box-shadow: 0 0 0 3px rgba(239,68,68,.22); }
    50%      { box-shadow: 0 0 0 7px rgba(239,68,68,.08); }
}

/* ── Empty state ───────────────────────────────────────────── */
.otc-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 70px 20px;
    background: #FAFAF9;
    border: 1.5px dashed #E5E7EB;
    border-radius: 14px;
}

.otc-empty__icon { font-size: 3rem; margin-bottom: 16px; }

.otc-empty h3 {
    font-size: 1.25rem; font-weight: 700;
    color: #111827; margin-bottom: 10px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}

.otc-empty p {
    font-size: .9rem; color: #6B7280;
    line-height: 1.7; margin-bottom: 20px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}

.otc-empty p a { color: #C8102E; text-decoration: none; font-weight: 600; }

.otc-empty__tags {
    display: flex; flex-wrap: wrap;
    gap: 8px; justify-content: center; margin-bottom: 24px;
}

.otc-tag {
    background: #F3F4F6; border: 1px solid #E5E7EB; color: #374151;
    border-radius: 20px; padding: 5px 14px; font-size: .8rem;
    display: inline-flex; align-items: center; gap: 6px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.otc-tag i { color: #C8102E; }

.otc-browse-btn {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, #C8102E 0%, #9e0b22 100%);
    color: #fff; text-decoration: none;
    padding: 11px 26px; border-radius: 50px;
    font-size: .88rem; font-weight: 700;
    box-shadow: 0 4px 18px rgba(200,16,46,.30);
    transition: transform .2s, box-shadow .2s;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.otc-browse-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(200,16,46,.42);
}

/* ── Search helpers ────────────────────────────────────────── */
.search-results-count {
    font-size: .85rem; color: #888;
    margin: -12px 0 24px; text-align: center;
}

.search-clear-link {
    color: #C8102E; text-decoration: none;
    font-size: .82rem; font-weight: 600;
    white-space: nowrap; transition: opacity .2s;
}
.search-clear-link:hover { opacity: .75; }
.search-clear-link i { margin-right: 3px; }

/* ── Responsive ────────────────────────────────────────────── */

/* Mobile: 1 full-width column */
@media (max-width: 575px) {
    .otc-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    .otc-body        { padding: 13px 14px 15px; }
    .otc-body__title { font-size: 15px; }
    .otc-topbar      { height: 38px; padding: 8px 13px; }
}

</style>
