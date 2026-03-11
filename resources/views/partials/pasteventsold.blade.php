{{-- ============================================================
     PARTIAL: partials/pastevents.blade.php
     ============================================================ --}}

{{-- ── Hero Banner (replaces Section Header) ──────────────────── --}}
{{-- <div class="past-hero-banner">
    <img src="{{ asset('assets/images/events/pasteventshero.jpg') }}"
         alt="3SixtyShows — Legendary Past Events"
         class="past-hero-img" />
</div> --}}

{{-- ── Main Section ─────────────────────────────────────────────── --}}
<section class="events past-events-section">
    <div class="container">

        {{-- Section Heading --}}
        <div class="past-section-heading">
            <span class="past-section-eyebrow">
                <i class="fas fa-history"></i> Archive
            </span>
            <h2 class="past-section-title">Legendary Past Events</h2>
            <p class="past-section-sub">Relive the magic — every show a story, every night a memory.</p>
        </div>

        {{-- Events Grid --}}
        @if($pastEvents->count() > 0)

            <div class="events-grid">
                @foreach($pastEvents as $show)
                    <div class="event-card past-event-card">

                        <a href="{{ route('show.details', $show->slug) }}" class="event-link">

                            {{-- Image --}}
                            <div class="event-image-wrapper">
                                @if($show->featured_image)
                                    <img src="{{ asset('storage/' . $show->featured_image) }}"
                                         alt="{{ $show->title }}"
                                         loading="lazy" />
                                @else
                                    <img src="{{ asset('assets/images/event-placeholder.jpg') }}"
                                         alt="{{ $show->title }}"
                                         loading="lazy" />
                                @endif

                                {{-- "Past Event" ribbon overlay --}}
                                <div class="past-event-ribbon">
                                    <i class="fas fa-history"></i> Past Event
                                </div>

                                {{-- Date badge --}}
                                <div class="event-date-badge">
                                    <span class="day">{{ \Carbon\Carbon::parse($show->start_date)->format('d') }}</span>
                                    <span class="month">{{ \Carbon\Carbon::parse($show->start_date)->format('M Y') }}</span>
                                </div>

                                {{-- Hover overlay --}}
                                <div class="event-overlay">
                                    <span class="event-action">View Details</span>
                                </div>
                            </div>

                            {{-- Card Info --}}
                            <div class="event-info">

                                <h3 class="event-title">{{ $show->title }}</h3>

                                <div class="event-date">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ \Carbon\Carbon::parse($show->start_date)->format('D, d M Y') }}
                                    @if($show->start_time)
                                        &nbsp;·&nbsp;
                                        <i class="far fa-clock"></i>
                                        {{ \Carbon\Carbon::parse($show->start_time)->format('g:i A') }}
                                    @endif
                                </div>

                                @if($show->venue)
                                    <div class="event-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ $show->venue->name }}
                                        @if($show->venue->city)
                                            · {{ $show->venue->city }}{{ $show->venue->state ? ', ' . $show->venue->state : '' }}
                                        @endif
                                    </div>
                                @endif

                                {{-- Price row --}}
                                <div class="event-price-row">
                                    <div class="event-price">
                                        @if($show->ticketTypes && $show->ticketTypes->where('is_active', true)->count() > 0)
                                            @php
                                                $minPrice = $show->ticketTypes->where('is_active', true)->min('price');
                                            @endphp
                                            @if($minPrice > 0)
                                                <span class="from">From</span>
                                                ${{ number_format($minPrice, 2) }}
                                            @else
                                                Free
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </div>

                                    <span class="badge past-event-badge">
                                        <i class="fas fa-history"></i> Completed
                                    </span>
                                </div>

                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

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
                        @elseif(
                            abs($page - $pastEvents->currentPage()) == 3
                        )
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
            <div class="past-events-empty">
                <div class="past-empty-icon">
                    <i class="fas fa-history"></i>
                </div>
                <h3 class="past-empty-title">No Past Events Found</h3>
                <p class="past-empty-text">No past events are available yet. Check back soon.</p>
                <a href="{{ route('events') }}" class="btn-secondary-dark"
                   style="margin-top:24px; display:inline-flex; align-items:center; gap:8px;">
                    <i class="fas fa-calendar-alt"></i> View Upcoming Events
                </a>
            </div>

        @endif

    </div>
</section>


{{-- ══════════════════════════════════════════════════════════════
     INTERNAL CSS — scoped to past events only, zero bleed
     ══════════════════════════════════════════════════════════════ --}}
<style>

/* ── Hero Banner ──────────────────────────────────────────────── */
.past-hero-banner {
    width: 100%;
    line-height: 0;          /* removes inline-block gap under image */
    background: #0d0d0d;
}

.past-hero-img {
    width: 100%;
    height: auto;
    display: block;
    max-height: 320px;
    object-fit: cover;
    object-position: center center;
}

@media (max-width: 768px) {
    .past-hero-img { max-height: 200px; }
}

@media (max-width: 480px) {
    .past-hero-img { max-height: 160px; }
}

/* ── Section ──────────────────────────────────────────────────── */
.past-events-section {
    background-color: #EEEAE3;
    padding-top: 40px;
}

/* ── Section Heading ──────────────────────────────────────────── */
.past-section-heading {
    text-align: center;
    margin-bottom: 44px;
}

.past-section-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-family: var(--font-body);
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 3px;
    color: var(--color-crimson);
    background: rgba(200,16,46,0.07);
    border: 1px solid rgba(200,16,46,0.18);
    padding: 5px 16px;
    border-radius: var(--radius-pill);
    margin-bottom: 14px;
}

