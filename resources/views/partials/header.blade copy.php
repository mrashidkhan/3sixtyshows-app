<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title', '3Sixtyshows - Premier Bollywood Events in Texas')</title>
    <meta name="description" content="@yield('meta_description', 'Texas\'s premier Bollywood event organizer. Experience world-class entertainment with legendary artists.')" />

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    {{-- App Stylesheet --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

    {{-- Favicon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/favicon_io/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon_io/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon_io/favicon-16x16.png') }}" />
    <link rel="manifest" href="{{ asset('site.webmanifest') }}" />

    {{-- Open Graph --}}
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url('/') }}" />
    <meta property="og:title" content="@yield('og_title', '3Sixtyshows - Premier Bollywood Events in Texas')" />
    <meta property="og:description" content="@yield('og_description', 'Experience world-class Bollywood entertainment in Texas.')" />
    <meta property="og:image" content="{{ asset('assets/images/logos/logo.jpg') }}" />

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:url" content="{{ url('/') }}" />
    <meta name="twitter:title" content="@yield('twitter_title', '3Sixtyshows - Premier Bollywood Events in Texas')" />
    <meta name="twitter:description" content="@yield('twitter_description', 'Experience world-class Bollywood entertainment in Texas.')" />
    <meta name="twitter:image" content="{{ asset('assets/images/logos/logo.jpg') }}" />

    @stack('styles')
</head>
<body>

    {{-- TOP BAR --}}
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
                    <a href="https://www.facebook.com/3sixtyshows" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://www.instagram.com/3sixtyshows/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                        <i class="fab fa-instagram"></i> Instagram
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- HEADER --}}
    <header class="header" id="header">

        {{-- Logo + Nav + Register --}}
        <div class="header-nav-row">
            <div class="container">
                <div class="header-content">

                    <a href="{{ url('/') }}" class="logo">
                        <img src="{{ asset('assets/images/logos/logo.jpg') }}" alt="3Sixtyshows" />
                    </a>

                    <nav class="nav" id="nav">
                        <ul>
                            <li><a href="{{ route('index') }}"     class="nav-link {{ request()->routeIs('index')     ? 'nav-link--active' : '' }}">Home</a></li>
                            <li><a href="{{ route('aboutus') }}"   class="nav-link {{ request()->routeIs('aboutus')   ? 'nav-link--active' : '' }}">About Us</a></li>
                            <li><a href="{{ route('events') }}"    class="nav-link {{ request()->routeIs('events')    ? 'nav-link--active' : '' }}">Events</a></li>
                            {{-- ── NEW: Past Events ── --}}
                            <li>
                                <a href="{{ route('pastevents') }}"
                                   class="nav-link {{ request()->routeIs('pastevents') ? 'nav-link--active' : '' }}">
                                    Past Events
                                </a>
                            </li>
                            <li>
    <a href="{{ route('gallery.index') }}"
       class="nav-link {{ request()->routeIs('gallery.*') ? 'nav-link--active' : '' }}">
        Gallery
    </a>
