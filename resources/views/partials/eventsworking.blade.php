{{-- Events Section --}}
<section class="events" id="events">
    <div class="container">

        {{-- ── Section Header ── --}}
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

        {{-- ── Results count when searching ── --}}
        @if($isSearching)
            <p class="search-results-count">
                @if($shows->count() > 0)
                    {{ $shows->count() }} event{{ $shows->count() === 1 ? '' : 's' }} found
                @endif
            </p>
        @endif

        {{-- ══════════════════════════════════════════════════════
             EVENTS GRID
        ══════════════════════════════════════════════════════ --}}
        <div class="ot-grid">

            @forelse($shows as $show)

                @php
                    $isExternal = $show->redirect && $show->redirect_url;
                    $href       = $isExternal ? $show->redirect_url : route('contactus', $show->id);
                    $target     = $isExternal ? '_blank' : '_self';

                    // Prices
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

                <div class="ot-card">
                    <a href="{{ $href }}"
                       target="{{ $target }}"
                       @if($isExternal) rel="noopener noreferrer" @endif
                       class="ot-card__link">

                        {{-- ═══════════════════════════════════════
                             TOP BAR — dark strip with date + icon
                        ═══════════════════════════════════════ --}}
                        <div class="ot-card__topbar">
                            <span class="ot-card__topbar-date">
                                {{ \Carbon\Carbon::parse($show->start_date)->format('Y M d') }}
                            </span>
                            <span class="ot-card__topbar-icon">
                                @if($isExternal)
                                    <i class="fas fa-sync-alt"></i>
                                @else
                                    <i class="fas fa-ticket-alt"></i>
                                @endif
                            </span>
                        </div>

                        {{-- ═══════════════════════════════════════
                             PORTRAIT POSTER IMAGE
                        ═══════════════════════════════════════ --}}
                        <div class="ot-card__img-wrap">
                            <img src="{{ $show->featured_image
                                            ? asset('storage/' . $show->featured_image)
                                            : asset('assets/images/placeholder.jpg') }}"
                                 alt="{{ $show->title }}"
                                 class="ot-card__img"
                                 loading="lazy" />

                            {{-- Hover scrim --}}
                            <div class="ot-card__scrim">
                                <span class="ot-card__cta">
                                    @if($isExternal)
                                        <i class="fas fa-ticket-alt"></i> Book Now
                                    @else
                                        <i class="fas fa-envelope"></i> Contact Us
                                    @endif
                                </span>
                            </div>
                        </div>

                        {{-- ═══════════════════════════════════════
                             CARD BODY
                        ═══════════════════════════════════════ --}}
                        <div class="ot-card__body">

                            {{-- Category — small grey caps --}}
                            <span class="ot-card__cat">
                                {{ ($show->category ?? null) ? $show->category->name : 'Shows' }}
                            </span>

                            {{-- Title --}}
                            <h3 class="ot-card__title">{{ $show->title }}</h3>

                            {{-- Venue --}}
                            @if($show->venue)
                                <p class="ot-card__venue">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $show->venue->name }}@if($show->venue->city), {{ $show->venue->city }}@endif</span>
                                </p>
                            @endif

                            {{-- Divider --}}
                            <hr class="ot-card__hr" />

                            {{-- Price row --}}
                            @if($minPrice !== null)
                                <div class="ot-card__price-row">
                                    <span class="ot-price__label">Starts</span>
                                    <span class="ot-price__amount">{{ number_format($minPrice, 2) }}</span>
                                    @if($hasDiscount)
                                        <span class="ot-price__was">{{ number_format($maxPrice, 2) }}</span>
                                    @endif
                                    <span class="ot-price__currency">USD</span>
                                </div>
                            @endif

                            {{-- Countdown --}}
                            @if(!$isPast)
                                <div class="ot-card__cd
                                    {{ $isUrgent ? 'ot-card__cd--urgent' : '' }}
                                    {{ $isToday  ? 'ot-card__cd--today'  : '' }}">

                                    @if($isToday)
                                        <span class="ot-dot ot-dot--green"></span>
                                        <span>On Today</span>
                                    @elseif($isUrgent)
                                        <span class="ot-dot ot-dot--red"></span>
                                        <span>Sale Ends In &nbsp;<strong>{{ $daysLeft }} Day{{ $daysLeft === 1 ? '' : 's' }}</strong></span>
                                    @else
                                        <span class="ot-dot ot-dot--grey"></span>
                                        <span><strong>{{ $daysLeft }}</strong> Day{{ $daysLeft === 1 ? '' : 's' }} Away</span>
                                    @endif

                                </div>
                            @endif

                        </div>{{-- /.ot-card__body --}}

                    </a>
                </div>{{-- /.ot-card --}}

            @empty

                <div class="no-events-found">
                    @if($isSearching)
                        <div class="no-events-icon">🔍</div>
                        <h3>No Events Found</h3>
                        <p>No upcoming events match your search criteria.<br>
                           Try adjusting your filters or <a href="{{ route('events') }}">browse all events</a>.</p>
                        <div class="no-events-filters">
                            @if($location) <span class="filter-tag"><i class="fas fa-map-marker-alt"></i> {{ $location }}</span> @endif
                            @if($date)     <span class="filter-tag"><i class="far fa-calendar"></i> {{ \Carbon\Carbon::parse($date)->format('M j, Y') }}</span> @endif
                            @if($query)    <span class="filter-tag"><i class="fas fa-search"></i> {{ $query }}</span> @endif
                        </div>
                        <a href="{{ route('events') }}" class="btn-browse-all">
                            <i class="fas fa-list"></i> &nbsp;Browse All Events
                        </a>
                    @else
                        <div class="no-events-icon">🎭</div>
                        <h3>No Upcoming Events</h3>
                        <p>No upcoming events at this time. Check back soon!</p>
                    @endif
                </div>

            @endforelse

        </div>{{-- /.ot-grid --}}

    </div>
