<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title', '3Sixtyshows - Premier Bollywood Events in Texas')</title>
    <meta name="description" content="@yield('meta_description', 'Texas\'s premier Bollywood event organizer. Experience world-class entertainment with legendary artists.')" />

    {{-- Google Fonts — Oswald · Playfair Display · DM Sans (matches style.css §02) --}}
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    {{-- App Stylesheet --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

    {{-- Favicon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/favicon_io/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32"  href="{{ asset('assets/images/favicon_io/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16"  href="{{ asset('assets/images/favicon_io/favicon-16x16.png') }}" />
    <link rel="manifest" href="{{ asset('site.webmanifest') }}" />

    {{-- Open Graph --}}
    <meta property="og:type"        content="website" />
    <meta property="og:url"         content="{{ url('/') }}" />
    <meta property="og:title"       content="@yield('og_title',       '3Sixtyshows - Premier Bollywood Events in Texas')" />
    <meta property="og:description" content="@yield('og_description', 'Experience world-class Bollywood entertainment in Texas.')" />
    <meta property="og:image"       content="{{ asset('assets/images/logos/logo.jpg') }}" />

    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="summary_large_image" />
    <meta name="twitter:url"         content="{{ url('/') }}" />
    <meta name="twitter:title"       content="@yield('twitter_title',       '3Sixtyshows - Premier Bollywood Events in Texas')" />
    <meta name="twitter:description" content="@yield('twitter_description', 'Experience world-class Bollywood entertainment in Texas.')" />
    <meta name="twitter:image"       content="{{ asset('assets/images/logos/logo.jpg') }}" />

    {{-- ─────────────────────────────────────────────────────────────────────
         Active-state fix + Gallery dropdown
         ─────────────────────────────────────────────────────────────────────
         style.css §06 defines:
           .nav-link.active::after  { transform: scaleX(1) }  ← gold underbar
           .nav-link.active         { color: #fff }
         We simply add the gold tint so the active item stands out visually,
         and we add the dropdown system for the Gallery item.
         No !important hacks — we work with the existing class, not against it.
         ───────────────────────────────────────────────────────────────────── --}}
    <style>

        /* ── 1. Active link colour — gold tint ─────────────────────────────── */
        .nav-link.active {
            color: var(--color-gold, #D4A017);
        }

        /* ── 2. ROOT FIX: style.css sets .nav { overflow: hidden } which clips
              the absolutely-positioned submenu panel on desktop.
              Override to visible only above the mobile breakpoint.             */
        @media (min-width: 993px) {
            .nav { overflow: visible !important; }
        }

        /* ── 3. Dropdown <li> wrapper ─────────────────────────────────────── */
        .nav-dropdown {
            position: relative;
        }

        /* Parent trigger: inline-flex so caret is inline with label */
        .nav-dropdown > .nav-link {
            display: inline-flex !important;
            align-items: center;
            gap: 4px;
        }

        /* Chevron — only rotates when JS adds .open to the <li> */
        .nav-caret {
            font-size: 8px;
            opacity: 0.55;
            flex-shrink: 0;
            transition: transform 0.22s ease;
        }
        .nav-dropdown.open > .nav-link .nav-caret {
            transform: rotate(180deg);
        }

        /* ── 4. Dropdown panel ─────────────────────────────────────────────
              NO CSS :hover rule — hover is 100% JS (mouseenter/mouseleave
              + delay timer). CSS :hover caused flicker because the gap
              between the link bottom and the panel top briefly interrupted
              the hover state as the cursor crossed it.
              JS keeps the panel open for 120 ms after the cursor leaves the
              <li>, so the cursor can move onto the panel without it closing.
           ─────────────────────────────────────────────────────────────────── */
        .nav-submenu {
            visibility: hidden;
            opacity: 0;
            pointer-events: none;

            position: absolute;
            top: 100%;          /* flush to bottom of <li> — no gap */
            left: 50%;
            transform: translateX(-50%) translateY(4px);
            min-width: 200px;

            /* 10px top padding creates an invisible bridge so the cursor
               can travel from the link into the panel without mouseleave
               firing on the <li>. The visible box is the __inner div.      */
            padding-top: 10px;
            background: transparent;
            z-index: 9999;

            transition: opacity 0.17s ease,
                        transform 0.17s ease,
                        visibility 0.17s ease;
        }

        /* Visible box inside the transparent padding wrapper */
        .nav-submenu__inner {
            background-color: var(--color-onyx, #0D0D0D);
            border: 1px solid rgba(255,255,255,0.08);
            border-top: 2px solid var(--color-crimson, #C8102E);
            border-radius: 10px;
            box-shadow: 0 14px 44px rgba(0,0,0,0.65);
            padding: 6px 0;
            overflow: hidden;
        }

        /* JS-only: .open added/removed on the <li> */
        .nav-dropdown.open > .nav-submenu {
            visibility: visible;
            opacity: 1;
            pointer-events: auto;
            transform: translateX(-50%) translateY(0);
        }

        /* ── 5. Submenu items ─────────────────────────────────────────────── */
        .nav-submenu__item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 18px;
            font-family: var(--font-body, 'DM Sans', sans-serif);
            font-size: 12.5px;
            font-weight: 600;
            color: rgba(255,255,255,0.72);
            text-decoration: none;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            white-space: nowrap;
            transition: background 0.14s ease, color 0.14s ease;
        }
        .nav-submenu__item:hover {
            background: rgba(200,16,46,0.14);
            color: #fff;
        }
        .nav-submenu__item.active {
            color: var(--color-gold, #D4A017);
            background: rgba(212,160,23,0.07);
        }
        .nav-submenu__item i {
            width: 15px;
            text-align: center;
            font-size: 12px;
            color: var(--color-crimson, #C8102E);
            flex-shrink: 0;
        }
        .nav-submenu__item.active i {
            color: var(--color-gold, #D4A017);
        }

        .nav-submenu__divider {
            height: 1px;
            background: rgba(255,255,255,0.07);
            margin: 4px 14px;
        }

        /* ── 6. Mobile ≤ 992px ─────────────────────────────────────────────── */
        @media (max-width: 992px) {

            .nav-submenu {
                position: static;
                visibility: visible;
                opacity: 1;
                pointer-events: auto;
                transform: none;
                transition: none;
                padding-top: 0;
                display: none;          /* JS toggles via .open on <li> */
                background: transparent;
                min-width: unset;
                z-index: auto;
            }

            .nav-submenu__inner {
                background: rgba(255,255,255,0.03);
                border: none;
                border-left: 3px solid var(--color-crimson, #C8102E);
                border-radius: 0;
                box-shadow: none;
                padding: 0;
            }

            .nav-dropdown.open > .nav-submenu { display: block; }

            .nav-submenu__item {
                padding: 13px 28px;
                font-size: 12px;
                border-bottom: 1px solid rgba(255,255,255,0.04);
                color: rgba(255,255,255,0.60);
            }
            .nav-submenu__item:last-child { border-bottom: none; }

            .nav-submenu__divider { display: none; }

            .nav-dropdown > .nav-link {
                width: 100%;
                justify-content: flex-start;
            }
            .nav-caret {
                margin-left: auto;
                opacity: 0.45;
            }
        }

    </style>

    @stack('styles')
</head>
<body>

    {{-- ═══════════════════════════════════════════════════════════════════
         TOP BAR
         ═══════════════════════════════════════════════════════════════════ --}}
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-content">

                <div class="top-bar-left">
                    <span>
                        <i class="fas fa-envelope"></i>
                        <span class="top-bar-text">info@3sixtyshows.com</span>
                    </span>
                    <span>
                        <i class="fas fa-phone"></i>
                        <span class="top-bar-text">+1-855-360-SHOW</span>
                    </span>
                </div>

                <div class="top-bar-right">
                    <a href="https://www.facebook.com/3sixtyshows"
                       target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://www.instagram.com/3sixtyshows/"
                       target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                        <i class="fab fa-instagram"></i> Instagram
                    </a>
                </div>

            </div>
        </div>
    </div>
    {{-- end .top-bar --}}


    {{-- ═══════════════════════════════════════════════════════════════════
         HEADER
         ═══════════════════════════════════════════════════════════════════ --}}
    <header class="header" id="header">

        {{-- ── Row 1: Logo  |  Nav  |  Register  |  Hamburger ──────────── --}}
        <div class="header-nav-row">
            <div class="container">
                <div class="header-content">

                    {{-- Logo --}}
                    <a href="{{ url('/') }}" class="logo">
                        <img src="{{ asset('assets/images/logos/logo.jpg') }}"
                             alt="3Sixtyshows — Premier Bollywood Events in Texas" />
                    </a>

                    {{-- Primary Navigation --}}
                    <nav class="nav" id="nav" aria-label="Primary navigation">
                        <ul>

                            <li>
                                <a href="{{ route('index') }}"
                                   class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}">
                                    Home
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('aboutus') }}"
                                   class="nav-link {{ request()->routeIs('aboutus') ? 'active' : '' }}">
                                    About Us
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('events') }}"
                                   class="nav-link {{ request()->routeIs('events') ? 'active' : '' }}">
                                    Events
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('pastevents') }}"
                                   class="nav-link {{ request()->routeIs('pastevents') ? 'active' : '' }}">
                                    Past Events
                                </a>
                            </li>

                            {{-- ── Gallery dropdown ──────────────────────────────────────
                                 • Parent link is highlighted whenever the user is anywhere
                                   under gallery.* or video-gallery.* routes.
                                 • Desktop: pure CSS :hover opens the panel.
                                 • Mobile (≤992px): JS toggles .open class on the <li>,
                                   which turns the submenu into an accordion panel.
                                 ────────────────────────────────────────────────────────── --}}
                            <li class="nav-dropdown" id="nav-gallery-dropdown">

                                <a href="{{ route('gallery.index') }}"
                                   class="nav-link {{ request()->routeIs('gallery.*') || request()->routeIs('video-gallery.*') ? 'active' : '' }}"
                                   id="nav-gallery-trigger"
                                   aria-haspopup="true"
                                   aria-expanded="false">
                                    Gallery
                                    <i class="fas fa-chevron-down nav-caret" aria-hidden="true"></i>
                                </a>

                                <div class="nav-submenu" role="menu" aria-label="Gallery submenu"><div class="nav-submenu__inner">

                                    <a href="{{ route('gallery.index') }}"
                                       class="nav-submenu__item {{ request()->routeIs('gallery.*') ? 'active' : '' }}"
                                       role="menuitem">
                                        <i class="fas fa-images" aria-hidden="true"></i>
                                        Photo Gallery
                                    </a>

                                    <div class="nav-submenu__divider" role="separator"></div>

                                    <a href="{{ route('video-gallery.index') }}"
                                       class="nav-submenu__item {{ request()->routeIs('video-gallery.*') ? 'active' : '' }}"
                                       role="menuitem">
                                        <i class="fas fa-film" aria-hidden="true"></i>
                                        Video Gallery
                                    </a>

                                </div></div>
                            </li>
                            {{-- end Gallery dropdown --}}

                            <li>
                                <a href="{{ route('artists') }}"
                                   class="nav-link {{ request()->routeIs('artists') ? 'active' : '' }}">
                                    Artists
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('faq') }}"
                                   class="nav-link {{ request()->routeIs('faq') ? 'active' : '' }}">
                                    FAQ
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('contactus') }}"
                                   class="nav-link {{ request()->routeIs('contactus') ? 'active' : '' }}">
                                    Contact Us
                                </a>
                            </li>

                        </ul>
                    </nav>
                    {{-- end .nav --}}

                    {{-- Register CTA --}}
                    <a href="{{ route('registration') }}" class="btn-header">
                        <i class="far fa-user-circle"></i>
                        Register
                    </a>

                    {{-- Hamburger — shown at ≤992px via style.css §24 --}}
                    <button class="mobile-toggle"
                            id="mobile-toggle"
                            aria-label="Toggle navigation menu"
                            aria-expanded="false"
                            aria-controls="nav">
                        <i class="fas fa-bars" aria-hidden="true"></i>
                    </button>

                </div>
            </div>
        </div>
        {{-- end Row 1 --}}


        {{-- ── Row 2: Desktop Search Bar ──────────────────────────────── --}}
        <div class="header-search-row">
            <div class="container">
                <div class="header-search-bar">

                    {{-- Location segment --}}
                    <div class="search-segment search-seg-location">
                        <span class="search-seg-icon">
                            <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                        </span>
                        <div class="search-seg-body">
                            <span class="search-seg-label">Location</span>
                            <input type="text"
                                   class="search-seg-input"
                                   id="search-location"
                                   placeholder="City or Zip Code"
                                   aria-label="Location" />
                        </div>
                    </div>

                    <div class="search-divider" aria-hidden="true"></div>

                    {{-- Dates segment --}}
                    <div class="search-segment search-seg-dates"
                         onclick="this.querySelector('input[type=date]').showPicker()">
                        <span class="search-seg-icon">
                            <i class="far fa-calendar-alt" aria-hidden="true"></i>
                        </span>
                        <div class="search-seg-body">
                            <span class="search-seg-label">Dates</span>
                            <input type="date"
                                   class="search-seg-input search-seg-date-input"
                                   id="event-date"
                                   name="event_date"
                                   aria-label="Select event date" />
                        </div>
                    </div>

                    <div class="search-divider" aria-hidden="true"></div>

                    {{-- Keyword segment --}}
                    <div class="search-segment search-seg-query">
                        <span class="search-seg-icon">
                            <i class="fas fa-search" aria-hidden="true"></i>
                        </span>
                        <div class="search-seg-body">
                            <span class="search-seg-label">Search</span>
                            <input type="text"
                                   class="search-seg-input"
                                   id="main-search-input"
                                   placeholder="Artist, Event or Venue"
                                   aria-label="Search events" />
                        </div>
                    </div>

                    <button class="search-submit-btn"
                            type="button"
                            onclick="handleSearch()"
                            aria-label="Search events">
                        Search
                    </button>

                </div>
            </div>
        </div>
        {{-- end Row 2 --}}


        {{-- ── Row 3: Mobile Search Strip ─────────────────────────────── --}}
        <div class="mobile-search-strip">
            <div class="mob-search-card">

                {{-- Location --}}
                <div class="mob-search-row">
                    <span class="mob-search-icon">
                        <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                    </span>
                    <div class="mob-search-body">
                        <span class="mob-search-label">Location</span>
                        <input type="text"
                               class="mob-search-input"
                               id="mob-location"
                               placeholder="City or Zip Code"
                               aria-label="Location" />
                    </div>
                </div>

                <div class="mob-search-sep" aria-hidden="true"></div>

                {{-- Dates --}}
                <div class="mob-search-row">
                    <span class="mob-search-icon">
                        <i class="far fa-calendar-alt" aria-hidden="true"></i>
                    </span>
                    <div class="mob-search-body">
                        <span class="mob-search-label">Dates</span>
                        <input type="date"
                               class="mob-search-input"
                               id="mob-date"
                               aria-label="Select event date" />
                    </div>
                </div>

                <div class="mob-search-sep" aria-hidden="true"></div>

                {{-- Keyword --}}
                <div class="mob-search-row">
                    <span class="mob-search-icon">
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </span>
                    <div class="mob-search-body">
                        <span class="mob-search-label">Search</span>
                        <input type="text"
                               class="mob-search-input"
                               id="mobile-search-input"
                               placeholder="Artist, Event or Venue"
                               aria-label="Search events" />
                    </div>
                </div>

                <button class="mob-search-btn"
                        type="button"
                        onclick="handleMobSearch()">
                    <i class="fas fa-search" aria-hidden="true"></i>&nbsp; Search Events
                </button>

            </div>
        </div>
        {{-- end Row 3 --}}

    </header>
    {{-- end .header --}}


    {{-- ═══════════════════════════════════════════════════════════════════
         HEADER SCRIPTS
         Placed immediately after </header> so the DOM nodes are available.
         ═══════════════════════════════════════════════════════════════════ --}}
        <script>

        /* ── Desktop search redirect ──────────────────────────────────────── */
        function handleSearch() {
            var location = document.getElementById('search-location');
            var date     = document.getElementById('event-date');
            var query    = document.getElementById('main-search-input');
            var params   = new URLSearchParams();
            if (location && location.value.trim()) params.set('location', location.value.trim());
            if (date     && date.value.trim())     params.set('date',     date.value.trim());
            if (query    && query.value.trim())    params.set('query',    query.value.trim());
            window.location.href = '{{ route("events") }}' + (params.toString() ? '?' + params.toString() : '');
        }

        /* ── Mobile search redirect ───────────────────────────────────────── */
        function handleMobSearch() {
            var location = document.getElementById('mob-location');
            var date     = document.getElementById('mob-date');
            var query    = document.getElementById('mobile-search-input');
            var params   = new URLSearchParams();
            if (location && location.value.trim()) params.set('location', location.value.trim());
            if (date     && date.value.trim())     params.set('date',     date.value.trim());
            if (query    && query.value.trim())    params.set('query',    query.value.trim());
            window.location.href = '{{ route("events") }}' + (params.toString() ? '?' + params.toString() : '');
        }

        document.addEventListener('DOMContentLoaded', function () {

            var mobileToggle    = document.getElementById('mobile-toggle');
            var nav             = document.getElementById('nav');
            var galleryLi       = document.getElementById('nav-gallery-dropdown');
            var galleryTrigger  = document.getElementById('nav-gallery-trigger');
            var hoverTimer      = null;    /* delay timer — prevents flicker */

            /* ── helpers ─────────────────────────────────────────────────── */
            function openGallery() {
                if (!galleryLi) return;
                clearTimeout(hoverTimer);
                galleryLi.classList.add('open');
                if (galleryTrigger) galleryTrigger.setAttribute('aria-expanded', 'true');
            }

            function closeGallery() {
                if (!galleryLi) return;
                galleryLi.classList.remove('open');
                if (galleryTrigger) galleryTrigger.setAttribute('aria-expanded', 'false');
            }

            function scheduleClose() {
                /* 120 ms grace period — cursor can cross the 10px gap without
                   the panel disappearing                                      */
                hoverTimer = setTimeout(closeGallery, 120);
            }

            function closeNav() {
                if (!nav || !nav.classList.contains('active')) return;
                nav.classList.remove('active');
                if (mobileToggle) {
                    var icon = mobileToggle.querySelector('i');
                    icon.classList.add('fa-bars');
                    icon.classList.remove('fa-times');
                    mobileToggle.setAttribute('aria-expanded', 'false');
                }
            }

            /* ── 1. Desktop hover (mouseenter / mouseleave + timer) ────────
                  This is the ONLY mechanism that opens the dropdown on desktop.
                  No CSS :hover rule exists for the panel — that was the source
                  of the flicker.
               ─────────────────────────────────────────────────────────────── */
            if (galleryLi) {
                galleryLi.addEventListener('mouseenter', function () {
                    if (window.innerWidth > 992) openGallery();
                });
                galleryLi.addEventListener('mouseleave', function () {
                    if (window.innerWidth > 992) scheduleClose();
                });
            }

            /* ── 2. Mobile tap — toggle accordion ─────────────────────────── */
            if (galleryTrigger) {
                galleryTrigger.addEventListener('click', function (e) {
                    if (window.innerWidth <= 992) {
                        e.preventDefault();
                        if (galleryLi.classList.contains('open')) {
                            closeGallery();
                        } else {
                            openGallery();
                        }
                    }
                    /* Desktop: click navigates to gallery.index as normal */
                });
            }

            /* ── 3. Keyboard: Escape closes dropdown ──────────────────────── */
            if (galleryLi) {
                galleryLi.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') {
                        closeGallery();
                        if (galleryTrigger) galleryTrigger.focus();
                    }
                });
            }

            /* ── 4. Outside click closes dropdown (all screen sizes) ─────── */
            document.addEventListener('click', function (e) {
                if (galleryLi && !galleryLi.contains(e.target)) {
                    closeGallery();
                }
            });

            /* ── 5. Hamburger toggle ──────────────────────────────────────── */
            if (mobileToggle && nav) {

                mobileToggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    var isOpen = nav.classList.toggle('active');
                    var icon   = mobileToggle.querySelector('i');
                    icon.classList.toggle('fa-bars',  !isOpen);
                    icon.classList.toggle('fa-times',  isOpen);
                    mobileToggle.setAttribute('aria-expanded', String(isOpen));
                    if (!isOpen) closeGallery();
                });

                document.addEventListener('click', function (e) {
                    if (nav.classList.contains('active') &&
                        !nav.contains(e.target) &&
                        !mobileToggle.contains(e.target)) {
                        closeNav();
                    }
                });

                /* Regular nav links close the slide-in panel */
                nav.querySelectorAll('li:not(.nav-dropdown) .nav-link').forEach(function (link) {
                    link.addEventListener('click', closeNav);
                });

                /* Submenu links also close the panel */
                nav.querySelectorAll('.nav-submenu__item').forEach(function (item) {
                    item.addEventListener('click', closeNav);
                });
            }

            /* ── 6. Enter key fires search ────────────────────────────────── */
            ['search-location', 'event-date', 'main-search-input'].forEach(function (id) {
                var el = document.getElementById(id);
                if (el) el.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') handleSearch();
                });
            });
            ['mob-location', 'mob-date', 'mobile-search-input'].forEach(function (id) {
                var el = document.getElementById(id);
                if (el) el.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') handleMobSearch();
                });
            });

            /* ── 7. Sticky scroll class ───────────────────────────────────── */
            var header = document.getElementById('header');
            if (header) {
                window.addEventListener('scroll', function () {
                    header.classList.toggle('header--scrolled', window.scrollY > 10);
                }, { passive: true });
            }

        }); /* end DOMContentLoaded */

    </script>