</li>
                            <li><a href="{{ route('artists') }}"   class="nav-link {{ request()->routeIs('artists')   ? 'nav-link--active' : '' }}">Artists</a></li>
                            <li><a href="{{ route('faq') }}"       class="nav-link {{ request()->routeIs('faq')       ? 'nav-link--active' : '' }}">FAQ</a></li>
                            <li><a href="{{ route('contactus') }}" class="nav-link {{ request()->routeIs('contactus') ? 'nav-link--active' : '' }}">Contact Us</a></li>
                        </ul>
                    </nav>

                    <a href="{{ route('registration') }}" class="btn-header">
                        <i class="far fa-user-circle"></i>
                        Register
                    </a>

                    <button class="mobile-toggle" id="mobile-toggle" aria-label="Toggle menu">
                        <i class="fas fa-bars"></i>
                    </button>

                </div>
            </div>
        </div>

        {{-- Desktop Search Bar --}}
        <div class="header-search-row">
            <div class="container">
                <div class="header-search-bar">

                    <div class="search-segment search-seg-location">
                        <span class="search-seg-icon"><i class="fas fa-map-marker-alt"></i></span>
                        <div class="search-seg-body">
                            <span class="search-seg-label">Location</span>
                            <input type="text" class="search-seg-input" id="search-location"
                                   placeholder="City or Zip Code" aria-label="Location" />
                        </div>
                    </div>

                    <div class="search-divider"></div>

                    <div class="search-segment search-seg-dates" onclick="this.querySelector('input[type=date]').showPicker()">
                        <span class="search-seg-icon"><i class="far fa-calendar-alt"></i></span>
                        <div class="search-seg-body">
                            <span class="search-seg-label">Dates</span>
                            <input type="date" class="search-seg-input search-seg-date-input"
                                   id="event-date" name="event_date" aria-label="Select date" />
                        </div>
                    </div>

                    <div class="search-divider"></div>

                    <div class="search-segment search-seg-query">
                        <span class="search-seg-icon"><i class="fas fa-search"></i></span>
                        <div class="search-seg-body">
                            <span class="search-seg-label">Search</span>
                            <input type="text" class="search-seg-input" id="main-search-input"
                                   placeholder="Artist, Event or Venue" aria-label="Search" />
                        </div>
                    </div>

                    <button class="search-submit-btn" type="button" onclick="handleSearch()">
                        Search
                    </button>

                </div>
            </div>
        </div>

        {{-- Mobile Search Strip: 3 stacked rows --}}
        <div class="mobile-search-strip">
            <div class="mob-search-card">

                <div class="mob-search-row">
                    <span class="mob-search-icon"><i class="fas fa-map-marker-alt"></i></span>
                    <div class="mob-search-body">
                        <span class="mob-search-label">Location</span>
                        <input type="text" class="mob-search-input" id="mob-location"
                               placeholder="City or Zip Code" aria-label="Location" />
                    </div>
                </div>

                <div class="mob-search-sep"></div>

                <div class="mob-search-row">
                    <span class="mob-search-icon"><i class="far fa-calendar-alt"></i></span>
                    <div class="mob-search-body">
                        <span class="mob-search-label">Dates</span>
                        <input type="date" class="mob-search-input" id="mob-date" aria-label="Select date" />
                    </div>
                </div>

                <div class="mob-search-sep"></div>

                <div class="mob-search-row">
                    <span class="mob-search-icon"><i class="fas fa-search"></i></span>
                    <div class="mob-search-body">
                        <span class="mob-search-label">Search</span>
                        <input type="text" class="mob-search-input" id="mobile-search-input"
                               placeholder="Artist, Event or Venue" aria-label="Search" />
                    </div>
                </div>

                <button class="mob-search-btn" type="button" onclick="handleMobSearch()">
                    <i class="fas fa-search"></i>&nbsp; Search Events
                </button>

            </div>
        </div>

    </header>

    <script>
    function handleSearch() {
        var location   = document.getElementById('search-location');
        var date       = document.getElementById('event-date');
        var query      = document.getElementById('main-search-input');
        var params     = new URLSearchParams();
        if (location && location.value.trim()) params.set('location', location.value.trim());
        if (date     && date.value.trim())     params.set('date',     date.value.trim());
        if (query    && query.value.trim())    params.set('query',    query.value.trim());
        window.location.href = '{{ route("events") }}' + (params.toString() ? '?' + params.toString() : '');
    }

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
        var mobileToggle = document.getElementById('mobile-toggle');
        var nav          = document.getElementById('nav');

        if (mobileToggle && nav) {
            mobileToggle.addEventListener('click', function (e) {
                e.stopPropagation();
                nav.classList.toggle('active');
                var icon = mobileToggle.querySelector('i');
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-times');
            });
            document.addEventListener('click', function (e) {
                if (nav.classList.contains('active') &&
                    !nav.contains(e.target) &&
                    !mobileToggle.contains(e.target)) {
                    nav.classList.remove('active');
                    var icon = mobileToggle.querySelector('i');
                    icon.classList.add('fa-bars');
                    icon.classList.remove('fa-times');
                }
            });
            nav.querySelectorAll('.nav-link').forEach(function (link) {
                link.addEventListener('click', function () {
                    nav.classList.remove('active');
                    var icon = mobileToggle.querySelector('i');
                    icon.classList.add('fa-bars');
                    icon.classList.remove('fa-times');
                });
            });
        }

        ['search-location', 'event-date', 'main-search-input'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.addEventListener('keydown', function (e) { if (e.key === 'Enter') handleSearch(); });
        });
        ['mob-location', 'mob-date', 'mobile-search-input'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.addEventListener('keydown', function (e) { if (e.key === 'Enter') handleMobSearch(); });
        });
    });
    </script>
