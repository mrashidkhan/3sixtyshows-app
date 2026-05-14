{{-- ============================================================
     PARTIAL: resources/views/partials/pastevents.blade.php
     Card layout: OpalTickets portrait-poster style (otc- system)
     Mirrors events.blade.php — all classes prefixed .otc- / .pe-
     zero bleed from/to global style.css
============================================================ --}}

{{-- ── Hero Banner (optional — uncomment to enable) ───────────── --}}
{{-- <div class="past-hero-banner">
    <img src="{{ asset('assets/images/events/pasteventshero.jpg') }}"
         alt="3SixtyShows — Legendary Past Events"
         class="past-hero-img" />
</div> --}}

<section class="events past-events-section" id="past-events">
    <div class="container">

        {{-- Section Heading --}}
        <div class="section-header">
            <span class="pe-eyebrow">
                <i class="fas fa-history"></i> Archive
            </span>
            <h2 class="section-title">Legendary Past Events</h2>
            <p class="section-subtitle">Relive the magic — every show a story, every night a memory.</p>
        </div>

        {{-- ══════════════════════════════════════
             EVENTS GRID
        ══════════════════════════════════════ --}}
        @if($pastEvents->count() > 0)

            <div class="otc-grid">
                @foreach($pastEvents as $show)

                    <div class="otc-card pe-card">
                        {{-- Guard: only make clickable if the show has a valid slug --}}
                        @if($show->slug)
                        <a href="{{ route('show.details', $show->slug) }}" class="otc-card__link">
                        @else
                        <div class="otc-card__link" style="cursor:default;">
                        @endif

                            {{-- ① DARK TOP BAR --}}
                            <div class="otc-topbar pe-topbar">
                                <span class="otc-topbar__date">
                                    {{ \Carbon\Carbon::parse($show->start_date)->format('Y M d') }}
                                </span>
                                <span class="otc-topbar__icon">
                                    <i class="fas fa-history"></i>
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

                                {{-- "Past Event" ribbon (top-right corner) --}}
                                <div class="pe-ribbon">
                                    <i class="fas fa-history"></i> Past Event
                                </div>

                                <div class="otc-poster__hover">
                                    <span class="otc-poster__cta">
                                        <i class="fas fa-eye"></i> View Details
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

                                {{-- Price --}}
                                @php
                                    $activeTypes = ($show->ticketTypes ?? collect())->where('is_active', true);
                                    $minPrice    = $activeTypes->count() ? $activeTypes->min('price') : null;
                                @endphp

                                {{-- @if($minPrice !== null)
                                    <div class="otc-price">
                                        @if($minPrice > 0)
                                            <span class="otc-price__label">From</span>
                                            <span class="otc-price__amount">{{ number_format($minPrice, 2) }}</span>
                                            <span class="otc-price__currency">USD</span>
                                        @else
                                            <span class="otc-price__label">Free</span>
                                        @endif
                                    </div>
                                @endif --}}

                                {{-- Completed badge --}}
                                {{-- <div class="pe-completed-badge">
                                    <span class="pe-completed-badge__dot"></span>
                                    <span>Completed</span>
                                </div> --}}

                            </div>{{-- /.otc-body --}}
                        @if($show->slug)
                        </a>
                        @else
                        </div>
                        @endif
                    </div>{{-- /.otc-card --}}

                @endforeach
            </div>{{-- /.otc-grid --}}

            {{-- Pagination --}}
            @if($pastEvents->hasPages())
                <nav class="past-pagination" aria-label="Past events pagination">

                    {{-- Prev --}}
                    @if($pastEvents->onFirstPage())
                        <span class="past-pg-btn past-pg-btn--disabled" aria-disabled="true">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $pastEvents->previousPageUrl() }}" class="past-pg-btn" aria-label="Previous page">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Page numbers --}}
                    @foreach($pastEvents->getUrlRange(1, $pastEvents->lastPage()) as $page => $url)
                        @if($page == $pastEvents->currentPage())
                            <span class="past-pg-num past-pg-num--active" aria-current="page">{{ $page }}</span>
                        @elseif(
                            $page == 1 ||
                            $page == $pastEvents->lastPage() ||
                            abs($page - $pastEvents->currentPage()) <= 2
                        )
                            <a href="{{ $url }}" class="past-pg-num">{{ $page }}</a>
                        @elseif(abs($page - $pastEvents->currentPage()) == 3)
                            <span class="past-pg-ellipsis">&hellip;</span>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if($pastEvents->hasMorePages())
                        <a href="{{ $pastEvents->nextPageUrl() }}" class="past-pg-btn" aria-label="Next page">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="past-pg-btn past-pg-btn--disabled" aria-disabled="true">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif

                </nav>
            @endif

        @else

            {{-- Empty State --}}
            <div class="otc-empty">
                <div class="otc-empty__icon">🎭</div>
                <h3>No Past Events Found</h3>
                <p>No past events are available yet. Check back soon.</p>
                <a href="{{ route('events') }}" class="otc-browse-btn">
                    <i class="fas fa-calendar-alt"></i> View Upcoming Events
                </a>
            </div>

        @endif

    </div>
