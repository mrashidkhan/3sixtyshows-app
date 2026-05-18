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
          <div class="sh-box">
            <p class="sh-subtitle">The People Behind The Magic</p>
            <h2 class="sh-title">Our Leadership Team</h2>
            <span class="sh-bar"></span>
            <p class="sh-desc">Passionate professionals dedicated to delivering unforgettable Bollywood experiences across North America.</p>
          </div>
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
                    <img src="{{ asset('assets/images/team/uzmaabbas.jpg') }}"
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

/* ══════════════════════════════════════════════════════════════
   TEAM SECTION — Professional Light Theme
   ══════════════════════════════════════════════════════════════ */

/* ── Section shell ──────────────────────────────────────────── */
.tm-section {
    padding: 100px 0;
    background: #F8F9FA;
    position: relative;
    overflow: hidden;
}

/* Decorative background blobs */
.tm-section__bg {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse at 10% 20%, rgba(230,57,70,0.05) 0%, transparent 50%),
        radial-gradient(ellipse at 90% 80%, rgba(244,162,97,0.06) 0%, transparent 50%);
    pointer-events: none;
}

/* ── Section header overrides — screenshot style ───────────── */
.tm-section .section-subtitle {
    font-family: 'Playfair Display', Georgia, serif !important;
    font-size: 1.05rem !important;
    color: #D4A017 !important;
    font-weight: 700 !important;
    font-style: italic !important;
    text-transform: none !important;
    letter-spacing: 0.3px !important;
    margin-bottom: 6px !important;
    display: block !important;
}
.tm-title {
    font-family: 'Oswald', sans-serif !important;
    font-size: 2.5rem !important;
    color: #C8102E !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 2px !important;
    line-height: 1.1 !important;
    margin-bottom: 0 !important;
}
/* Gold underline bar — like screenshot */
.tm-title::after {
    content: '' !important;
    display: block !important;
    width: 60px !important;
    height: 3px !important;
    background: linear-gradient(90deg, #D4A017, #F0C040, #D4A017) !important;
    margin: 10px auto 0 !important;
    border-radius: 2px !important;
}
.tm-section .section-description {
    color: #6C757D !important;
    margin-top: 14px !important;
}

/* ── Grid ───────────────────────────────────────────────────── */
.tm-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 24px;
    position: relative;
}

/* ── Card ───────────────────────────────────────────────────── */
.tm-card {
    background: #FFFFFF;
    border-radius: 16px;
    overflow: hidden;
    text-align: center;
    border: 1px solid #E9ECEF;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    transition: all 0.30s cubic-bezier(0.23, 1, 0.32, 1);
    cursor: default;
}

.tm-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(0,0,0,0.12);
    border-color: rgba(230,57,70,0.18);
}

/* ── Photo wrapper ──────────────────────────────────────────── */
.tm-card__img-wrap {
    position: relative;
    overflow: hidden;
    aspect-ratio: 1 / 1;
    background: #F0F2F5;
}

.tm-card__img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top center;
    display: block;
    transition: transform 0.40s cubic-bezier(0.23, 1, 0.32, 1);
}

.tm-card:hover .tm-card__img-wrap img {
    transform: scale(1.07);
}

/* ── Hover overlay ──────────────────────────────────────────── */
.tm-card__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to top,
        rgba(26,26,46,0.90) 0%,
        rgba(26,26,46,0.30) 45%,
        transparent 75%
    );
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding: 16px 12px;
    opacity: 0;
    transition: opacity 0.26s ease;
}

.tm-card:hover .tm-card__overlay { opacity: 1; }

.tm-card__overlay-role {
    display: inline-block;
    background: linear-gradient(135deg, #E63946 0%, #c1121f 100%);
    color: #fff;
    padding: 5px 14px;
    border-radius: 999px;
    font-family: 'Inter', sans-serif;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    line-height: 1.4;
    text-align: center;
    box-shadow: 0 4px 14px rgba(230,57,70,0.40);
}

/* ── Card info (below photo) ────────────────────────────────── */
.tm-card__info {
    padding: 14px 14px 16px;
    border-top: 1px solid #F0F2F5;
    position: relative;
}

/* Red-to-amber accent top border */
.tm-card__info::before {
    content: '';
    position: absolute;
    top: 0; left: 20px; right: 20px;
    height: 2px;
    background: linear-gradient(90deg, #E63946, #F4A261);
    border-radius: 0 0 2px 2px;
    opacity: 0;
    transition: opacity 0.26s ease;
}
.tm-card:hover .tm-card__info::before { opacity: 1; }

.tm-card__name {
    font-family: 'Poppins', sans-serif;
    font-size: 0.88rem;
    color: #1A1A2E;
    font-weight: 700;
    text-transform: none;
    letter-spacing: 0;
    line-height: 1.3;
    margin-bottom: 5px;
}

.tm-card__role {
    font-family: 'Inter', sans-serif;
    font-size: 0.74rem;
    color: #6C757D;
    font-weight: 500;
    letter-spacing: 0.2px;
    line-height: 1.35;
}

/* ── Responsive ─────────────────────────────────────────────── */
@media (max-width: 1200px) {
    .tm-grid { grid-template-columns: repeat(4, 1fr); gap: 20px; }
}
@media (max-width: 992px) {
    .tm-section { padding: 72px 0; }
    .tm-grid { grid-template-columns: repeat(3, 1fr); gap: 18px; }
}
@media (max-width: 768px) {
    .tm-section { padding: 60px 0; }
    .tm-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
    .tm-card__name { font-size: 0.82rem; }
    .tm-card__role { font-size: 0.70rem; }
}
@media (max-width: 480px) {
    .tm-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .tm-card__info { padding: 10px 10px 12px; }
    .tm-card__name { font-size: 0.76rem; }
    .tm-card__overlay-role { font-size: 9px; padding: 4px 10px; }
}

</style>
