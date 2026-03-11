{{-- ============================================================
     PARTIAL: resources/views/partials/staff.blade.php
     Our Leadership Team — styled to match 3SixtyShows style.css
     Mirrors the .artists section pattern (dark bg, grid, cards)
     All team-specific classes prefixed .tm- to avoid collisions
============================================================ --}}

<section class="tm-section">

    {{-- Radial glow texture (matches .artists::before) --}}
    <div class="tm-section__bg" aria-hidden="true"></div>

    <div class="container">

        {{-- ── Section Header ── --}}
        <div class="section-header">
            <p class="section-subtitle">The People Behind The Magic</p>
            <h2 class="section-title tm-title">Our Leadership Team</h2>
            <p class="section-description">
                Passionate professionals dedicated to delivering unforgettable
                Bollywood experiences across North America.
            </p>
        </div>

        {{-- ── Team Grid ── --}}
        <div class="tm-grid">

            {{-- ─── Chloe Jones ─────────────────────────────── --}}
            <div class="tm-card">
                <div class="tm-card__img-wrap">
                    <img src="{{ asset('assets/images/team/chloejones.jpeg') }}"
                         alt="Chloe Jones" loading="lazy" />
                    <div class="tm-card__overlay">
                        <span class="tm-card__overlay-role">President &amp; CEO</span>
                    </div>
                </div>
                <div class="tm-card__info">
                    <h4 class="tm-card__name">Chloe Jones</h4>
                    <p class="tm-card__role">President &amp; CEO</p>
                </div>
            </div>

            {{-- ─── Mohammad Abbas ───────────────────────────── --}}
            <div class="tm-card">
                <div class="tm-card__img-wrap">
                    <img src="{{ asset('assets/images/team/mabbas.jpeg') }}"
                         alt="Mohammad Abbas" loading="lazy" />
                    <div class="tm-card__overlay">
                        <span class="tm-card__overlay-role">Managing Director</span>
                    </div>
                </div>
                <div class="tm-card__info">
                    <h4 class="tm-card__name">Mohammad Abbas</h4>
                    <p class="tm-card__role">Managing Director</p>
                </div>
            </div>

            {{-- ─── Nafiz Hossain ────────────────────────────── --}}
            <div class="tm-card">
                <div class="tm-card__img-wrap">
                    <img src="{{ asset('assets/images/team/nafizhossain.jpeg') }}"
                         alt="Nafiz Hossain" loading="lazy" />
                    <div class="tm-card__overlay">
                        <span class="tm-card__overlay-role">Vice President &amp; CFO</span>
                    </div>
                </div>
                <div class="tm-card__info">
                    <h4 class="tm-card__name">Nafiz Hossain</h4>
                    <p class="tm-card__role">Vice President &amp; CFO</p>
                </div>
            </div>

            {{-- ─── Uzma Abbas ───────────────────────────────── --}}
            <div class="tm-card">
                <div class="tm-card__img-wrap">
                    <img src="{{ asset('assets/images/team/uzmaabbas.png') }}"
                         alt="Uzma Abbas" loading="lazy" />
                    <div class="tm-card__overlay">
                        <span class="tm-card__overlay-role">COO</span>
                    </div>
                </div>
                <div class="tm-card__info">
                    <h4 class="tm-card__name">Uzma Abbas</h4>
                    <p class="tm-card__role">COO</p>
                </div>
            </div>

            {{-- ─── Arafat Hossain Khan ──────────────────────── --}}
            <div class="tm-card">
                <div class="tm-card__img-wrap">
                    <img src="{{ asset('assets/images/team/arafatnew.jpg') }}"
                         alt="Arafat Hossain Khan" loading="lazy" />
                    <div class="tm-card__overlay">
                        <span class="tm-card__overlay-role">Chief Technical Officer</span>
                    </div>
                </div>
                <div class="tm-card__info">
                    <h4 class="tm-card__name">Arafat Hossain Khan</h4>
                    <p class="tm-card__role">Chief Technical Officer (CTO)</p>
                </div>
            </div>

            {{-- ─── Kalpesh Ramani ───────────────────────────── --}}
            <div class="tm-card">
                <div class="tm-card__img-wrap">
                    <img src="{{ asset('assets/images/team/kalpeshramani.jpeg') }}"
                         alt="Kalpesh Ramani" loading="lazy" />
                    <div class="tm-card__overlay">
                        <span class="tm-card__overlay-role">Director of Logistics</span>
                    </div>
                </div>
                <div class="tm-card__info">
                    <h4 class="tm-card__name">Kalpesh Ramani</h4>
                    <p class="tm-card__role">Director of Logistics</p>
                </div>
            </div>

            {{-- ─── Rachel Samuel ────────────────────────────── --}}
            <div class="tm-card">
                <div class="tm-card__img-wrap">
                    <img src="{{ asset('assets/images/team/rachel.png') }}"
                         alt="Rachel Samuel" loading="lazy" />
                    <div class="tm-card__overlay">
                        <span class="tm-card__overlay-role">Director Operations</span>
                    </div>
                </div>
                <div class="tm-card__info">
                    <h4 class="tm-card__name">Rachel Samuel</h4>
                    <p class="tm-card__role">Director Operations</p>
                </div>
            </div>

            {{-- ─── Shahab Siddiqi ───────────────────────────── --}}
            <div class="tm-card">
                <div class="tm-card__img-wrap">
                    <img src="{{ asset('assets/images/team/shahabsiddiqui.jpeg') }}"
                         alt="Shahab Siddiqi" loading="lazy" />
                    <div class="tm-card__overlay">
                        <span class="tm-card__overlay-role">Marketing Director</span>
                    </div>
                </div>
                <div class="tm-card__info">
                    <h4 class="tm-card__name">Shahab Siddiqi</h4>
                    <p class="tm-card__role">Marketing Director</p>
                </div>
            </div>

            {{-- ─── Shahzad Ali Dar ──────────────────────────── --}}
            <div class="tm-card">
                <div class="tm-card__img-wrap">
                    <img src="{{ asset('assets/images/team/shahzadalidar.jpeg') }}"
                         alt="Shahzad Ali Dar" loading="lazy" />
                    <div class="tm-card__overlay">
                        <span class="tm-card__overlay-role">Sales Director</span>
                    </div>
                </div>
                <div class="tm-card__info">
                    <h4 class="tm-card__name">Shahzad Ali Dar</h4>
                    <p class="tm-card__role">Sales Director</p>
                </div>
            </div>

            {{-- ─── Lalith Choudary ──────────────────────────── --}}
            <div class="tm-card">
                <div class="tm-card__img-wrap">
                    <img src="{{ asset('assets/images/team/lalithchoudary.jpeg') }}"
                         alt="Lalith Choudary" loading="lazy" />
                    <div class="tm-card__overlay">
                        <span class="tm-card__overlay-role">Box Office &amp; Ticketing Manager</span>
                    </div>
                </div>
                <div class="tm-card__info">
                    <h4 class="tm-card__name">Lalith Choudary</h4>
                    <p class="tm-card__role">Box Office &amp; Ticketing Manager</p>
                </div>
            </div>

            {{-- ─── Satwik Reddy ─────────────────────────────── --}}
            <div class="tm-card">
                <div class="tm-card__img-wrap">
                    <img src="{{ asset('assets/images/team/satwikreddy.jpeg') }}"
                         alt="Satwik Reddy" loading="lazy" />
                    <div class="tm-card__overlay">
                        <span class="tm-card__overlay-role">Marketing Manager</span>
                    </div>
                </div>
                <div class="tm-card__info">
                    <h4 class="tm-card__name">Satwik Reddy</h4>
                    <p class="tm-card__role">Marketing Manager</p>
                </div>
            </div>

            {{-- ─── Milan Dhakal ─────────────────────────────── --}}
            <div class="tm-card">
                <div class="tm-card__img-wrap">
                    <img src="{{ asset('assets/images/team/milandhakal.jpeg') }}"
                         alt="Milan Dhakal" loading="lazy" />
                    <div class="tm-card__overlay">
                        <span class="tm-card__overlay-role">Director Cinematics</span>
                    </div>
                </div>
                <div class="tm-card__info">
                    <h4 class="tm-card__name">Milan Dhakal</h4>
                    <p class="tm-card__role">Director Cinematics</p>
                </div>
            </div>

            {{-- ─── Rebecca Samuel ───────────────────────────── --}}
            <div class="tm-card">
                <div class="tm-card__img-wrap">
                    <img src="{{ asset('assets/images/team/Rebecanew.png') }}"
                         alt="Rebecca Samuel" loading="lazy" />
                    <div class="tm-card__overlay">
                        <span class="tm-card__overlay-role">Transportation Coordinator</span>
                    </div>
                </div>
                <div class="tm-card__info">
                    <h4 class="tm-card__name">Rebecca Samuel</h4>
                    <p class="tm-card__role">Transportation Coordinator</p>
                </div>
            </div>

            {{-- ─── Sarah Esther ─────────────────────────────── --}}
            <div class="tm-card">
                <div class="tm-card__img-wrap">
                    <img src="{{ asset('assets/images/team/sarahesther.jpeg') }}"
                         alt="Sarah Esther" loading="lazy" />
                    <div class="tm-card__overlay">
                        <span class="tm-card__overlay-role">Transportation Coordinator</span>
                    </div>
                </div>
                <div class="tm-card__info">
                    <h4 class="tm-card__name">Sarah Esther</h4>
                    <p class="tm-card__role">Transportation Coordinator</p>
                </div>
            </div>

        </div>{{-- /.tm-grid --}}
    </div>
