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

            <!-- Eyebrow label -->
            <p class="hero-eyebrow">
                <span class="hero-eyebrow-line"></span>
                Dallas&nbsp;&bull;&nbsp;Texas
                <span class="hero-eyebrow-line"></span>
            </p>

            <!-- Main title -->
            <h1 class="hero-title">
                Welcome to
                <span class="hero-title-brand">3Sixty<em>Shows</em></span>
            </h1>

            <!-- Subtitle -->
            <p class="hero-subtitle">
                Biggest&nbsp;Bollywood&nbsp;Events&nbsp;in&nbsp;America
            </p>

            <!-- Decorative divider -->
            <div class="hero-divider" aria-hidden="true">
                <span class="hero-divider-dot"></span>
                <span class="hero-divider-line"></span>
                <span class="hero-divider-diamond">&#9670;</span>
                <span class="hero-divider-line"></span>
                <span class="hero-divider-dot"></span>
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
    background-color: #0D0D0D;

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
        rgba(13,13,13,0.08) 0%,
        rgba(13,13,13,0.02) 40%,
        rgba(13,13,13,0.00) 55%,
        rgba(13,13,13,0.12) 100%
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
        rgba(13,13,13,0.38) 100%
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
    background: linear-gradient(to top, rgba(13,13,13,0.70), transparent);
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
    max-width: 500px;
    width: 100%;
    padding: 28px 0;
    animation: hero-content-in 0.9s cubic-bezier(0.22,1,0.36,1) both;
}

@keyframes hero-content-in {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}

.hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 4px;
    color: #F0C040;
    margin: 0 0 14px 0;
    opacity: 0.90;
}

.hero-eyebrow-line {
    display: inline-block;
    width: 32px; height: 1px;
    background: linear-gradient(90deg, #D4A017, #F0C040);
    opacity: 0.7;
}

.hero-title {
    font-family: 'Oswald', sans-serif;
    font-size: clamp(1.8rem, 3.8vw, 3.6rem);
    font-weight: 700;
    color: #FFFFFF;
    margin: 0 0 10px 0;
    letter-spacing: 1.5px;
    line-height: 1.08;
    text-transform: uppercase;
    text-shadow: 0 2px 14px rgba(0,0,0,0.75), 0 0 40px rgba(0,0,0,0.4);
    display: block;
}

.hero-title-brand {
    display: block;
    color: #FFFFFF;
}

.hero-title-brand em {
    font-style: normal;
    color: #C8102E;
    text-shadow: 0 0 30px rgba(200,16,46,0.55), 0 2px 12px rgba(0,0,0,0.65);
}

.hero-subtitle {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(0.95rem, 2vw, 1.5rem);
    font-weight: 700;
    font-style: italic;
    color: rgba(255,255,255,0.88);
    margin: 0 0 18px 0;
    line-height: 1.35;
    text-shadow: 0 2px 10px rgba(0,0,0,0.55);
    display: block;
    letter-spacing: 0.4px;
}

.hero-divider {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    margin: 0 0 20px 0;
}

.hero-divider-line {
    display: inline-block;
    width: 44px; height: 1px;
    background: linear-gradient(90deg, transparent, #D4A017);
    opacity: 0.55;
}

.hero-divider-line:last-child {
    background: linear-gradient(90deg, #D4A017, transparent);
}

.hero-divider-dot {
    width: 4px; height: 4px;
    border-radius: 50%;
    background: #D4A017;
    opacity: 0.5;
    display: inline-block;
}

.hero-divider-diamond {
    font-size: 9px;
    color: #D4A017;
    opacity: 0.8;
    line-height: 1;
}

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
        /* Shift anchor so figures stay visible behind centered text */
        background-position: 22% center;
        min-height: clamp(340px, 44vw, 480px);
    }

    /* Stronger tint so centered text is legible over figures */
    .hero-overlay {
        background: linear-gradient(
            to bottom,
            rgba(13,13,13,0.25) 0%,
            rgba(13,13,13,0.52) 100%
        );
    }

    .hero-content-wrapper {
        justify-content: center;
        padding: 0 20px;
    }

    .hero-content {
        text-align: center;
        max-width: 560px;
        padding: 32px 20px;
    }

    .hero-eyebrow,
    .hero-divider,
    .hero-buttons { justify-content: center; }

    .hero-divider-line { width: 34px; }
    .hero-title { font-size: clamp(1.7rem, 4.5vw, 2.6rem); }
}

/* ── Mobile ≤ 768px ──────────────────────────── */
@media (max-width: 768px) {

    .hero {
        /*
         * At 375px wide the 4:1 image is only 94px tall with cover+vw.
         * Fix: set explicit min-height, lock bg-size to height, shift x
         * so the main seated figure sits behind the bottom-aligned text.
         */
        min-height: 480px;
        background-size: auto 100%;     /* height fills, width crops */
        background-position: 24% top;  /* keep figure centred */
    }

    /* Heavier scrim: text needs to be legible over busy image content */
    .hero-overlay {
        background: linear-gradient(
            175deg,
            rgba(13,13,13,0.15) 0%,
            rgba(13,13,13,0.62) 55%,
            rgba(13,13,13,0.82) 100%
        );
    }

    .hero-overlay-vignette {
        background: radial-gradient(
            ellipse 100% 100% at 50% 50%,
            transparent 15%,
            rgba(13,13,13,0.52) 100%
        );
    }

    /* Push text to bottom so figures show above */
    .hero-content-wrapper {
        align-items: flex-end;
        padding: 0 0 36px 0;
    }

    .hero-content {
        text-align: center;
        max-width: 100%;
        padding: 20px 24px;
    }

    .hero-title    { font-size: clamp(1.55rem, 6.5vw, 2.1rem); }
    .hero-subtitle { font-size: clamp(0.88rem, 3.2vw, 1.05rem); }

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

    .hero-title    { font-size: clamp(1.35rem, 7.5vw, 1.8rem); letter-spacing: 0.8px; }
    .hero-subtitle { font-size: 0.88rem; }
    .hero-eyebrow  { font-size: 9px; letter-spacing: 3px; }

    .hero-buttons {
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .hero-btn        { width: 200px; justify-content: center; padding: 11px 18px; }
    .hero-divider-line { width: 26px; }
    .hero-bottom-fade  { height: 50px; }
}

/* ── Very Small ≤ 360px ──────────────────────── */
@media (max-width: 360px) {
    .hero          { min-height: 390px; }
    .hero-title    { font-size: 1.25rem; }
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