</section>


{{-- ============================================================
     INTERNAL CSS — all rules .otc- or .pe- prefixed, zero bleed
============================================================ --}}
<style>

/* ── Grid ──────────────────────────────────────────────────── */
.otc-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
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
}

.otc-card:hover {
    box-shadow: 0 8px 32px rgba(0,0,0,.15);
    transform: translateY(-4px);
}

/* Past event: slight desaturate at rest, full colour on hover */
.pe-card {
    opacity: 0.92;
    filter: saturate(0.82);
}
.pe-card:hover {
    opacity: 1;
    filter: saturate(1);
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
    background: #111827;
    padding: 9px 14px;
    height: 40px;
}

/* Past events: use a muted charcoal instead of pure black */
.pe-topbar { background: #2D3240; }

.otc-topbar__date {
    font-size: 11px;
    font-weight: 600;
    color: rgba(255,255,255,0.80);
    letter-spacing: .5px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}

.otc-topbar__icon {
    font-size: 13px;
    color: rgba(255,255,255,0.45);
}

/* ── ② Portrait poster ─────────────────────────────────────── */
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

.otc-card:hover .otc-poster__img { transform: scale(1.04); }

/* "Past Event" ribbon — top-right corner */
.pe-ribbon {
    position: absolute;
    top: 0;
    right: 0;
    background: rgba(13,13,13,0.72);
    color: rgba(255,255,255,0.80);
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 9.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 5px 12px;
    border-bottom-left-radius: 8px;
    z-index: 3;
    display: flex;
    align-items: center;
    gap: 5px;
    backdrop-filter: blur(4px);
}
.pe-ribbon i { font-size: 9px; }

/* Hover CTA overlay */
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
.otc-price__currency{ font-size: 12px;   color: #9CA3AF; font-weight: 600; }

/* ── Completed badge ───────────────────────────────────────── */
.pe-completed-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 12px;
    color: #9CA3AF;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}

.pe-completed-badge__dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #D1D5DB;
    flex-shrink: 0;
    display: inline-block;
}

/* ── Section heading extras ────────────────────────────────── */
.past-events-section {
    background-color: #EEEAE3;
    padding-top: 40px;
}

.pe-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 3px;
    color: #C8102E;
    background: rgba(200,16,46,0.07);
    border: 1px solid rgba(200,16,46,0.18);
    padding: 5px 16px;
    border-radius: 999px;
    margin-bottom: 14px;
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

/* ── Pagination ────────────────────────────────────────────── */
.past-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    flex-wrap: wrap;
    margin-top: 56px;
    padding-bottom: 8px;
}

.past-pg-btn {
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

.past-pg-btn:hover {
    background: #C8102E;
    border-color: #C8102E;
    color: #fff;
    box-shadow: 0 4px 14px rgba(200,16,46,.35);
    transform: translateY(-1px);
}

.past-pg-btn--disabled {
    background: #F5F3F0;
    border-color: #E8E2DA;
    color: #C0B8B0;
    cursor: not-allowed;
    pointer-events: none;
}

.past-pg-num {
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

.past-pg-num:hover {
    background: rgba(200,16,46,.06);
    border-color: #C8102E;
    color: #C8102E;
    transform: translateY(-1px);
}

.past-pg-num--active {
    background: linear-gradient(135deg, #C8102E 0%, #9e0b22 100%);
    border-color: #C8102E;
    color: #fff;
    box-shadow: 0 4px 14px rgba(200,16,46,.40);
    cursor: default;
    pointer-events: none;
}

.past-pg-ellipsis {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px; height: 40px;
    color: #9CA3AF;
    font-size: 16px;
    font-weight: 700;
    letter-spacing: 1px;
    flex-shrink: 0;
}

/* ── Hero banner (optional) ────────────────────────────────── */
.past-hero-banner { width: 100%; line-height: 0; background: #0d0d0d; }
.past-hero-img {
    width: 100%; height: auto; display: block;
    max-height: 320px; object-fit: cover; object-position: center;
}

/* ── Responsive ────────────────────────────────────────────── */
@media (max-width: 992px) {
    .otc-grid { grid-template-columns: repeat(2, 1fr); gap: 20px; }
}

@media (max-width: 575px) {
    .otc-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
    .otc-body        { padding: 10px 12px 12px; }
    .otc-body__title { font-size: 13.5px; }
    .otc-topbar      { height: 36px; padding: 7px 12px; }
    .past-pg-btn,
    .past-pg-num     { width: 36px; height: 36px; font-size: 13px; }
}

@media (max-width: 360px) {
    .otc-grid { grid-template-columns: 1fr; }
}

</style>