</section>


<style>
/* ═══════════════════════════════════════════════════════════════
   OpalTickets-style cards — all classes prefixed .ot-
   Zero collision with global style.css
═══════════════════════════════════════════════════════════════ */

/* ── Grid ─────────────────────────────────────────────────────── */
.ot-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
    align-items: start;   /* cards don't stretch — body stays tight */
}

/* ── Card shell ───────────────────────────────────────────────── */
.ot-card {
    background: #ffffff;
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #E2E2E2;
    box-shadow: 0 1px 3px rgba(0,0,0,0.07), 0 4px 14px rgba(0,0,0,0.06);
    transition: box-shadow .24s ease, transform .24s ease;
}

.ot-card:hover {
    box-shadow: 0 6px 30px rgba(0,0,0,0.14);
    transform: translateY(-4px);
}

.ot-card__link {
    display: block;
    text-decoration: none;
    color: inherit;
}

/* ── TOP BAR — solid dark strip ───────────────────────────────── */
.ot-card__topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #111111;
    padding: 8px 12px;
    min-height: 36px;
}

.ot-card__topbar-date {
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 12.5px;
    font-weight: 700;
    color: #FFFFFF;
    letter-spacing: 0.3px;
    line-height: 1;
}

.ot-card__topbar-icon {
    color: rgba(255,255,255,0.75);
    font-size: 14px;
    line-height: 1;
    flex-shrink: 0;
}

/* ── Portrait poster image ────────────────────────────────────── */
.ot-card__img-wrap {
    position: relative;
    width: 100%;
    /* Natural portrait ratio — image drives the height, no forced crop */
    line-height: 0;
    overflow: hidden;
    background: #0D0D0D;
}

.ot-card__img {
    width: 100%;
    height: auto;          /* let portrait image breathe at its own ratio */
    display: block;
    transition: transform .4s ease;
    object-fit: cover;
    object-position: center top;
}

.ot-card:hover .ot-card__img {
    transform: scale(1.04);
}

/* Hover scrim */
.ot-card__scrim {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top,
        rgba(10,10,10,.82)  0%,
        rgba(10,10,10,.22)  45%,
        transparent         100%);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding-bottom: 20px;
    opacity: 0;
    transition: opacity .28s ease;
    z-index: 2;
}

.ot-card:hover .ot-card__scrim { opacity: 1; }

