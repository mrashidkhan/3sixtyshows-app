<!-- Hero Section -->
<section class="hero" id="home" aria-label="3Sixtyshows - Bollywood Stars Live in Dallas">

    <!-- Animated shimmer particles -->
    <div class="hero-particles" aria-hidden="true">
        <span class="hero-particle"></span>
        <span class="hero-particle"></span>
        <span class="hero-particle"></span>
        <span class="hero-particle"></span>
        <span class="hero-particle"></span>
        <span class="hero-particle"></span>
        <span class="hero-particle"></span>
        <span class="hero-particle"></span>
    </div>

    <!-- Layered cinematic overlays -->
    <div class="hero-overlay"></div>
    <div class="hero-overlay-vignette"></div>

    <!-- Decorative gold line accent -->
    <div class="hero-accent-line" aria-hidden="true"></div>

    <!-- Content -->
    <div class="hero-content-wrapper">
        <div class="hero-content">

            <!-- Main heading — no box, no Welcome to, no Dallas Texas -->
            <div class="hero-sh-box">
                <h1 class="hero-sh-title">
                    <span class="hero-sh-brand">3Sixty<em>Shows</em></span>
                </h1>
                <span class="hero-sh-bar"></span>
                <p class="hero-sh-desc">
                    Biggest&nbsp;Bollywood&nbsp;Events&nbsp;in&nbsp;America
                </p>
            </div>

            <!-- CTA Buttons -->
            <div class="hero-buttons">
                <a href="#events" class="hero-btn hero-btn--primary">
                    <span>View Events</span>
                    <svg class="hero-btn-icon" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M4 10h12M11 5l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <a href="#contact" class="hero-btn hero-btn--secondary">
                    <span>Contact Us</span>
                </a>
            </div>

        </div>
    </div>

    <!-- Bottom fade -->
    <div class="hero-bottom-fade" aria-hidden="true"></div>

</section>

<style>
/* ════════════════════════════════════════════════
   HERO — Mobile-Responsive
   Key change: switched from <img> to CSS
   background-image so we control position/size
   at every breakpoint independently.
   ════════════════════════════════════════════════ */

.hero {
    position: relative;
    width: 100%;
    overflow: hidden;
    background-color: #120A14;

    /* Full-width banner — natural 4:1 ratio on desktop */
    background-image: url('{{ asset("assets/images/events/hero.png") }}');
    background-repeat: no-repeat;
    background-size: cover;
    background-position: left center;

    /* clamp: never shorter than 260px, tracks 25vw, caps at 540px */
    min-height: clamp(260px, 25vw, 540px);
}

/* ── Cinematic overlay ── */
.hero-overlay {
    position: absolute;
    inset: 0;
    z-index: 1;
    background: linear-gradient(
        to right,
        rgba(18,10,20,0.08) 0%,
        rgba(18,10,20,0.02) 40%,
        rgba(18,10,20,0.00) 55%,
        rgba(18,10,20,0.12) 100%
    );
}

/* ── Radial vignette ── */
.hero-overlay-vignette {
    position: absolute;
    inset: 0;
    z-index: 1;
    background: radial-gradient(
        ellipse 85% 95% at 50% 50%,
        transparent 40%,
        rgba(18,10,20,0.40) 100%
    );
}

/* ── Gold accent bar ── */
.hero-accent-line {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    z-index: 6;
    background: linear-gradient(
        90deg,
        transparent 0%, #D4A017 20%, #F0C040 50%, #D4A017 80%, transparent 100%
    );
    opacity: 0.85;
}

/* ── Bottom fade ── */
.hero-bottom-fade {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 80px;
    z-index: 2;
    background: linear-gradient(to top, rgba(18,10,20,0.75), transparent);
}

/* ── Shimmer Particles ── */
.hero-particles {
    position: absolute;
    inset: 0;
    z-index: 2;
    pointer-events: none;
    overflow: hidden;
}

.hero-particle {
    position: absolute;
    border-radius: 50%;
    background: #F0C040;
    opacity: 0;
    animation: hero-particle-rise var(--dur,6s) var(--delay,0s) ease-in-out infinite;
    width: var(--sz,4px);
    height: var(--sz,4px);
    left: var(--x,50%);
    bottom: -10px;
    box-shadow: 0 0 6px 1px rgba(240,192,64,0.6);
}