.past-section-eyebrow i { font-size: 10px; }

.past-section-title {
    font-family: var(--font-heading);
    font-size: clamp(1.9rem, 4vw, 2.8rem);
    font-weight: 700;
    color: var(--color-text-dark);
    text-transform: uppercase;
    letter-spacing: 3px;
    line-height: 1.1;
    margin-bottom: 14px;
}

/* Gold underline accent */
.past-section-title::after {
    content: '';
    display: block;
    width: 60px;
    height: 3px;
    background: var(--gradient-gold);
    margin: 12px auto 0;
    border-radius: var(--radius-pill);
}

.past-section-sub {
    font-family: var(--font-display);
    font-size: 1.05rem;
    color: var(--color-text-muted);
    font-style: italic;
    max-width: 500px;
    margin: 0 auto;
    line-height: 1.7;
}

/* ═══════════════════════════════════════════════════════════════
   PORTRAIT POSTER GRID
   Scoped to .past-events-section — never bleeds into events grid
   ═══════════════════════════════════════════════════════════════ */
.past-events-section .events-grid {
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 28px;
}

/* Override global 16/9 → 3/4 portrait */
.past-events-section .event-image-wrapper {
    aspect-ratio: 3 / 4;
}

.past-events-section .event-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
}

.past-events-section .event-info {
    padding: 16px 18px 18px;
}

/* ── Past Event Card ──────────────────────────────────────────── */
.past-event-card {
    opacity: 0.92;
    filter: saturate(0.85);
    transition: opacity var(--transition-slow), filter var(--transition-slow),
                transform var(--transition-slow), box-shadow var(--transition-slow);
}

.past-event-card:hover {
    opacity: 1;
    filter: saturate(1);
    transform: translateY(-8px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.14);
}

/* Past ribbon top-right */
.past-event-ribbon {
    position: absolute;
    top: 0;
    right: 0;
    background: rgba(13,13,13,0.75);
    color: rgba(255,255,255,0.75);
    font-family: var(--font-body);
    font-size: 9.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 5px 12px;
    border-bottom-left-radius: var(--radius-md);
    z-index: 3;
    display: flex;
    align-items: center;
    gap: 5px;
    backdrop-filter: blur(4px);
}
.past-event-ribbon i { font-size: 9px; }

/* Completed badge */
.past-event-badge {
    background: rgba(90,90,90,0.1);
    color: var(--color-text-muted);
    border: 1px solid rgba(90,90,90,0.2);
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: var(--radius-pill);
    font-family: var(--font-body);
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* ── Pagination ───────────────────────────────────────────────── */
.past-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    flex-wrap: wrap;
    margin-top: 56px;
    padding-bottom: 8px;
}

/* Prev / Next arrow buttons */
.past-pg-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--color-white);
    border: 1.5px solid #D8D2CA;
    color: var(--color-text-dark);
    font-size: 13px;
    text-decoration: none;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.past-pg-btn:hover {
    background: var(--color-crimson);
    border-color: var(--color-crimson);
    color: var(--color-white);
    box-shadow: 0 4px 14px rgba(200,16,46,0.35);
    transform: translateY(-1px);
}

.past-pg-btn--disabled {
    background: var(--color-off-white);
    border-color: #E8E2DA;
    color: #C0B8B0;
    cursor: not-allowed;
    pointer-events: none;
}

/* Page number pills */
.past-pg-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 6px;
    border-radius: 10px;
    background: var(--color-white);
    border: 1.5px solid #D8D2CA;
    color: var(--color-text-body);
    font-family: var(--font-body);
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.past-pg-num:hover {
    background: rgba(200,16,46,0.06);
    border-color: var(--color-crimson);
    color: var(--color-crimson);
    transform: translateY(-1px);
}

/* Active page */
.past-pg-num--active {
    background: var(--gradient-crimson);
    border-color: var(--color-crimson);
    color: var(--color-white);
    box-shadow: 0 4px 14px rgba(200,16,46,0.4);
    cursor: default;
    pointer-events: none;
}

/* Ellipsis */
.past-pg-ellipsis {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 40px;
    color: var(--color-text-muted);
    font-size: 16px;
    font-weight: 700;
    letter-spacing: 1px;
    flex-shrink: 0;
}

/* ── Empty State ──────────────────────────────────────────────── */
.past-events-empty {
    text-align: center;
    padding: 80px 20px;
}

.past-empty-icon {
    width: 90px;
    height: 90px;
    background: rgba(200,16,46,0.08);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
    border: 2px solid rgba(200,16,46,0.15);
}

.past-empty-icon i {
    font-size: 36px;
    color: var(--color-crimson);
    opacity: 0.6;
}

.past-empty-title {
    font-family: var(--font-heading);
    font-size: 1.6rem;
    color: var(--color-text-dark);
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 12px;
}

.past-empty-text {
    font-size: 1rem;
    color: var(--color-text-muted);
    max-width: 420px;
    margin: 0 auto 6px;
    line-height: 1.8;
}

/* ── Responsive ───────────────────────────────────────────────── */
@media (max-width: 992px) {
    .past-events-section .events-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .past-pagination { gap: 5px; margin-top: 40px; }
    .past-pg-btn,
    .past-pg-num     { width: 36px; height: 36px; font-size: 13px; }
}

@media (max-width: 576px) {
    .past-events-section .events-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }
    .past-pagination { gap: 4px; }
}

@media (max-width: 360px) {
    .past-events-section .events-grid { grid-template-columns: 1fr; }
}

</style>
