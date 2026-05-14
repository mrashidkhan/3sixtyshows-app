<!-- Contact Section -->
<section class="contact" id="contact">
  <div class="container">

    <div class="section-header">
      <div class="sh-box">
        <p class="sh-subtitle">Contact Us</p>
        <h2 class="sh-title">Get in Touch</h2>
        <span class="sh-bar"></span>
        <p class="sh-desc">Have questions? We'd love to hear from you.</p>
      </div>
    </div>

    <div class="contact-wrapper">

      {{-- Success / Error Alert --}}
      <div id="formAlert" style="display:none; padding:14px 18px; border-radius:8px; margin-bottom:24px; font-size:14px; font-weight:500;"></div>

      <form class="contact-form" id="contactForm">
        @csrf

        <div class="cf-row">
          <div class="cf-group">
            <label class="cf-label" for="name">
              <i class="fas fa-user"></i> Full Name
            </label>
            <input class="cf-input" type="text" id="name" name="name"
                   placeholder="Your full name" autocomplete="name" required />
          </div>
          <div class="cf-group">
            <label class="cf-label" for="email">
              <i class="fas fa-envelope"></i> Email Address
            </label>
            <input class="cf-input" type="email" id="email" name="email"
                   placeholder="your@email.com" autocomplete="email" required />
          </div>
        </div>

        <div class="cf-row">
          <div class="cf-group">
            <label class="cf-label" for="phone">
              <i class="fas fa-phone"></i> Phone Number
            </label>
            <input class="cf-input" type="tel" id="phone" name="phone"
                   placeholder="(555) 000-0000" autocomplete="tel" required />
          </div>
          <div class="cf-group">
            <label class="cf-label" for="subject">
              <i class="fas fa-tag"></i> Subject
            </label>
            <input class="cf-input" type="text" id="subject" name="subject"
                   placeholder="How can we help?" autocomplete="off" required />
          </div>
        </div>

        <div class="cf-group">
          <label class="cf-label" for="message">
            <i class="fas fa-comment-alt"></i> Message
          </label>
          <textarea class="cf-input cf-textarea" id="message" name="message"
                    rows="5" placeholder="Tell us more…" autocomplete="off" required></textarea>
        </div>

        {{-- Contact info strip --}}
        <div class="cf-info-strip">
          <span><i class="fas fa-phone"></i> 855-360-SHOW</span>
          <span><i class="fas fa-envelope"></i> info@3sixtyshows.com</span>
          <span><i class="fas fa-map-marker-alt"></i> Dallas &amp; Houston, TX</span>
        </div>

        <button type="submit" class="cf-submit" id="submitBtn">
          <i class="fas fa-paper-plane"></i> Send Message
        </button>

      </form>
    </div>
  </div>
</section>

<style>
/* ── Contact Section ─────────────────────────────────────────── */
.contact {
    padding: 80px 0;
    background-color: #F5F0E8;
}

.contact-wrapper {
    max-width: 780px;
    margin: 0 auto;
}

/* ── Form card ───────────────────────────────────────────────── */
.contact-form {
    background: #FFFFFF;
    border-radius: 16px;
    padding: 44px 48px;
    border: 1px solid #E0D5C5;
    box-shadow: 0 4px 24px rgba(0,0,0,.08), 0 1px 4px rgba(0,0,0,.05);
}

/* ── Row layout ──────────────────────────────────────────────── */
.cf-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

/* ── Field group ─────────────────────────────────────────────── */
.cf-group {
    display: flex;
    flex-direction: column;
    gap: 7px;
    margin-bottom: 20px;
}
.cf-group:last-child { margin-bottom: 0; }

/* ── Label ───────────────────────────────────────────────────── */
.cf-label {
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 11.5px;
    font-weight: 700;
    color: #3A3028;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.cf-label i {
    color: #C8102E;
    font-size: 11px;
    width: 14px;
    text-align: center;
}