.hero-particle:nth-child(1) { --x:12%;  --sz:3px; --dur:7s;  --delay:0s;   }
.hero-particle:nth-child(2) { --x:25%;  --sz:5px; --dur:9s;  --delay:1.2s; }
.hero-particle:nth-child(3) { --x:38%;  --sz:3px; --dur:6s;  --delay:2.5s; }
.hero-particle:nth-child(4) { --x:52%;  --sz:4px; --dur:8s;  --delay:0.8s; }
.hero-particle:nth-child(5) { --x:65%;  --sz:6px; --dur:10s; --delay:3.1s; }
.hero-particle:nth-child(6) { --x:75%;  --sz:3px; --dur:7s;  --delay:1.7s; }
.hero-particle:nth-child(7) { --x:85%;  --sz:4px; --dur:8s;  --delay:0.3s; }
.hero-particle:nth-child(8) { --x:92%;  --sz:5px; --dur:9s;  --delay:2s;   }

@keyframes hero-particle-rise {
    0%   { transform:translateY(0) scale(0.5);     opacity:0;   }
    15%  { opacity:0.7; }
    80%  { opacity:0.3; }
    100% { transform:translateY(-90vh) scale(1.2); opacity:0;   }
}

/* ════════════════════════════════════
   CONTENT — Desktop (right-aligned)
   ════════════════════════════════════ */
.hero-content-wrapper {
    position: absolute;
    inset: 0;
    z-index: 3;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding: 0 5% 0 0;
    font-size: 1rem;
}

.hero-content {
    text-align: right;
    max-width: 520px;
    width: 100%;
    padding: 28px 0;
    animation: hero-content-in 0.9s cubic-bezier(0.22,1,0.36,1) both;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 24px;
}

