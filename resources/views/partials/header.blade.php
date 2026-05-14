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
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" /> --}}
    @stack('early_styles')
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
    {{-- <meta property="og:image" content="{{ asset('assets/images/logos/3sixtyshows_og.jpg') }}" /> --}}
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />

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

        /* ── 4. Desktop dropdown panel ─────────────────────────────────────── */
        .nav-submenu {
            visibility: hidden;
            opacity: 0;
            pointer-events: none;
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(6px);
            min-width: 180px;
            padding-top: 10px;      /* invisible bridge — cursor can cross gap */
            background: transparent;
            z-index: 9999;
            transition: opacity 0.17s ease,
                        transform 0.17s ease,
                        visibility 0.17s ease;
        }

        .nav-submenu__inner {
            background: #120A14;
            border: 1px solid rgba(255,255,255,0.10);
            border-top: 2px solid #C8102E;
            border-radius: 10px;
            box-shadow: 0 16px 48px rgba(0,0,0,0.70);
            padding: 6px 0;
            overflow: hidden;
        }

        .nav-dropdown.open > .nav-submenu {
            visibility: visible;
            opacity: 1;
            pointer-events: auto;
            transform: translateX(-50%) translateY(0);
        }

        /* ── 5. Submenu items — desktop ────────────────────────────────────── */
        .nav-submenu__item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 22px;
            font-family: var(--font-body, 'DM Sans', sans-serif);
            font-size: 13px;
            font-weight: 600;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            white-space: nowrap;
            transition: background 0.14s ease, color 0.14s ease;
        }
        .nav-submenu__item:hover {
            background: rgba(200,16,46,0.16);
            color: #fff;
        }
        .nav-submenu__item.active {
            color: #D4A017;
            background: rgba(212,160,23,0.08);
        }
        .nav-submenu__item i {
            width: 16px;
            text-align: center;
            font-size: 13px;
            color: #C8102E;
            flex-shrink: 0;
        }
        .nav-submenu__item.active i {
            color: #D4A017;
        }

        .nav-submenu__divider {
            height: 1px;
            background: rgba(255,255,255,0.07);
            margin: 3px 14px;
        }

        /* ── Nav auth item — hidden on desktop ─────────────────────────────── */
        .nav-auth-item { display: none; }

        /* ── 6. Mobile ≤ 992px ─────────────────────────────────────────────── */
        @media (max-width: 992px) {

            /* ── Hide desktop Login/Logout button from header bar on mobile ── */
            .btn-header-wrapper { display: none !important; }
            .btn-header          { display: none !important; }

            /* ── Show Login/Logout inside the slide-in hamburger nav panel ── */
            .nav-auth-item {
                display: block;
                border-top: 1px solid rgba(255,255,255,0.08);
                margin-top: 10px;
                padding: 14px 0 4px;
            }

            .nav-auth-wrapper {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 6px;
                padding: 0 28px;
            }

            .nav-auth-btn {
                display: inline-flex;
                align-items: center;
                gap: 9px;
                padding: 9px 20px;
                border-radius: 50px;
                background: linear-gradient(135deg, #C8102E 0%, #9b0c22 100%);
                color: #fff !important;
                font-family: var(--font-body, 'DM Sans', sans-serif);
                font-weight: 700;
                font-size: 13px;
                letter-spacing: 0.5px;
                text-transform: uppercase;
                text-decoration: none;
                box-shadow: 0 2px 12px rgba(200,16,46,0.4);
                transition: all 0.2s ease;
            }

            .nav-auth-btn:hover {
                background: linear-gradient(135deg, #e01535 0%, #C8102E 100%);
                transform: translateY(-1px);
                color: #fff !important;
            }

            .nav-auth-btn i { font-size: 15px; }

            .nav-auth-username {
                font-family: var(--font-body, 'DM Sans', sans-serif);
                font-size: 12px;
                font-weight: 600;
                color: #ffffff;
                padding-left: 4px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 200px;
            }

            /* ── Mobile Gallery accordion ──────────────────────────────────── */

            /* Reset ALL desktop nav-submenu properties */
            .nav-submenu {
                position: static   !important;
                visibility: visible !important;
                opacity: 1          !important;
                pointer-events: auto !important;
                transform: none     !important;
                transition: none    !important;
                padding: 0          !important;
                display: none;
                background: transparent;
                min-width: unset;
                z-index: auto;
                width: 100%;
                top: auto;
                left: auto;
            }

            /* Accordion panel — full nav width, no indent */
            .nav-submenu__inner {
                background: rgba(200,16,46,0.07);
                border-left: 3px solid #C8102E;
                border-top: none;
                border-right: none;
                border-bottom: none;
                border-radius: 0;
                box-shadow: none;
                padding: 4px 0;
                margin: 0;
                width: 100%;
                box-sizing: border-box;
                overflow: visible;
            }

            .nav-dropdown.open > .nav-submenu { display: block; }

            /* Gallery trigger — same padding as other nav-links (from style.css) */
            .nav-dropdown > .nav-link {
                display: flex !important;
                width: 100%;
                justify-content: space-between;
                box-sizing: border-box;
            }

            /* Submenu items — indented 28px (matches nav-link padding) + 12px extra */
            .nav-submenu__item {
                padding: 13px 28px 13px 40px;
                font-size: 13px;
                color: rgba(255,255,255,0.80);
                white-space: normal;
                border-bottom: 1px solid rgba(255,255,255,0.05);
                width: 100%;
                box-sizing: border-box;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .nav-submenu__item:last-child { border-bottom: none; }
            .nav-submenu__item:hover,
            .nav-submenu__item:active {
                background: rgba(200,16,46,0.16);
                color: #fff;
            }
            .nav-submenu__item i {
                width: 16px;
                text-align: center;
                color: #C8102E;
                flex-shrink: 0;
            }

            /* Show divider on mobile */
            .nav-submenu__divider {
                display: block;
                height: 1px;
                background: rgba(255,255,255,0.06);
                margin: 0;
            }

            .nav-caret { margin-left: auto; }
            .nav-dropdown { width: 100%; }
        }

    </style>

    @stack('styles')

    {{-- Page-specific scripts that must load in <head> (e.g. seats.io chart.js) --}}
    @stack('head_scripts')

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
                        <span class="top-bar-text">855-360-SHOW</span>
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

        {{-- ── Row 1: Logo  |  Nav  |  Login/Logout (desktop)  |  Hamburger ── --}}
        <div class="header-nav-row">
            <div class="container">
                <div class="header-content">

                    {{-- Logo --}}
                    <a href="{{ url('/') }}" class="logo">
                        <img src="{{ asset('assets/images/logos/logo.png') }}"
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
                                 • Desktop: hover opens the panel via JS mouseenter/mouseleave.
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

                                <div class="nav-submenu" role="menu" aria-label="Gallery submenu">
                                    <div class="nav-submenu__inner">

                                        <a href="{{ route('gallery.index') }}"
                                           class="nav-submenu__item {{ request()->routeIs('gallery.*') ? 'active' : '' }}"
                                           role="menuitem">
                                            <i class="fas fa-images" aria-hidden="true"></i>
                                            Photos
                                        </a>

                                        <div class="nav-submenu__divider" role="separator"></div>

                                        <a href="{{ route('video-gallery.index') }}"
                                           class="nav-submenu__item {{ request()->routeIs('video-gallery.*') ? 'active' : '' }}"
                                           role="menuitem">
                                            <i class="fas fa-film" aria-hidden="true"></i>
                                            Videos
                                        </a>

                                    </div>
                                </div>

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

                            {{-- ── Mobile-only Login/Logout ──────────────────────────────
                                 Hidden on desktop via .nav-auth-item { display: none }.
                                 Shown on mobile (≤992px) at the bottom of the slide-in
                                 hamburger panel, below Contact Us, above the panel bottom.
                                 ────────────────────────────────────────────────────────── --}}
                            <li class="nav-auth-item">
                                @auth
                                    <div class="nav-auth-wrapper">
                                        <a href="{{ route('user_logout') }}" class="nav-auth-btn">
                                            <i class="far fa-user-circle"></i>
                                            Logout
                                        </a>
                                        <span class="nav-auth-username">{{ auth()->user()->name }}</span>
                                    </div>
                                @else
                                    <a href="{{ route('user_login') }}" class="nav-auth-btn">
                                        <i class="far fa-user-circle"></i>
                                        Login
                                    </a>
                                @endauth
                            </li>
                            {{-- end Mobile Login/Logout --}}

                        </ul>
                    </nav>
                    {{-- end .nav --}}

                    {{-- Desktop Login / Logout CTA (hidden on mobile via CSS) --}}
                    @auth
                        <div class="btn-header-wrapper">
                            <a href="{{ route('user_logout') }}" class="btn-header">
                                <i class="far fa-user-circle"></i>
                                Logout
                            </a>
                            <span class="btn-header-username">
                                {{ auth()->user()->name }}
                            </span>
                        </div>
                    @else
                        <a href="{{ route('user_login') }}" class="btn-header">
                            <i class="far fa-user-circle"></i>
                            Login
                        </a>
                    @endauth

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
                nav.querySelectorAll('li:not(.nav-dropdown):not(.nav-auth-item) .nav-link').forEach(function (link) {
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
                    header.classList.toggle('scrolled', window.scrollY > 50);
                });
            }

        }); /* end DOMContentLoaded */

    </script>

    @stack('scripts')
