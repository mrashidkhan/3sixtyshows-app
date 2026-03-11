{{-- resources/views/gallery/show.blade.php --}}
@extends('layouts.master')

@section('title', $gallery->title . ' — ' . $year . ' — 3SixtyShows Gallery')
@section('meta_description', $gallery->description ?? 'View photos from ' . $gallery->title . ' at 3SixtyShows Dallas.')

@push('styles')
<style>
/* ── Hero ─────────────────────────────────────────────────── */
.glr-hero {
    background: linear-gradient(135deg, #0a0a0a 0%, #1a0005 50%, #0a0a0a 100%);
    padding: 72px 0 52px;
    position: relative;
    overflow: hidden;
}
.glr-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 60% 60% at 50% 0%, rgba(200,16,46,0.18) 0%, transparent 70%);
    pointer-events: none;
}
.glr-hero__breadcrumb {
    font-size: 0.78rem;
    color: rgba(255,255,255,0.38);
    letter-spacing: 0.07em;
    text-transform: uppercase;
    margin-bottom: 16px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.glr-hero__breadcrumb a { color: #D4A017; text-decoration: none; }
.glr-hero__breadcrumb a:hover { text-decoration: underline; }
.glr-hero__title {
    font-family: 'Oswald', sans-serif;
    font-size: clamp(1.8rem, 4vw, 3rem);
    font-weight: 700;
    letter-spacing: 0.04em;
    color: #fff;
    text-transform: uppercase;
    line-height: 1.1;
}
.glr-hero__title span { color: #C8102E; }
.glr-hero__sub {
    color: rgba(255,255,255,0.5);
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 0.95rem;
    margin-top: 10px;
}

/* ── Section wrapper ──────────────────────────────────────── */
.glr-section {
    background: #F5F5F5;
    padding: 48px 0 72px;
    min-height: 60vh;
}

/* ── Stats bar ────────────────────────────────────────────── */
.glr-statsbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 32px;
    padding-bottom: 20px;
    border-bottom: 1px solid #E5E7EB;
}
.glr-statsbar__count {
    font-size: 13px;
    color: #6B7280;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.glr-statsbar__count strong { color: #111827; font-weight: 700; }
.glr-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #374151;
    text-decoration: none;
    border: 1px solid #E2E2E2;
    border-radius: 8px;
    padding: 7px 14px;
    background: #fff;
    transition: all .2s;
    font-family: var(--font-body, 'DM Sans', sans-serif);
    letter-spacing: .4px;
    text-transform: uppercase;
}
.glr-back-btn:hover { border-color: #C8102E; color: #C8102E; text-decoration: none; }

/* ── Grid: 4-col photo grid, mirrors .otc-grid ────────────── */
.glr-photo-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    align-items: start;
}

/* ── Photo card: same shell as .otc-card ─────────────────── */
.glr-photo-card {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #E2E2E2;
    box-shadow: 0 1px 3px rgba(0,0,0,.07), 0 3px 12px rgba(0,0,0,.06);
    transition: box-shadow .22s ease, transform .22s ease;
    cursor: pointer;
}
.glr-photo-card:hover {
    box-shadow: 0 8px 32px rgba(0,0,0,.15);
    transform: translateY(-4px);
}

/* ── ① Dark top bar ───────────────────────────────────────── */
.glr-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #111;
    padding: 8px 13px;
    height: 38px;
    border-bottom: 2.5px solid rgba(255,255,255,0.70);
}
.glr-topbar__label {
    font-size: 12.5px;
    font-weight: 700;
    color: #fff;
    letter-spacing: .3px;
    line-height: 1;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.glr-topbar__icon {
    color: rgba(255,255,255,.72);
    font-size: 15px;
    line-height: 1;
}

/* ── ② Photo image area ───────────────────────────────────── */
.glr-photo-img-wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 1 / 1;
    background: #111;
    overflow: hidden;
    line-height: 0;
}
.glr-photo-img-wrap img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    display: block;
    object-fit: contain;
    transition: transform .38s ease;
}
.glr-photo-card:hover .glr-photo-img-wrap img { transform: scale(1.04); }