/* ── Input / Textarea ────────────────────────────────────────── */
.cf-input {
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 14.5px;
    color: #1A1208;
    background: #FDFAF6;
    border: 1.5px solid #D0C5B0;
    border-radius: 10px;
    padding: 12px 16px;
    width: 100%;
    outline: none;
    transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
    box-sizing: border-box;
}
.cf-input::placeholder {
    color: #A89880;
    font-size: 13.5px;
}
.cf-input:hover {
    border-color: #B8A898;
    background: #FEFCF9;
}
.cf-input:focus {
    border-color: #C8102E;
    background: #FFFFFF;
    box-shadow: 0 0 0 3px rgba(200,16,46,.10);
}
.cf-textarea {
    resize: vertical;
    min-height: 120px;
    line-height: 1.6;
}

/* ── Info strip ──────────────────────────────────────────────── */
.cf-info-strip {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px 28px;
    margin: 24px 0 28px;
    padding: 16px 20px;
    background: #F5F0E8;
    border-radius: 10px;
    border: 1px solid #E0D5C5;
}
.cf-info-strip span {
    font-size: 13px;
    font-weight: 600;
    color: #3A3028;
    display: flex;
    align-items: center;
    gap: 7px;
    font-family: var(--font-body, 'DM Sans', sans-serif);
}
.cf-info-strip i {
    color: #C8102E;
    font-size: 13px;
    width: 16px;
    text-align: center;
}

/* ── Submit button ───────────────────────────────────────────── */
.cf-submit {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    background: linear-gradient(135deg, #C8102E 0%, #9e0b22 100%);
    color: #FFFFFF;
    font-family: var(--font-body, 'DM Sans', sans-serif);
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 14px 36px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    width: 100%;
    justify-content: center;
    box-shadow: 0 4px 20px rgba(200,16,46,.30);
    transition: transform .2s ease, box-shadow .2s ease;
}
.cf-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(200,16,46,.42);
}
.cf-submit:active { transform: translateY(0); }
.cf-submit:disabled {
    opacity: 0.65;
    cursor: not-allowed;
    transform: none;
}

/* ── Responsive ──────────────────────────────────────────────── */
@media (max-width: 640px) {
    .contact-form { padding: 28px 22px; }
    .cf-row { grid-template-columns: 1fr; gap: 0; }
    .cf-info-strip { flex-direction: column; align-items: flex-start; gap: 10px; }
}
</style>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form      = this;
    const btn       = document.getElementById('submitBtn');
    const msgBox    = document.getElementById('formAlert');
    const formData  = new FormData(form);

    btn.disabled    = true;
    btn.innerHTML   = '<i class="fas fa-spinner fa-spin"></i> Sending…';
    msgBox.style.display = 'none';

    function showMsg(msg, success) {
        msgBox.style.background = success ? '#d4edda' : '#f8d7da';
        msgBox.style.color      = success ? '#155724' : '#721c24';
        msgBox.style.border     = success ? '1px solid #c3e6cb' : '1px solid #f5c6cb';
        msgBox.textContent      = msg;
        msgBox.style.display    = 'block';
        msgBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    fetch('{{ route("contact.send") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(function(res) {
        if (res.status === 419) {
            throw new Error('Session expired. Please refresh the page and try again.');
        }
        if (res.status === 422) {
            return res.json().then(function(data) {
                const messages = data.errors
                    ? Object.values(data.errors).flat().join(' ')
                    : (data.message || 'Validation error. Please check your inputs.');
                throw new Error(messages);
            });
        }
        if (!res.ok) {
            throw new Error('Server error (' + res.status + '). Please try again later.');
        }
        return res.json();
    })
    .then(function(data) {
        if (data.success) {
            showMsg(data.message || "Message sent successfully! We'll be in touch soon.", true);
            form.reset();
        } else {
            showMsg(data.message || 'Something went wrong. Please try again.', false);
        }
    })
    .catch(function(err) {
        showMsg(err.message || 'Unexpected error. Please try again.', false);
        console.error('[ContactForm]', err);
    })
    .finally(function() {
        btn.disabled  = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Message';
    });
});
</script>
