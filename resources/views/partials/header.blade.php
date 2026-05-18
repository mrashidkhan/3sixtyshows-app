<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title', '3Sixtyshows - Premier Bollywood Events in Texas')</title>
    <meta name="description" content="@yield('meta_description', 'Texas\'s premier Bollywood event organizer. Experience world-class entertainment with legendary artists.')" />

    {{-- Google Fonts — Oswald · Playfair Display · DM Sans (matches style.css §02) --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&family=Oswald:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

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

        /* ══════════════════════════════════════════════════════════════════
           HEADER — Professional Light Theme
           White background · Crimson brand · Gold accents
           ══════════════════════════════════════════════════════════════════ */

        /* ── Top Bar — light warm strip ─────────────────────────────────── */
        .top-bar {
            background: #1A1A2E !important;
            border-bottom: 1px solid rgba(200,16,46,0.15) !important;
        }
        .top-bar-left span,
        .top-bar-left a {
            color: rgba(255,255,255,0.70) !important;
            font-size: 12px !important;
            font-family: 'Inter', sans-serif !important;
        }
        .top-bar-left i { color: #D4A017 !important; }
        .top-bar-left a:hover { color: #D4A017 !important; }
        .top-bar-right a {
            color: rgba(255,255,255,0.70) !important;
            font-size: 12px !important;
            font-family: 'Inter', sans-serif !important;
        }
        .top-bar-right a:hover {
            color: #D4A017 !important;
            background: rgba(212,160,23,0.08) !important;
        }
        .top-bar-right a + a {
            border-left-color: rgba(255,255,255,0.12) !important;
        }

        /* ── Main Header — WHITE background ────────────────────────────── */
        .header {
            background: #FFFFFF !important;
            background-image: none !important;
            border-bottom: none !important;
            box-shadow: 0 2px 20px rgba(0,0,0,0.09), 0 1px 4px rgba(0,0,0,0.05) !important;
            position: sticky !important;
            top: 0 !important;
        }

        /* Crimson bottom accent line */
        .header::after {
            content: '' !important;
            display: block !important;
            position: absolute !important;
            bottom: 0; left: 0; right: 0 !important;
            height: 3px !important;
            background: linear-gradient(90deg, #C8102E 0%, #D4A017 50%, #C8102E 100%) !important;
        }

        /* ── Nav row separator ── */
        .header-nav-row {
            border-bottom: 1px solid #F0F0F0 !important;
        }

        /* ── Logo ── */
        .logo img {
            height: 52px !important;
            background: transparent !important;
            border-radius: 0 !important;
            padding: 0 !important;
        }

        /* ── Nav links — dark text on white bg ─────────────────────────── */
        .nav-link {
            color: #1A1A2E !important;
            font-family: 'Poppins', sans-serif !important;
            font-size: 12.5px !important;
            font-weight: 600 !important;
            letter-spacing: 0.8px !important;
            text-transform: uppercase !important;
            padding: 8px 14px !important;
            transition: color 0.18s ease !important;
            position: relative !important;
        }
        .nav-link:hover { color: #C8102E !important; }

        /* Active = crimson with underbar */
        .nav-link.active {
            color: #C8102E !important;
        }
        .nav-link.active::after {
            content: '' !important;
            position: absolute !important;
            bottom: -1px; left: 14px; right: 14px !important;
            height: 2px !important;
            background: #C8102E !important;
            border-radius: 2px !important;
            display: block !important;
        }

        /* ── Nav caret ── */
        .nav-caret {
            font-size: 8px;
            opacity: 0.50;
            flex-shrink: 0;
            transition: transform 0.22s ease;
        }
        .nav-dropdown.open > .nav-link .nav-caret { transform: rotate(180deg); }

        /* ── Login button ── */
        .btn-header-login {
            background: linear-gradient(135deg, #C8102E 0%, #9e0b22 100%) !important;
            color: #fff !important;
            border: none !important;
            padding: 10px 22px !important;
            border-radius: 999px !important;
            font-family: 'Poppins', sans-serif !important;
            font-weight: 700 !important;
            font-size: 12.5px !important;
            letter-spacing: 0.5px !important;
            box-shadow: 0 4px 14px rgba(200,16,46,0.30) !important;
            transition: all 0.22s ease !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 7px !important;
        }
        .btn-header-login:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 22px rgba(200,16,46,0.40) !important;
            color: #fff !important;
        }

        /* ── Search row — light gray strip ─────────────────────────────── */
        .header-search-row {
            background: #F8F9FA !important;
            padding: 14px 0 18px !important;
            border-bottom: 2px solid #C8102E !important;
        }
        .header-search-bar {
            border-radius: 12px !important;
            box-shadow: 0 2px 16px rgba(0,0,0,0.10), 0 0 0 2px rgba(200,16,46,0.12) !important;
            height: 60px !important;
            background: #FFFFFF !important;
        }

        /* Search segments */
        .search-segment { background: #FFFFFF !important; }
        .search-segment:hover { background: #FFF8F8 !important; }
        .search-seg-label {
            color: #C8102E !important;
            font-family: 'Inter', sans-serif !important;
            font-weight: 700 !important;
            font-size: 10px !important;
            letter-spacing: 1px !important;
        }
        .search-seg-input, .search-seg-input::placeholder {
            color: #6C757D !important;
            font-family: 'Inter', sans-serif !important;
        }
        .search-seg-icon i { color: #C8102E !important; }

        /* ── Desktop dropdown panel ─────────────────────────────────────── */
        .nav-dropdown { position: relative; }
        .nav-dropdown > .nav-link {
            display: inline-flex !important;
            align-items: center;
            gap: 4px;
        }

        .nav-submenu {
            visibility: hidden;
            opacity: 0;
            pointer-events: none;
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(6px);
            min-width: 180px;
            padding-top: 10px;
            background: transparent;
            z-index: 9999;
            transition: opacity 0.17s ease, transform 0.17s ease, visibility 0.17s ease;
        }

        .nav-submenu__inner {
            background: #FFFFFF;
            border: 1px solid #E9ECEF;
            border-top: 3px solid #C8102E;
            border-radius: 12px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.14), 0 2px 8px rgba(0,0,0,0.07);
            padding: 6px 0;
            overflow: hidden;
        }

        .nav-dropdown.open > .nav-submenu {
            visibility: visible;
            opacity: 1;
            pointer-events: auto;
            transform: translateX(-50%) translateY(0);
        }

        /* ── Submenu items — dark text on white ─────────────────────────── */
        .nav-submenu__item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 22px;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: #1A1A2E;
            text-decoration: none;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            white-space: nowrap;
            transition: background 0.14s ease, color 0.14s ease;
        }
        .nav-submenu__item:hover {
            background: #FFF0F0;
            color: #C8102E;
        }
        .nav-submenu__item.active {
            color: #C8102E;
            background: rgba(200,16,46,0.05);
        }
        .nav-submenu__item i {
            width: 16px;
            text-align: center;
            font-size: 13px;
            color: #C8102E;
            flex-shrink: 0;
        }
        .nav-submenu__item.active i { color: #C8102E; }

        .nav-submenu__divider {
            height: 1px;
            background: #F0F0F0;
            margin: 3px 14px;
        }

        /* ── Mobile hamburger ─────────────────────────────────────────────── */
        .mobile-toggle {
            color: #1A1A2E !important;
        }

        /* ── Nav auth item — hidden on desktop ─────────────────────────── */
        .nav-auth-item { display: none; }

        /* ── ROOT FIX — desktop submenu overflow ────────────────────────── */
        @media (min-width: 993px) {
            .nav { overflow: visible !important; }
        }

        /* ══════════════════════════════════════════════════════════════════
           MOBILE ≤ 992px
           ══════════════════════════════════════════════════════════════════ */
        @media (max-width: 992px) {

            .btn-header-wrapper { display: none !important; }
            .btn-header          { display: none !important; }

            /* Mobile slide-in nav — white background */
            .nav {
                background-color: #FFFFFF !important;
                border-left: 3px solid #C8102E !important;
                box-shadow: -6px 0 30px rgba(0,0,0,0.15) !important;
            }

            /* Mobile nav links — dark on white */
            .nav-link {
                color: #1A1A2E !important;
                border-bottom: 1px solid #F0F0F0 !important;
            }
            .nav-link:hover,
            .nav-link.active { color: #C8102E !important; background: #FFF0F0 !important; }

            /* Auth item in mobile nav */
            .nav-auth-item {
                display: block;
                border-top: 1px solid #F0F0F0;
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
                font-family: 'Poppins', sans-serif;
                font-weight: 700;
                font-size: 13px;
                letter-spacing: 0.5px;
                text-transform: uppercase;
                text-decoration: none;
                box-shadow: 0 2px 12px rgba(200,16,46,0.40);
                transition: all 0.2s ease;
            }
            .nav-auth-btn:hover {
                background: linear-gradient(135deg, #e01535 0%, #C8102E 100%);
                transform: translateY(-1px);
                color: #fff !important;
            }
            .nav-auth-btn i { font-size: 15px; }
            .nav-auth-username {
                font-family: 'Poppins', sans-serif;
                font-size: 12px;
                font-weight: 600;
                color: #1A1A2E;
                padding-left: 4px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 200px;
            }

            /* Mobile Gallery accordion */
            .nav-submenu {
                position: static !important;
                visibility: visible !important;
                opacity: 1 !important;
                pointer-events: auto !important;
                transform: none !important;
                transition: none !important;
                padding: 0 !important;
                display: none !important;
                background: transparent !important;
                min-width: unset !important;
                z-index: auto !important;
                width: 100% !important;
                top: auto !important;
                left: auto !important;
            }
            .nav-submenu__inner {
                background: #FFF8F8 !important;
                border-left: 3px solid #C8102E !important;
                border-top: none !important;
                border-right: none !important;
                border-bottom: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                padding: 4px 0 !important;
                margin: 0 !important;
                width: 100% !important;
                box-sizing: border-box !important;
                overflow: visible !important;
            }
            .nav-dropdown.open > .nav-submenu { display: block !important; }

            .nav-dropdown > .nav-link {
                display: flex !important;
                width: 100%;
                justify-content: space-between;
                box-sizing: border-box;
            }
            .nav-submenu__item {
                padding: 13px 28px 13px 40px !important;
                font-size: 12px !important;
                color: #1A1A2E !important;
                white-space: normal !important;
                border-bottom: 1px solid #F0F0F0 !important;
                width: 100% !important;
                box-sizing: border-box !important;
                display: flex !important;
                align-items: center !important;
                gap: 10px !important;
            }
            .nav-submenu__item:last-child { border-bottom: none !important; }
            .nav-submenu__item:hover,
            .nav-submenu__item:active {
                background: #FFF0F0 !important;
                color: #C8102E !important;
            }
            .nav-submenu__item i { color: #C8102E !important; }
            .nav-submenu__divider {
                display: block !important;
                height: 1px !important;
                background: #F0F0F0 !important;
                margin: 0 !important;
            }
            .nav-caret { margin-left: auto; }
            .nav-dropdown { width: 100%; }

        } /* end @media 992px */

    </style>


    @stack('styles')

    {{-- Page-specific scripts that must load in <head> (e.g. seats.io chart.js) --}}
    @stack('head_scripts')
    <script>
    /* Fade-up on scroll — runs once DOM is ready */
    document.addEventListener('DOMContentLoaded', function () {
        var targets = document.querySelectorAll(
            '.about-text-block, .about-feature-card, .event-card, ' +
            '.otc-card, .artist-card, .faq-item, .section-header'
        );
        if (!targets.length) return;
        targets.forEach(function (el) { el.classList.add('fade-up'); });
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.10 });
        targets.forEach(function (el) { io.observe(el); });
    });
    </script>

</head>
<body>

    {{-- ═══════════════════════════════════════════════════════════════════
         TOP BAR
         ═══════════════════════════════════════════════════════════════════ --}}
    {{-- <div class="top-bar">
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
    </div> --}}
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
                                   class="nav-link {{ request()->routeIs('pastevents') || request()->is('shows/*') ? 'active' : '' }}">
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
