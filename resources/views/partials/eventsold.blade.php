{{-- Events Section --}}

<style>
    /* ─── Variables ─────────────────────────────────────────────── */
    :root {
        --ot-section-bg:   #f3f4f6;
        --ot-card-bg:      #ffffff;
        --ot-card-radius:  12px;
        --ot-card-shadow:  0 2px 12px rgba(0,0,0,0.10);
        --ot-card-hover:   0 8px 32px rgba(0,0,0,0.18);
        --ot-header-bg:    #111827;
        --ot-header-text:  #f9fafb;
        --ot-accent:       #346AB4;
        --ot-text-dark:    #111827;
        --ot-text-mid:     #374151;
        --ot-text-muted:   #6b7280;
        --ot-divider:      #e5e7eb;
        --ot-cat-color:    #6b7280;
    }

    /* ─── Section ───────────────────────────────────────────────── */
    .ot-events-section {
        background: var(--ot-section-bg);
        padding: 60px 0 80px;
    }

    .ot-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 24px;
    }

    /* ─── Section Header ─────────────────────────────────────────── */
    .ot-section-header {
        text-align: center;
        margin-bottom: 44px;
    }

    .ot-section-header h2 {
        font-size: clamp(1.8rem, 3vw, 2.4rem);
        font-weight: 700;
        color: var(--ot-text-dark);
        letter-spacing: -0.02em;
        margin: 0 0 8px;
    }

    .ot-section-header p {
        color: var(--ot-text-muted);
        font-size: 0.95rem;
        margin: 0 0 4px;
    }

    .ot-section-note {
        font-size: 0.82rem;
        color: var(--ot-text-muted);
        font-style: italic;
        margin: 0;
    }

    /* ─── Grid ───────────────────────────────────────────────────── */
    .ot-events-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    @media (max-width: 1024px) {
        .ot-events-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 580px) {
        .ot-events-grid { grid-template-columns: 1fr; }
        .ot-events-section { padding: 40px 0 60px; }
    }

    /* ─── Card ───────────────────────────────────────────────────── */
    .ot-event-card {
        background: var(--ot-card-bg);
        border-radius: var(--ot-card-radius);
        overflow: hidden;
        box-shadow: var(--ot-card-shadow);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .ot-event-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--ot-card-hover);
    }

    .ot-event-card a.ot-card-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    /* ─── Dark header strip (date + icon) ────────────────────────── */
    .ot-card-header {
        background: var(--ot-header-bg);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
    }

    .ot-date-badge {
        color: var(--ot-header-text);
        font-size: 0.82rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        white-space: nowrap;
    }

    .ot-header-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1.5px solid rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255,255,255,0.7);
        font-size: 0.72rem;
        flex-shrink: 0;
        transition: background 0.2s, color 0.2s, border-color 0.2s;
    }

    .ot-event-card:hover .ot-header-icon {
        background: var(--ot-accent);
        color: #fff;
        border-color: var(--ot-accent);
    }

    /* ─── Portrait Poster ────────────────────────────────────────── */
    .ot-image-wrapper {
        position: relative;
        width: 100%;
        aspect-ratio: 3 / 4;
        overflow: hidden;
        background: #1a1a2e;
    }

    .ot-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
        display: block;
        transition: transform 0.4s ease;
    }

    .ot-event-card:hover .ot-image-wrapper img {
        transform: scale(1.04);
    }

    /* Hover overlay CTA */
    .ot-book-overlay {
        position: absolute;
        inset: 0;
        background: rgba(52, 106, 180, 0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .ot-event-card:hover .ot-book-overlay {
        opacity: 1;
    }

    .ot-book-btn {
        background: var(--ot-accent);
        color: #fff;
        padding: 10px 28px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
        letter-spacing: 0.03em;
        box-shadow: 0 4px 16px rgba(52,106,180,0.45);
    }

    /* ─── Card Body ──────────────────────────────────────────────── */
    .ot-card-body {
        padding: 14px 16px 16px;
        background: var(--ot-card-bg);
    }

    .ot-card-category {
        font-size: 0.72rem;
        font-weight: 500;
        color: var(--ot-cat-color);
        margin: 0 0 4px;
        letter-spacing: 0.02em;
    }

    .ot-card-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--ot-text-dark);
        margin: 0 0 8px;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .ot-venue-row {
        display: flex;
        align-items: flex-start;
        gap: 6px;
        font-size: 0.82rem;
        color: var(--ot-text-mid);
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .ot-venue-row i {
        color: var(--ot-text-muted);
        font-size: 0.78rem;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .ot-card-divider {
        height: 1px;
        background: var(--ot-divider);
        margin: 10px 0;
    }

    .ot-price-row {
        font-size: 0.82rem;
        color: var(--ot-text-muted);
    }

    .ot-price-row strong {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--ot-text-dark);
    }

    /* ─── Empty State ────────────────────────────────────────────── */
    .ot-no-events {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        color: var(--ot-text-muted);
    }
</style>

<section class="ot-events-section" id="events">
    <div class="ot-container">

        <div class="ot-section-header">
            <h2>Upcoming Events</h2>
            <p>Featured Concerts & Shows</p>
            <p class="ot-section-note">Click on Sonu Nigam poster to receive notifications</p>
        </div>

        <div class="ot-events-grid">

            @forelse($shows as $show)

                <div class="ot-event-card">

                    @if($show->redirect && $show->redirect_url)
                        <a href="{{ $show->redirect_url }}" target="_blank" rel="noopener noreferrer" class="ot-card-link">
                    @else
                        <a href="{{ route('contactus') }}" class="ot-card-link">
                    @endif

                        {{-- ── Dark header strip: date + icon ── --}}
                        <div class="ot-card-header">
                            <span class="ot-date-badge">
                                {{ \Carbon\Carbon::parse($show->start_date)->format('Y M d') }}
                            </span>
                            <div class="ot-header-icon">
                                @if($show->redirect && $show->redirect_url)
                                    <i class="fas fa-sync-alt"></i>
                                @else
                                    <i class="fas fa-envelope"></i>
                                @endif
                            </div>
                        </div>

                        {{-- ── Portrait Poster ── --}}
                        <div class="ot-image-wrapper">
                            <img
                                src="{{ $show->featured_image ? asset('storage/' . $show->featured_image) : asset('assets/images/placeholder.jpg') }}"
                                alt="{{ $show->title }}"
                                loading="lazy"
                            >
                            <div class="ot-book-overlay">
                                <div class="ot-book-btn">
                                    @if($show->redirect && $show->redirect_url)
                                        Book Now
                                    @else
                                        Contact Us
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- ── Card Body on white ── --}}
                        <div class="ot-card-body">

                            <p class="ot-card-category">
                                {{ $show->category ? $show->category->name : 'Shows' }}
                            </p>

                            <h3 class="ot-card-title">{{ $show->title }}</h3>

                            @if($show->venue)
                                <div class="ot-venue-row">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>
                                        {{ $show->venue->name }}@if($show->venue->city), {{ strtoupper($show->venue->city) }}@endif
                                    </span>
                                </div>
                            @endif

                            <div class="ot-card-divider"></div>

                            <div class="ot-price-row">
                                @if($show->price)
                                    Starts <strong>${{ number_format($show->price, 2) }} USD</strong>
                                @else
                                    <strong>Free / Contact for Pricing</strong>
                                @endif
                            </div>

                        </div>

                    </a>
                </div>

            @empty
                <div class="ot-no-events">
                    <p>No upcoming events at this time. Check back soon!</p>
                </div>
            @endforelse

        </div>
    </div>
</section>
