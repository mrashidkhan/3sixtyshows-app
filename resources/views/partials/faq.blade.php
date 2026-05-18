<!-- FAQ Section -->
    <section class="faq-section" id="faq">
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
        <span class="pg-breadcrumb__current"><i class="fas fa-question-circle"></i> FAQ</span>
    </div>
</nav>
@endunless
        <div class="section-header">
          <div class="sh-box">
            <p class="sh-subtitle">Got Questions?</p>
            <h2 class="sh-title">Frequently Asked Questions</h2>
            <span class="sh-bar"></span>
            <p class="sh-desc">Everything you need to know about 3Sixtyshows</p>
          </div>
        </div>

        <div class="faq-wrapper">
          <div class="faq-item">
            <button class="faq-question" aria-expanded="false">
              <span>Is it free to use 3Sixtyshows?</span>
              <span class="faq-icon"><i class="fas fa-plus"></i></span>
            </button>
            <div class="faq-answer">
              <p>
                Yes! Browsing events and registering your interest on 3Sixtyshows
                is completely free. Ticket pricing varies by event and
                seating category. We also run exclusive promotions — such as our
                <strong>First 250 Sign-Ups Get FREE TICKETS</strong> offer — so
                registering early always pays off!
              </p>
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question" aria-expanded="false">
              <span>How do I use 3Sixtyshows?</span>
              <span class="faq-icon"><i class="fas fa-plus"></i></span>
            </button>
            <div class="faq-answer">
              <p>
                It's simple! Browse our upcoming events in the Events section,
                choose the concert you'd like to attend, and click the ticket
                button. Fill in your details in the registration form — your
                name, email, and phone number — and submit. You'll receive a
                confirmation email once your registration is processed.
              </p>
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question" aria-expanded="false">
              <span>How will I receive my tickets?</span>
              <span class="faq-icon"><i class="fas fa-plus"></i></span>
            </button>
            <div class="faq-answer">
              <p>
                All tickets are delivered digitally to your registered email
                address. You will receive a confirmation email with your ticket
                details and a QR code that you can show at the venue entrance.
                Please ensure you provide a valid and active email address
                during registration.
              </p>
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question" aria-expanded="false">
              <span>Are my payments secure?</span>
              <span class="faq-icon"><i class="fas fa-plus"></i></span>
            </button>
            <div class="faq-answer">
              <p>
                Absolutely. 3Sixtyshows uses industry-standard SSL encryption
                to protect all your personal and payment information. We partner
                with trusted and secure payment processors to ensure every
                transaction is safe. Your data is never shared with third
                parties without your consent.
              </p>
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question" aria-expanded="false">
              <span>Who do I contact for support?</span>
              <span class="faq-icon"><i class="fas fa-plus"></i></span>
            </button>
            <div class="faq-answer">
              <p>
                Our support team is always ready to help! You can reach us by
                calling <strong>855-360-SHOW</strong> or emailing us at
                <strong>
  <a href="mailto:info@3sixtyshows.com">info@3sixtyshows.com</a>
</strong>
. You can also use the Contact form on this page and we'll get
                back to you promptly. Follow us on Facebook and Instagram for
                the latest updates.
              </p>
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question" aria-expanded="false">
              <span>Can I get a refund if I can't attend?</span>
              <span class="faq-icon"><i class="fas fa-plus"></i></span>
            </button>
            <div class="faq-answer">
              <p>
                Refund eligibility depends on the specific event's terms and
                conditions. In general, refund requests made more than 7 days
                before the event date may be considered. Please contact our
                support team as soon as possible if you need assistance with a
                refund or ticket transfer.
              </p>
            </div>
          </div>

          <div class="faq-item">
            <button class="faq-question" aria-expanded="false">
              <span>What cities does 3Sixtyshows operate in?</span>
              <span class="faq-icon"><i class="fas fa-plus"></i></span>
            </button>
            <div class="faq-answer">
              <p>
                We currently organize events in <strong>Houston, TX</strong> and
                <strong>Dallas, TX</strong>. We are actively expanding our reach
                across Texas and beyond. Stay tuned to our website and social
                media pages for announcements about new cities and upcoming
                events.
              </p>
            </div>
          </div>
        </div>
        <!-- /.faq-wrapper -->
      </div>
    </section>