</section>


{{-- ============================================================
     INTERNAL CSS — all classes prefixed .tm-
     Mirrors .artists section from style.css exactly
============================================================ --}}
<style>

/* ── Section shell ─────────────────────────────────────────── */
.tm-section {
    padding: 90px 0;
    background-color: var(--color-onyx);
    position: relative;
    overflow: hidden;
}

/* Radial glow — identical to .artists::before */
.tm-section__bg {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse at 20% 50%, rgba(200,16,46,0.09) 0%, transparent 55%),
        radial-gradient(ellipse at 80% 50%, rgba(212,160,23,0.06) 0%, transparent 55%);
    pointer-events: none;
}

/* Section header overrides for dark background */
.tm-section .section-subtitle  { color: var(--color-gold); }
.tm-title                       { color: var(--color-white); }
.tm-section .section-description{ color: rgba(255,255,255,0.55); }

/* ── Grid ──────────────────────────────────────────────────── */
/*  5 cols on wide desktop → naturally wraps to 4/3/2/1       */
.tm-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 26px;
    position: relative;
}

/* ── Card ──────────────────────────────────────────────────── */
.tm-card {
    text-align: center;
}

/* ── Photo wrapper ─────────────────────────────────────────── */
.tm-card__img-wrap {
    position: relative;
    border-radius: var(--radius-xl);
    overflow: hidden;
    margin-bottom: 14px;
    box-shadow: var(--shadow-card);
    transition: all var(--transition-slow);
    aspect-ratio: 1 / 1;           /* square container — fits all photo sizes */
    background: #1a1a1a;           /* dark fill behind any letterbox gaps */
}

