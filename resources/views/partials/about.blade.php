<!-- About Section -->
    <section class="about-section" id="about">
      <div class="container">
<style>
/* ── Breadcrumb pill — site-wide consistent style ─────────── */
.pg-breadcrumb {
    display: flex;
    justify-content: center;
    margin-bottom: 24px;
    font-family: 'DM Sans', sans-serif;
}
.pg-breadcrumb__pill {
    display: inline-flex;
    align-items: center;
    flex-wrap: wrap;
    background: #fff;
    border: 1px solid #E2E2E2;
    border-radius: 999px;
    padding: 7px 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,.07);
}
.pg-breadcrumb__link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 600;
    color: #555;
    text-decoration: none;
    letter-spacing: .4px;
    text-transform: uppercase;
    transition: color .18s ease;
    white-space: nowrap;
}
.pg-breadcrumb__link i { font-size: 10px; color: #C8102E; }
.pg-breadcrumb__link:hover { color: #C8102E; text-decoration: none; }
.pg-breadcrumb__sep {
    display: inline-flex;
    align-items: center;
    margin: 0 8px;
    color: #D4A017;
    font-size: 11px;
    font-weight: 700;
    user-select: none;
}
.pg-breadcrumb__current {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 700;
    color: #C8102E;
    letter-spacing: .4px;
    text-transform: uppercase;
}
@media (max-width: 576px) {
    .pg-breadcrumb__pill { padding: 6px 14px; border-radius: 12px; }
    .pg-breadcrumb__link, .pg-breadcrumb__current { font-size: 10px; }
    .pg-breadcrumb__sep { margin: 0 5px; }
}
</style>
@unless(request()->routeIs('index'))
<nav class="pg-breadcrumb" aria-label="Breadcrumb">
    <div class="pg-breadcrumb__pill">
        <a href="{{ route('index') }}" class="pg-breadcrumb__link"><i class="fas fa-home"></i> Home</a>
        <span class="pg-breadcrumb__sep">&#8250;</span>
        <span class="pg-breadcrumb__current"><i class="fas fa-info-circle"></i> About Us</span>
    </div>
</nav>
@endunless
        <div class="section-header">
          <div class="sh-box">
            <p class="sh-subtitle">Who We Are</p>
            <h2 class="sh-title">About 3Sixtyshows</h2>
            <span class="sh-bar"></span>
            <p class="sh-desc">Texas's premier destination for world-class Bollywood entertainment</p>
          </div>
        </div>



        <!-- Main Content -->
        <div class="about-main">
          <div class="about-text-block">
            <h3 class="about-subtitle">
              <i class="fas fa-star"></i> Our Story
            </h3>
            <p>
              3Sixtyshows was born from a passion for bringing the magic of
              Bollywood to the heart of Texas. We are a dedicated event
              management company that bridges the gap between legendary South
              Asian artists and their fans across Houston and Dallas. From
              intimate musical evenings to grand concert spectacles, we craft
              experiences that resonate deeply with our community.
            </p>
          </div>

          <div class="about-text-block">
            <h3 class="about-subtitle">
              <i class="fas fa-bullseye"></i> Our Mission
            </h3>
            <p>
              To deliver world-class Bollywood entertainment to the South Asian
              community in Texas — making every show a memory that lasts a
              lifetime. We are committed to seamless ticketing, top-tier
              production, and an experience that matches the grandeur of the
              artists we bring to your city.
            </p>
          </div>
        </div>

        <!-- Feature Cards -->
        <div class="about-features">
          <div class="about-feature-card">
            <div class="feature-icon">
              <i class="fas fa-ticket-alt"></i>
            </div>
            <h4>Easy Ticketing</h4>
            <p>
              Hassle-free ticket booking with instant confirmations sent
              directly to your inbox.
            </p>
          </div>
          <div class="about-feature-card">
            <div class="feature-icon">
              <i class="fas fa-shield-alt"></i>
            </div>
            <h4>Trusted & Secure</h4>
            <p>
              Every transaction is safe and every ticket is genuine — your trust
              is our priority.
            </p>
          </div>
          <div class="about-feature-card">
            <div class="feature-icon">
              <i class="fas fa-music"></i>
            </div>
            <h4>Top Artists</h4>
            <p>
              We partner with legendary Bollywood and Punjabi artists to bring
              the best to Texas.
            </p>
          </div>
          <div class="about-feature-card">
            <div class="feature-icon">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <h4>Premium Venues</h4>
            <p>
              World-class arenas in Houston and Dallas for an unforgettable live
              experience.
            </p>
          </div>
        </div>

        <div class="about-cta">
          <a href="{{ route('events') }}" class="btn-primary">
            <i class="fas fa-calendar-alt"></i> Explore Our Events
          </a>
          <a href="{{ route('contactus') }}" class="btn-secondary-dark">
            <i class="fas fa-envelope"></i> Get in Touch
          </a>
        </div>
      </div>
    </section>