@keyframes hero-content-in {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ── Hero sh-box — no border, transparent bg ── */
.hero-sh-box {
    display: inline-block;
    text-align: center;
    border: none;
    padding: 0;
    position: relative;
    background: transparent;
    backdrop-filter: none;
    -webkit-backdrop-filter: none;
}

/* No corner ticks needed */
.hero-sh-box::before,
.hero-sh-box::after { display: none; }

/* Crimson Oswald h1 — full title in crimson */
.hero-sh-title {
    font-family: 'Oswald', Impact, sans-serif;
    font-size: clamp(2.4rem, 5vw, 4.2rem);
    font-weight: 700;
    color: #C8102E;
    text-transform: uppercase;
    letter-spacing: 2px;
    line-height: 1.05;
    margin: 0;
    text-shadow: 0 2px 20px rgba(0,0,0,0.80), 0 0 40px rgba(200,16,46,0.30);
}

.hero-sh-brand {
    display: block;
    color: #C8102E;
}

/* "Shows" em — same color, same size, no override */
.hero-sh-brand em {
    font-style: normal;
    color: #C8102E;
    text-shadow: 0 2px 20px rgba(0,0,0,0.80), 0 0 40px rgba(200,16,46,0.30);
}

/* Gold bar */
.hero-sh-bar {
    display: block;
    width: 70px;
    height: 3px;
    background: linear-gradient(90deg, #D4A017, #F0C040, #D4A017);
    margin: 14px auto 12px;
    border-radius: 2px;
}

/* Gold italic description */
.hero-sh-desc {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(0.95rem, 1.8vw, 1.2rem);
    font-weight: 700;
    font-style: italic;
    color: #F0C040;
    margin: 0;
    letter-spacing: 0.3px;
    line-height: 1.4;
    text-shadow: 0 1px 8px rgba(0,0,0,0.60);
}

/* ── CTA Buttons ── */
.hero-buttons {
    display: flex;
    gap: 14px;
    justify-content: flex-end;
    flex-wrap: wrap;
}

.hero-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: 'DM Sans', sans-serif;
    font-weight: 700;
    font-size: 13px;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    text-decoration: none;
    padding: 12px 28px;
    border-radius: 3px;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.hero-btn::before {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(110deg, transparent 30%, rgba(255,255,255,0.12) 50%, transparent 70%);
    transform: translateX(-100%);
    transition: transform 0.45s ease;
}

.hero-btn:hover::before { transform: translateX(100%); }

.hero-btn--primary {
    background: linear-gradient(135deg, #C8102E 0%, #9e0b22 100%);
    color: #FFFFFF;
    border: 1px solid rgba(200,16,46,0.6);
    box-shadow: 0 4px 20px rgba(200,16,46,0.45), inset 0 1px 0 rgba(255,255,255,0.12);
}

.hero-btn--primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 32px rgba(200,16,46,0.60), inset 0 1px 0 rgba(255,255,255,0.12);
    color: #FFFFFF;
}

.hero-btn--secondary {
    background: transparent;
    color: #FFFFFF;
    border: 1px solid rgba(255,255,255,0.45);
}

.hero-btn--secondary:hover {
    border-color: #D4A017;
    color: #F0C040;
    transform: translateY(-3px);
    box-shadow: 0 6px 24px rgba(212,160,23,0.22);
}

.hero-btn-icon {
    width: 17px; height: 17px;
    flex-shrink: 0;
    transition: transform 0.25s ease;
}

.hero-btn--primary:hover .hero-btn-icon { transform: translateX(3px); }


/* ════════════════════════════════════════════════
   BREAKPOINTS
   ════════════════════════════════════════════════ */

/* ── Tablet ≤ 992px ──────────────────────────── */
@media (max-width: 992px) {

    .hero {
        background-position: 22% center;
        min-height: clamp(340px, 44vw, 480px);
    }

    .hero-overlay {
        background: linear-gradient(
            to bottom,
            rgba(18,10,20,0.25) 0%,
            rgba(18,10,20,0.52) 100%
        );
    }

    .hero-content-wrapper {
        justify-content: center;
        padding: 0 20px;
    }

    .hero-content {
        text-align: center;
        align-items: center;
        max-width: 580px;
        padding: 32px 20px;
    }

    .hero-buttons { justify-content: center; }
    .hero-sh-title { font-size: clamp(1.7rem, 4.5vw, 2.6rem); }
}

/* ── Mobile ≤ 768px ──────────────────────────── */
@media (max-width: 768px) {

    .hero {
        min-height: 480px;
        background-size: auto 100%;
        background-position: 24% top;
    }

    .hero-overlay {
        background: linear-gradient(
            175deg,
            rgba(18,10,20,0.15) 0%,
            rgba(18,10,20,0.62) 55%,
            rgba(18,10,20,0.82) 100%
        );
    }

    .hero-overlay-vignette {
        background: radial-gradient(
            ellipse 100% 100% at 50% 50%,
            transparent 15%,
            rgba(18,10,20,0.52) 100%
        );
    }

    .hero-content-wrapper {
        align-items: flex-end;
        padding: 0 0 36px 0;
    }

    .hero-content {
        text-align: center;
        align-items: center;
        max-width: 100%;
        padding: 20px 20px;
    }

    .hero-sh-title { font-size: clamp(1.55rem, 6.5vw, 2.6rem); letter-spacing: 1.5px; }
    .hero-sh-desc  { font-size: clamp(0.82rem, 3vw, 1rem); }

    .hero-btn { padding: 11px 22px; font-size: 12px; }
    .hero-particles { display: none; }
}

/* ── Small Mobile ≤ 480px ────────────────────── */
@media (max-width: 480px) {

    .hero {
        min-height: 430px;
        background-position: 26% top;
    }

    .hero-content-wrapper { padding-bottom: 28px; }

    .hero-sh-title  { font-size: clamp(1.35rem, 7.5vw, 1.9rem); letter-spacing: 0.8px; }
    .hero-sh-desc   { font-size: 0.85rem; }

    .hero-buttons {
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .hero-btn        { width: 200px; justify-content: center; padding: 11px 18px; }
    .hero-bottom-fade  { height: 50px; }
}

/* ── Very Small ≤ 360px ──────────────────────── */
@media (max-width: 360px) {
    .hero          { min-height: 390px; }
    .hero-sh-title { font-size: 1.25rem; }
    .hero-btn      { width: 175px; font-size: 11px; }
}

/* ── Reduced Motion ──────────────────────────── */
@media (prefers-reduced-motion: reduce) {
    .hero-content  { animation: none; }
    .hero-particle { animation: none; display: none; }
    .hero-btn      { transition: none; }
    .hero-btn::before { display: none; }
}
</style>