/* Gold border glow on hover — identical to .artist-card:hover */
.tm-card:hover .tm-card__img-wrap {
    box-shadow: 0 8px 40px rgba(212,160,23,0.35), 0 0 0 2px var(--color-gold);
    transform: scale(1.02);
}

.tm-card__img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: contain;           /* whole photo visible, zero cropping */
    object-position: center center;
    display: block;
    transition: transform var(--transition-slow);
}

.tm-card:hover .tm-card__img-wrap img {
    transform: scale(1.06);
}

/* ── Hover overlay ─────────────────────────────────────────── */
.tm-card__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(13,13,13,0.90) 0%, transparent 60%);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding: 18px 12px;
    opacity: 0;
    transition: opacity var(--transition-base);
}

.tm-card:hover .tm-card__overlay {
    opacity: 1;
}

.tm-card__overlay-role {
    background: var(--gradient-crimson);
    color: var(--color-white);
    padding: 7px 16px;
    border-radius: var(--radius-pill);
    font-family: var(--font-body);
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    text-align: center;
    box-shadow: var(--shadow-red);
    display: block;
    line-height: 1.3;
}

/* ── Card info (below photo) ───────────────────────────────── */
.tm-card__info {
    padding: 0 4px 4px;
}

.tm-card__name {
    font-family: var(--font-heading);
    font-size: 1rem;
    color: var(--color-white);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    line-height: 1.2;
    margin-bottom: 5px;
}

.tm-card__role {
    color: var(--color-gold);
    font-family: var(--font-body);
    font-size: 0.78rem;
    font-weight: 500;
    letter-spacing: 0.3px;
    line-height: 1.35;
}

/* ── Responsive ────────────────────────────────────────────── */

/* Large desktop: 5 cols (default) */

/* Medium desktop / tablet landscape: 4 cols */
@media (max-width: 1200px) {
    .tm-grid { grid-template-columns: repeat(4, 1fr); gap: 22px; }
}

/* Tablet portrait: 3 cols */
@media (max-width: 992px) {
    .tm-section { padding: 70px 0; }
    .tm-grid { grid-template-columns: repeat(3, 1fr); gap: 20px; }
}

/* Large mobile: 2 cols */
@media (max-width: 768px) {
    .tm-section { padding: 60px 0; }
    .tm-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
    .tm-card__name { font-size: 0.9rem; }
    .tm-card__role { font-size: 0.74rem; }
}

/* Small mobile: 2 cols tight */
@media (max-width: 480px) {
    .tm-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .tm-card__img-wrap { border-radius: var(--radius-lg); margin-bottom: 10px; }
    .tm-card__name { font-size: 0.82rem; letter-spacing: 0.5px; }
    .tm-card__role { font-size: 0.7rem; }
    .tm-card__overlay-role { font-size: 9px; padding: 5px 10px; }
}

</style>