.ot-card__cta {
    background: linear-gradient(135deg, #C8102E 0%, #9e0b22 100%);
    color: #fff;
    font-family: var(--font-body, 'DM Sans', sans-serif);
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
}

/* ── Card body ────────────────────────────────────────────────── */
.ot-card__body {
    padding: 13px 14px 15px;
    background: #ffffff;
}

/* "Shows" grey label */
.ot-card__cat {
    display: block;
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 10.5px;
    font-weight: 600;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 4px;
    line-height: 1;
}

/* Bold event title — 2-line max */
.ot-card__title {
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 15px;
    font-weight: 700;
    color: #111827;
    line-height: 1.35;
    margin: 0 0 7px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Venue row */
.ot-card__venue {
    display: flex;
    align-items: flex-start;
    gap: 5px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 12.5px;
    color: #6B7280;
    line-height: 1.4;
    margin: 0;
}

.ot-card__venue i {
    color: #C8102E;
    font-size: 11px;
    margin-top: 2px;
    flex-shrink: 0;
}

/* Thin horizontal rule between venue and price */
.ot-card__hr {
    border: none;
    border-top: 1px solid #F0F0F0;
    margin: 10px 0;
}

/* ── Price row ────────────────────────────────────────────────── */
.ot-card__price-row {
    display: flex;
    align-items: baseline;
    flex-wrap: wrap;
    gap: 5px;
    margin-bottom: 7px;
}

.ot-price__label {
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 12px;
    color: #6B7280;
    font-weight: 400;
}

.ot-price__amount {
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 16.5px;
    font-weight: 800;
    color: #111827;
    letter-spacing: -.3px;
}

/* Strikethrough original price */
.ot-price__was {
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 13px;
    font-weight: 500;
    color: #9CA3AF;
    text-decoration: line-through;
}

.ot-price__currency {
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 12px;
    font-weight: 600;
    color: #9CA3AF;
}

/* ── Countdown row ────────────────────────────────────────────── */
.ot-card__cd {
    display: flex;
    align-items: center;
    gap: 7px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 12px;
    color: #6B7280;
    line-height: 1.3;
}

.ot-card__cd--urgent { color: #DC2626; }
.ot-card__cd--today  { color: #16A34A; }
.ot-card__cd strong  { font-weight: 700; color: #374151; }
.ot-card__cd--urgent strong { color: #DC2626; }

/* Dot indicator */
.ot-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
    display: inline-block;
}

.ot-dot--red {
    background: #EF4444;
    box-shadow: 0 0 0 3px rgba(239,68,68,.20);
    animation: ot-pulse 1.6s infinite;
}

.ot-dot--green {
    background: #22C55E;
    box-shadow: 0 0 0 3px rgba(34,197,94,.20);
}

.ot-dot--grey { background: #D1D5DB; }

@keyframes ot-pulse {
    0%,100% { box-shadow: 0 0 0 3px rgba(239,68,68,.20); }
    50%      { box-shadow: 0 0 0 6px rgba(239,68,68,.08); }
}


/* ── Existing utility styles (no rename needed) ───────────────── */
.search-results-count {
    font-size: .85rem;
    color: #888;
    margin: -12px 0 24px;
    text-align: center;
}

.search-clear-link {
    color: #C8102E;
    text-decoration: none;
    font-size: .82rem;
    font-weight: 600;
    white-space: nowrap;
    transition: opacity .2s;
}
.search-clear-link:hover { opacity: .75; }
.search-clear-link i { margin-right: 3px; }

.no-events-found {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    background: #FAFAF9;
    border: 1.5px dashed #E5E7EB;
    border-radius: 14px;
}
.no-events-icon { font-size: 3rem; margin-bottom: 16px; }
.no-events-found h3 { font-size: 1.25rem; font-weight: 700; color: #111827; margin-bottom: 10px; }
.no-events-found p  { font-size: .9rem; color: #6B7280; line-height: 1.7; margin-bottom: 20px; }
.no-events-found p a { color: #C8102E; text-decoration: none; font-weight: 600; }

.no-events-filters {
    display: flex; flex-wrap: wrap;
    gap: 8px; justify-content: center; margin-bottom: 24px;
}
.filter-tag {
    background: #F3F4F6; border: 1px solid #E5E7EB; color: #374151;
    border-radius: 20px; padding: 5px 14px; font-size: .8rem;
    display: inline-flex; align-items: center; gap: 6px;
}
.filter-tag i { color: #C8102E; }

.btn-browse-all {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, #C8102E 0%, #9e0b22 100%);
    color: #fff; text-decoration: none;
    padding: 12px 28px; border-radius: 50px;
    font-size: .88rem; font-weight: 700;
    box-shadow: 0 4px 18px rgba(200,16,46,.30);
    transition: transform .2s, box-shadow .2s;
}
.btn-browse-all:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(200,16,46,.42);
}

/* ── Responsive ───────────────────────────────────────────────── */
@media (max-width: 1200px) {
    .ot-grid { grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); }
}

@media (max-width: 768px) {
    .ot-grid { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
}

@media (max-width: 600px) {
    .ot-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .ot-card__body { padding: 10px 11px 12px; }
    .ot-card__title { font-size: 13.5px; }
    .ot-card__topbar-date { font-size: 11px; }
}

@media (max-width: 360px) {
    .ot-grid { grid-template-columns: 1fr; }
}
</style>
