<!-- Artists Section -->
    <section class="artists" id="artists">
      <div class="container">
<style>
/* ── Breadcrumb pill — site-wide ─────────────────────────────── */
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
        <span class="pg-breadcrumb__current"><i class="fas fa-star"></i> Artists</span>
    </div>
</nav>
@endunless
        <div class="section-header">
          <div class="sh-box">
            <p class="sh-subtitle">Legendary Performers</p>
            <h2 class="sh-title">Our Artists</h2>
            <span class="sh-bar"></span>
            <p class="sh-desc">Meet the incredible artists who make our events unforgettable</p>
          </div>
        </div>

        <div class="artists-grid">
          <div class="artist-card">
            <div class="artist-image">
              <img
                src="{{ asset('assets/images/events/newposters/new/sonu-dallas-flyer.jpeg') }}"
                alt="Sonu Nigam"
              />
            </div>
            <div class="artist-info">
              <h3 class="artist-name">Sonu Nigam</h3>
              <p class="artist-role">Legendary Playback Singer</p>
            </div>
          </div>

          <div class="artist-card">
            <div class="artist-image">
              <img src="{{ asset('assets/images/events/newposters/new/NITIN-MUKESH-DALLAS-FLYER-NEW.jpeg') }}" alt="Nitin Mukesh" />
            </div>
            <div class="artist-info">
              <h3 class="artist-name">Nitin Mukesh</h3>
              <p class="artist-role">Classical Singer</p>
            </div>
          </div>

          <div class="artist-card">
            <div class="artist-image">
              <img src="{{ asset('assets/images/events/newposters/new/Javed-ali-Flyer.jpeg')}}" alt="Javed Ali" />
            </div>
            <div class="artist-info">
              <h3 class="artist-name">Javed Ali</h3>
              <p class="artist-role">Playback Singer</p>
            </div>
          </div>

          <div class="artist-card">
            <div class="artist-image">
              <img
                src="{{ asset('assets/images/events/newposters/new/Nawazuddin-Dallas-Flyer-v2.jpeg') }}"
                alt="Nawazuddin Siddiqui"
              />
            </div>
            <div class="artist-info">
              <h3 class="artist-name">Nawazuddin Siddiqui</h3>
              <p class="artist-role">Actor & Performer</p>
            </div>
          </div>
        </div>
      </div>
    </section>
