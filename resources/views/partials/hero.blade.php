{{-- ═══════════════════════════════════════════════════════
     HERO SLIDER — 3Sixtyshows
     Posters: 1170×570px — ratio 570/1170 = 48.717%
     Strategy: padding-bottom aspect-ratio box
     Full poster visible — zero crop top/bottom/sides
     ═══════════════════════════════════════════════════════ --}}

<section id="home" class="hs">
    <div class="hs__wrap">

        {{-- Aspect-ratio box: always matches 1170×570 poster exactly --}}
        <div class="hs__box" id="hsBox">

            <div class="hs__stack">
                <div class="hs__s hs--on"><img src="{{ asset('assets/images/events/slider/vishalmishra_chicago.jpg') }}"   alt="Vishal Mishra Chicago"       width="1170" height="570" loading="eager"></div>
                <div class="hs__s"><img src="{{ asset('assets/images/events/slider/Sonunigam_Dallas.jpeg') }}"             alt="Sonu Nigam Dallas"            width="1170" height="570" loading="lazy"></div>
                <div class="hs__s"><img src="{{ asset('assets/images/events/slider/Sonunigam_Houston.jpeg') }}"            alt="Sonu Nigam Houston"           width="1170" height="570" loading="lazy"></div>
                <div class="hs__s"><img src="{{ asset('assets/images/events/slider/Arjunrampal_Atlanta.jpeg') }}"          alt="Arjun Rampal Atlanta"         width="1170" height="570" loading="lazy"></div>
                <div class="hs__s"><img src="{{ asset('assets/images/events/slider/Arjunrampal_Dallas.jpeg') }}"           alt="Arjun Rampal Dallas"          width="1170" height="570" loading="lazy"></div>
                <div class="hs__s"><img src="{{ asset('assets/images/events/slider/Arjunrampal_Houston.jpeg') }}"          alt="Arjun Rampal Houston"         width="1170" height="570" loading="lazy"></div>
                <div class="hs__s"><img src="{{ asset('assets/images/events/slider/Arjunrampal_sanfrancisco.jpeg') }}"     alt="Arjun Rampal San Francisco"   width="1170" height="570" loading="lazy"></div>
                <div class="hs__s"><img src="{{ asset('assets/images/events/slider/Nawazuddin.jpeg') }}"                   alt="Nawazuddin Siddiqui Dallas"   width="1170" height="570" loading="lazy"></div>
                <div class="hs__s"><img src="{{ asset('assets/images/events/slider/Nitinmukesh.jpeg') }}"                  alt="Nitin Mukesh Dallas"          width="1170" height="570" loading="lazy"></div>
                <div class="hs__s"><img src="{{ asset('assets/images/events/slider/javedali.jpg') }}"                      alt="Javed Ali Dallas"             width="1170" height="570" loading="lazy"></div>
            </div>

            {{-- Prev / Next arrows --}}
            <button class="hs__arrow hs__arrow--prev" id="hsPrev" aria-label="Previous slide">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="hs__arrow hs__arrow--next" id="hsNext" aria-label="Next slide">
                <i class="fas fa-chevron-right"></i>
            </button>

            {{-- Dot indicators --}}
            <div class="hs__dots" id="hsDots"></div>

        </div>
    </div>
</section>

<style>
/* ════════════════════════════════════════════════════════════
   HERO SLIDER
   Aspect-ratio padding trick: 570/1170 × 100 = 48.717%
   Box height always matches poster ratio — zero crop ever.
   Max-width 1170px centred on large screens.
   ════════════════════════════════════════════════════════════ */

.hs {
    display: block;
    width: 100%;
    background: #000;
}

/* Centre and cap width so very wide screens dont stretch poster */
.hs__wrap {
    width: 100%;
    max-width: 1170px;
    margin: 0 auto;
    background: #000;
}

/* Aspect-ratio container */
.hs__box {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 48.717%; /* 570 / 1170 x 100 */
    overflow: hidden;
    background: #000;
}