/* Hover scrim + zoom CTA */
.glr-photo-hover {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(10,10,10,.82) 0%, rgba(10,10,10,.20) 45%, transparent 100%);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding-bottom: 20px;
    opacity: 0;
    transition: opacity .26s ease;
    z-index: 2;
}
.glr-photo-card:hover .glr-photo-hover { opacity: 1; }
.glr-photo-hover__cta {
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

/* ── ③ Card body ──────────────────────────────────────────── */
.glr-photo-body {
    padding: 11px 13px 13px;
    background: #fff;
}
.glr-photo-body__num {
    display: block;
    font-size: 10.5px;
    font-weight: 600;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: .8px;
    margin-bottom: 3px;
    line-height: 1;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.glr-photo-body__caption {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-family: var(--font-body, 'DM Sans', sans-serif);
    min-height: 1.5em;
}

/* ── Pagination ───────────────────────────────────────────── */
.glr-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    flex-wrap: wrap;
    margin-top: 56px;
    padding-bottom: 8px;
}

/* Prev / Next arrow buttons */
.glr-pg-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #ffffff;
    border: 1.5px solid #D8D2CA;
    color: #111827;
    font-size: 13px;
    text-decoration: none;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.glr-pg-btn:hover {
    background: #C8102E;
    border-color: #C8102E;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(200,16,46,0.35);
    transform: translateY(-1px);
}

.glr-pg-btn--disabled {
    background: #F9F9F6;
    border-color: #E8E2DA;
    color: #C0B8B0;
    cursor: not-allowed;
    pointer-events: none;
}

/* Page number pills */
.glr-pg-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 6px;
    border-radius: 10px;
    background: #ffffff;
    border: 1.5px solid #D8D2CA;
    color: #374151;
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.glr-pg-num:hover {
    background: rgba(200,16,46,0.06);
    border-color: #C8102E;
    color: #C8102E;
    transform: translateY(-1px);
}

/* Active page */
.glr-pg-num--active {
    background: linear-gradient(135deg, #C8102E 0%, #9e0b22 100%);
    border-color: #C8102E;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(200,16,46,0.4);
    cursor: default;
    pointer-events: none;
}

/* Ellipsis */
.glr-pg-ellipsis {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 40px;
    color: #9CA3AF;
    font-size: 16px;
    font-weight: 700;
    letter-spacing: 1px;
    flex-shrink: 0;
}

/* ── Sibling galleries ────────────────────────────────────── */
.glr-related {
    margin-top: 52px;
    padding-top: 36px;
    border-top: 1px solid #E5E7EB;
}
.glr-related__title {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: .8px;
    text-transform: uppercase;
    color: #9CA3AF;
    margin-bottom: 16px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.glr-related-scroll {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    padding-bottom: 6px;
    scrollbar-width: thin;
    scrollbar-color: #C8102E #E5E7EB;
}
.glr-related-scroll::-webkit-scrollbar { height: 4px; }
.glr-related-scroll::-webkit-scrollbar-track { background: #E5E7EB; border-radius: 2px; }
.glr-related-scroll::-webkit-scrollbar-thumb { background: #C8102E; border-radius: 2px; }
.glr-related-pill {
    flex-shrink: 0;
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    border: 1px solid #E2E2E2;
    border-radius: 8px;
    padding: 10px 13px;
    text-decoration: none;
    color: #374151;
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 12.5px;
    font-weight: 600;
    transition: all .2s;
    max-width: 200px;
    box-shadow: 0 1px 3px rgba(0,0,0,.05);
}
.glr-related-pill:hover { border-color: #C8102E; color: #C8102E; text-decoration: none; }
.glr-related-pill img {
    width: 36px;
    height: 36px;
    border-radius: 4px;
    object-fit: cover;
    flex-shrink: 0;
}
.glr-related-pill span {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* ── Empty ────────────────────────────────────────────────── */
.glr-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 70px 20px;
    background: #FAFAF9;
    border: 1.5px dashed #E5E7EB;
    border-radius: 14px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.glr-empty p { color: #6B7280; }

/* ── Lightbox ─────────────────────────────────────────────── */
#glr-lightbox {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(0,0,0,0.96);
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 20px;
}
#glr-lightbox.open { display: flex; }
#glr-lb-img-wrap {
    max-width: min(90vw, 1100px);
    max-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
}
#glr-lb-img {
    max-width: 100%;
    max-height: 78vh;
    object-fit: contain;
    border-radius: 4px;
    box-shadow: 0 30px 80px rgba(0,0,0,.8);
    display: block;
    transition: opacity .2s;
}
#glr-lb-img.loading { opacity: 0.3; }
#glr-lb-caption {
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 0.85rem;
    color: rgba(255,255,255,.5);
    text-align: center;
    margin-top: 14px;
    max-width: 600px;
}
#glr-lb-counter {
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 0.8rem;
    letter-spacing: .1em;
    color: rgba(255,255,255,.3);
    margin-top: 6px;
}
#glr-lb-close {
    position: fixed;
    top: 20px; right: 24px;
    width: 42px; height: 42px;
    border-radius: 50%;
    background: rgba(255,255,255,.1);
    border: none;
    color: #fff;
    font-size: 1.3rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background .2s;
    z-index: 10000;
}
#glr-lb-close:hover { background: #C8102E; }
.glr-lb-arrow {
    position: fixed;
    top: 50%;
    transform: translateY(-50%);
    width: 46px; height: 46px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.15);
    border-radius: 50%;
    color: #fff;
    font-size: 1.4rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .2s;
    z-index: 10000;
}
.glr-lb-arrow:hover { background: #C8102E; border-color: #C8102E; }
#glr-lb-prev { left: 18px; }
#glr-lb-next { right: 18px; }

/* ── Responsive ───────────────────────────────────────────── */
@media (max-width: 991px)  { .glr-photo-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 767px)  { .glr-photo-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; } }
@media (max-width: 480px)  { .glr-photo-grid { grid-template-columns: 1fr; gap: 16px; } }
@media (max-width: 576px)  { #glr-lb-prev { left: 6px; } #glr-lb-next { right: 6px; } }

/* ── Responsive pagination ────────────────────────────────── */
@media (max-width: 768px) {
    .glr-pagination { gap: 5px; margin-top: 40px; }
    .glr-pg-btn,
    .glr-pg-num     { width: 36px; height: 36px; font-size: 13px; }
}
@media (max-width: 576px) {
    .glr-pagination { gap: 4px; }
}
</style>
@endpush

@section('content')

<section class="glr-hero">
    <div class="container">
        <div class="glr-hero__breadcrumb">
            <a href="{{ route('index') }}">Home</a>
            <span class="mx-2">›</span>
            <a href="{{ route('gallery.index') }}">Gallery</a>
            <span class="mx-2">›</span>
            <a href="{{ route('gallery.year', $year) }}">{{ $year }}</a>
            <span class="mx-2">›</span>
            <span>{{ Str::limit($gallery->title, 40) }}</span>
        </div>
        <h1 class="glr-hero__title">{{ $gallery->title }}</h1>
        @if($gallery->description)
        <p class="glr-hero__sub">{{ $gallery->description }}</p>
        @endif
    </div>
</section>

<section class="glr-section">
    <div class="container">

        {{-- Stats bar --}}
        <div class="glr-statsbar">
            <span class="glr-statsbar__count">
                <strong>{{ $photos->total() }}</strong> {{ Str::plural('Photo', $photos->total()) }}
                @if($photos->hasPages())
                    &nbsp;·&nbsp; Page {{ $photos->currentPage() }} of {{ $photos->lastPage() }}
                @endif
            </span>
            <a href="{{ route('gallery.year', $year) }}" class="glr-back-btn">
                <i class="fas fa-arrow-left"></i> All {{ $year }} Galleries
            </a>
        </div>

        {{-- Photo grid --}}
        <div class="glr-photo-grid" id="glr-photo-grid">

            @forelse($photos as $index => $photo)
            @php $globalIdx = $photos->firstItem() + $index - 1; @endphp
            <div class="glr-photo-card"
                 data-index="{{ $globalIdx }}"
                 data-src="{{ $photo->image_url }}"
                 data-caption="{{ $photo->description ?? '' }}"
                 onclick="glrOpenLightbox({{ $globalIdx }})">

                {{-- ① Dark top bar --}}
                <div class="glr-topbar">
                    <span class="glr-topbar__label">{{ $gallery->title }}</span>
                    <span class="glr-topbar__icon"><i class="fas fa-camera"></i></span>
                </div>

                {{-- ② Photo --}}
                <div class="glr-photo-img-wrap">
                    <img src="{{ $photo->image_url }}"
                         alt="{{ $photo->description ?? $gallery->title . ' photo ' . ($globalIdx + 1) }}"
                         loading="lazy">
                    <div class="glr-photo-hover">
                        <span class="glr-photo-hover__cta">
                            <i class="fas fa-search-plus"></i> View Full
                        </span>
                    </div>
                </div>

                {{-- ③ Body --}}
                <div class="glr-photo-body">
                    <span class="glr-photo-body__num">Photo {{ $globalIdx + 1 }}</span>
                    <div class="glr-photo-body__caption">
                        {{ $photo->description ?: $gallery->title }}
                    </div>
                </div>

            </div>
            @empty
            <div class="glr-empty">
                <p>No photos in this gallery yet. Check back soon!</p>
            </div>
            @endforelse

        </div>

        @if($photos->hasPages())
        <nav class="glr-pagination" aria-label="Photos pagination">

            {{-- Prev --}}
            @if($photos->onFirstPage())
                <span class="glr-pg-btn glr-pg-btn--disabled" aria-disabled="true">
                    <i class="fas fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $photos->previousPageUrl() }}" class="glr-pg-btn" aria-label="Previous page">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            {{-- Page numbers --}}
            @foreach($photos->getUrlRange(1, $photos->lastPage()) as $page => $url)
                @if($page == $photos->currentPage())
                    <span class="glr-pg-num glr-pg-num--active" aria-current="page">{{ $page }}</span>
                @elseif(
                    $page == 1 ||
                    $page == $photos->lastPage() ||
                    abs($page - $photos->currentPage()) <= 2
                )
                    <a href="{{ $url }}" class="glr-pg-num">{{ $page }}</a>
                @elseif(
                    abs($page - $photos->currentPage()) == 3
                )
                    <span class="glr-pg-ellipsis">&hellip;</span>
                @endif
            @endforeach

            {{-- Next --}}
            @if($photos->hasMorePages())
                <a href="{{ $photos->nextPageUrl() }}" class="glr-pg-btn" aria-label="Next page">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="glr-pg-btn glr-pg-btn--disabled" aria-disabled="true">
                    <i class="fas fa-chevron-right"></i>
                </span>
            @endif

        </nav>
        @endif

        {{-- Sibling galleries --}}
        @if($siblingGalleries->isNotEmpty())
        <div class="glr-related">
            <div class="glr-related__title">More from {{ $year }}</div>
            <div class="glr-related-scroll">
                @foreach($siblingGalleries as $sibling)
                <a href="{{ route('gallery.show', [$year, $sibling->id]) }}" class="glr-related-pill">
                    @if($sibling->image)
                    <img src="{{ $sibling->image_url }}" alt="{{ $sibling->title }}">
                    @endif
                    <span>{{ $sibling->title }}</span>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</section>

{{-- Lightbox --}}
<div id="glr-lightbox" role="dialog" aria-modal="true" aria-label="Photo lightbox">
    <button id="glr-lb-close" onclick="glrCloseLightbox()" aria-label="Close">&#x2715;</button>
    <button class="glr-lb-arrow" id="glr-lb-prev" onclick="glrMoveLightbox(-1)" aria-label="Previous">&#8249;</button>
    <button class="glr-lb-arrow" id="glr-lb-next" onclick="glrMoveLightbox(1)" aria-label="Next">&#8250;</button>
    <div id="glr-lb-img-wrap">
        <img id="glr-lb-img" src="" alt="">
    </div>
    <div id="glr-lb-caption"></div>
    <div id="glr-lb-counter"></div>
</div>

<script>
(function () {
    var lb      = document.getElementById('glr-lightbox');
    var lbImg   = document.getElementById('glr-lb-img');
    var lbCap   = document.getElementById('glr-lb-caption');
    var lbCount = document.getElementById('glr-lb-counter');
    var lbPrev  = document.getElementById('glr-lb-prev');
    var lbNext  = document.getElementById('glr-lb-next');

    var photos  = [];
    var current = 0;

    /* Build photo list — runs after this <script> tag, so all cards above it are in the DOM */
    var cards = document.querySelectorAll('#glr-photo-grid .glr-photo-card');
    cards.forEach(function (el) {
        photos.push({
            index:   parseInt(el.dataset.index, 10),
            src:     el.dataset.src,
            caption: el.dataset.caption || ''
        });
    });

    window.glrOpenLightbox = function (globalIndex) {
        if (!photos.length) return;
        var local = -1;
        for (var i = 0; i < photos.length; i++) {
            if (photos[i].index === globalIndex) { local = i; break; }
        }
        current = local >= 0 ? local : 0;
        lb.classList.add('open');
        document.body.style.overflow = 'hidden';
        render();
    };

    window.glrCloseLightbox = function () {
        lb.classList.remove('open');
        document.body.style.overflow = '';
        lbImg.src = '';
    };

    window.glrMoveLightbox = function (dir) {
        if (!photos.length) return;
        current = (current + dir + photos.length) % photos.length;
        render();
    };

    function render() {
        var p = photos[current];
        if (!p) return;
        lbImg.onload  = function () { lbImg.classList.remove('loading'); };
        lbImg.onerror = function () { lbImg.classList.remove('loading'); };
        lbImg.classList.add('loading');
        lbImg.alt = p.caption || 'Gallery photo';
        lbImg.src = p.src;
        lbCap.textContent   = p.caption;
        lbCount.textContent = (current + 1) + ' / ' + photos.length;
        lbPrev.style.display = photos.length > 1 ? '' : 'none';
        lbNext.style.display = photos.length > 1 ? '' : 'none';
    }

    document.addEventListener('keydown', function (e) {
        if (!lb.classList.contains('open')) return;
        if (e.key === 'Escape')     glrCloseLightbox();
        if (e.key === 'ArrowLeft')  glrMoveLightbox(-1);
        if (e.key === 'ArrowRight') glrMoveLightbox(1);
    });

    lb.addEventListener('click', function (e) {
        if (e.target === lb) glrCloseLightbox();
    });
}());
</script>

@endsection

@push('scripts')@endpush