/* Stack fills padding area absolutely */
.hs__stack {
    position: absolute;
    inset: 0;
}

/* Each slide */
.hs__s {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 0.7s ease;
    pointer-events: none;
}
.hs__s.hs--on {
    opacity: 1;
    z-index: 1;
    pointer-events: auto;
}

/* Poster image — same ratio as box = no crop, no black bars */
.hs__s img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: fill;
}

/* ── Prev / Next arrows ─────────────────────────────────── */
.hs__arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    background: rgba(0,0,0,0.45);
    border: 1.5px solid rgba(255,255,255,0.18);
    color: #fff;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s ease, transform 0.2s ease;
    padding: 0;
    line-height: 1;
}
.hs__arrow:hover {
    background: rgba(200,16,46,0.85);
    transform: translateY(-50%) scale(1.08);
}
.hs__arrow--prev { left: 14px; }
.hs__arrow--next { right: 14px; }
.hs__arrow i { font-size: 14px; }

/* ── Dot indicators ─────────────────────────────────────── */
.hs__dots {
    position: absolute;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
    display: flex;
    gap: 7px;
    align-items: center;
}
.hs__dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255,255,255,0.45);
    border: none;
    cursor: pointer;
    padding: 0;
    transition: background 0.22s ease, transform 0.22s ease;
}
.hs__dot.hs--active {
    background: #C8102E;
    transform: scale(1.35);
}

/* ── Mobile ─────────────────────────────────────────────── */
@media (max-width: 576px) {
    .hs__arrow { width: 32px; height: 32px; }
    .hs__arrow i { font-size: 12px; }
    .hs__arrow--prev { left: 8px; }
    .hs__arrow--next { right: 8px; }
    .hs__dot { width: 6px; height: 6px; }
    .hs__dots { bottom: 6px; }
}
</style>

<script>
(function () {
    var slides = document.querySelectorAll('.hs__s');
    var n      = slides.length;
    var cur    = 0;
    var tx     = 0;
    var paused = false;

    /* ── Build dot indicators dynamically ──────────────── */
    var dotsWrap = document.getElementById('hsDots');
    var dots = [];
    for (var i = 0; i < n; i++) {
        var d = document.createElement('button');
        d.className = 'hs__dot' + (i === 0 ? ' hs--active' : '');
        d.setAttribute('aria-label', 'Go to slide ' + (i + 1));
        (function (idx) {
            d.addEventListener('click', function () { go(idx); });
        })(i);
        dotsWrap.appendChild(d);
        dots.push(d);
    }

    /* ── Slide transition ───────────────────────────────── */
    function go(i) {
        slides[cur].classList.remove('hs--on');
        dots[cur].classList.remove('hs--active');
        cur = ((i % n) + n) % n;
        slides[cur].classList.add('hs--on');
        dots[cur].classList.add('hs--active');
    }

    /* ── Auto-play every 5s ─────────────────────────────── */
    setInterval(function () { if (!paused) go(cur + 1); }, 5000);

    /* ── Arrow buttons ──────────────────────────────────── */
    var prev = document.getElementById('hsPrev');
    var next = document.getElementById('hsNext');
    if (prev) prev.addEventListener('click', function () { go(cur - 1); });
    if (next) next.addEventListener('click', function () { go(cur + 1); });

    /* ── Touch swipe + hover pause ──────────────────────── */
    var box = document.getElementById('hsBox');
    if (box) {
        box.addEventListener('touchstart', function (e) {
            tx = e.changedTouches[0].clientX;
        }, { passive: true });
        box.addEventListener('touchend', function (e) {
            var d = tx - e.changedTouches[0].clientX;
            if (Math.abs(d) > 40) go(d > 0 ? cur + 1 : cur - 1);
        }, { passive: true });
        box.addEventListener('mouseenter', function () { paused = true; });
        box.addEventListener('mouseleave', function () { paused = false; });
    }
})();
</script>
