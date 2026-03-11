{{-- ═══════════════════════════════════════════════
     partials/registration.blade.php
     OpalTickets — Free Ticket Registration Section
     ═══════════════════════════════════════════════ --}}

<style>
/* ── Registration Page Variables ───────────────────── */
:root {
  --reg-navy:   #0d1535;
  --reg-navy2:  #111d45;
  --reg-teal:   #00c9a7;
  --reg-red:    #e8293a;
  --reg-purple: #7b2ff7;
  --reg-gray:   #8892b0;
  --reg-border: rgba(255,255,255,0.08);
  --reg-text:   #ccd6f6;
  --opal-blue:  #346AB4;
}

/* ── Top Promo Banner ───────────────────────────────── */
.reg-top-banner {
  background: linear-gradient(135deg, #c0392b, #8e24aa);
  text-align: center;
  padding: 13px 20px;
  position: relative;
  overflow: hidden;
}
.reg-top-banner::after {
  content: '';
  position: absolute; top: 0; left: -120%;
  width: 50%; height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
  animation: reg-sweep 3.5s linear infinite;
}
@keyframes reg-sweep { to { left: 170%; } }
.reg-top-banner h2 {
  font-size: clamp(0.88rem, 2.5vw, 1.05rem);
  font-weight: 700; letter-spacing: 1.5px;
  text-transform: uppercase; position: relative;
  color: #fff;
}
.reg-top-banner p {
  font-size: clamp(0.75rem, 2vw, 0.85rem);
  opacity: 0.9; margin-top: 3px;
  position: relative; color: #fff;
}

/* ── Hero ───────────────────────────────────────────── */
.reg-hero {
  background: linear-gradient(180deg, #1a0a2e 0%, var(--reg-navy) 100%);
  padding: 52px 20px 40px;
  text-align: center;
  border-bottom: 1px solid var(--reg-border);
  position: relative; overflow: hidden;
}
.reg-hero::before {
  content: '';
  position: absolute; top: -200px; left: 50%;
  transform: translateX(-50%);
  width: 700px; height: 400px;
  background: radial-gradient(ellipse, rgba(0,201,167,0.07) 0%, transparent 70%);
  pointer-events: none;
}
.reg-hero h1 {
  font-size: clamp(1.5rem, 4.5vw, 2.6rem);
  font-weight: 800; letter-spacing: 1px;
  line-height: 1.2; position: relative;
  color: #fff;
}
.reg-hero h1 .hl { color: var(--reg-teal); }
.reg-hero p {
  color: var(--reg-gray);
  font-size: clamp(0.85rem, 2vw, 1rem);
  margin-top: 10px; position: relative;
}
.reg-spots-pill {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(230,41,58,0.12);
  border: 1px solid rgba(230,41,58,0.35);
  border-radius: 30px; padding: 9px 24px;
  margin-top: 18px; font-size: 0.82rem; font-weight: 600;
  color: #ff7070; letter-spacing: 0.5px;
  animation: reg-glow 2.5s ease-in-out infinite;
  position: relative;
}
@keyframes reg-glow {
  0%,100% { box-shadow: 0 0 0 0 rgba(230,41,58,0.25); }
  50%      { box-shadow: 0 0 0 8px rgba(230,41,58,0); }
}

/* ── Main Container ─────────────────────────────────── */
.reg-container {
  max-width: 1040px;
  margin: 0 auto;
  padding: 0 20px 60px;
  background: var(--reg-navy);
}

/* ── Section Title ──────────────────────────────────── */
.reg-sec-title {
  font-size: clamp(0.85rem, 2.5vw, 1rem);
  font-weight: 700; color: var(--reg-teal);
  text-transform: uppercase; letter-spacing: 3px;
  margin: 44px 0 20px;
  display: flex; align-items: center; gap: 14px;
}
.reg-sec-title::after {
  content: ''; flex: 1; height: 1px;
  background: linear-gradient(90deg, rgba(0,201,167,0.4), transparent);
}

/* ── Event Cards ────────────────────────────────────── */
.reg-events-grid {
  display: grid;
  grid-template-columns: minmax(0, 500px);
  gap: 18px; margin-bottom: 36px;
  justify-content: center;
}
.reg-event-card {
  background: var(--reg-navy2);
  border: 1px solid var(--reg-border);
  border-radius: 12px; overflow: hidden;
  transition: transform 0.3s, border-color 0.3s, box-shadow 0.3s;
}
.reg-event-card:hover {
  transform: translateY(-5px);
  border-color: rgba(0,201,167,0.4);
  box-shadow: 0 12px 35px rgba(0,0,0,0.4);
}
.reg-event-card .poster-wrap { overflow: hidden; }
.reg-event-card img {
  width: 100%; aspect-ratio: 16/9;
  object-fit: cover; display: block;
  transition: transform 0.4s;
}
.reg-event-card:hover img { transform: scale(1.04); }
.reg-event-card-body { padding: 14px 16px; }
.reg-event-card-body h3 {
  font-size: 0.9rem; font-weight: 700;
  color: #fff; margin-bottom: 4px;
}
.reg-event-card-body p {
  font-size: 0.78rem; color: var(--reg-gray);
}
.reg-free-tag {
  display: inline-block;
  background: linear-gradient(135deg, var(--reg-teal), #00a08a);
  color: #000; font-size: 0.68rem; font-weight: 800;
  text-transform: uppercase; letter-spacing: 1px;
  padding: 3px 10px; border-radius: 20px; margin-top: 8px;
}

/* ── Info Box ───────────────────────────────────────── */
.reg-info-box {
  background: rgba(0,201,167,0.06);
  border: 1px solid rgba(0,201,167,0.2);
  border-radius: 10px; padding: 20px 24px;
  margin-bottom: 36px;
}
.reg-info-box h4 {
  font-size: 0.88rem; font-weight: 700;
  color: var(--reg-teal); margin-bottom: 12px;
}
.reg-info-box ul {
  list-style: none; padding: 0;
  display: flex; flex-direction: column; gap: 8px;
}
.reg-info-box ul li {
  font-size: 0.82rem; color: var(--reg-text);
  padding-left: 18px; position: relative;
}
.reg-info-box ul li::before {
  content: '✓'; position: absolute; left: 0;
  color: var(--reg-teal); font-weight: 700;
}

/* ── Form Card ──────────────────────────────────────── */
.reg-form-card {
  background: var(--reg-navy2);
  border: 1px solid var(--reg-border);
  border-radius: 16px; padding: 36px 32px;
  margin-bottom: 36px;
  box-shadow: 0 8px 40px rgba(0,0,0,0.3);
}
.reg-form-card h2 {
  font-size: clamp(1.1rem, 3vw, 1.4rem);
  font-weight: 800; color: #fff;
  margin-bottom: 28px;
}
.reg-form-card h2 .hl { color: var(--reg-teal); }

.reg-form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}
@media (max-width: 640px) {
  .reg-form-grid { grid-template-columns: 1fr; }
}

.reg-form-group { display: flex; flex-direction: column; gap: 6px; }
.reg-form-group.full { grid-column: 1 / -1; }

.reg-form-group label {
  font-size: 0.8rem; font-weight: 600;
  color: var(--reg-text); letter-spacing: 0.5px;
}
.req { color: var(--reg-red); margin-left: 2px; }

.reg-form-group input,
.reg-form-group select {
  background: #1e2d5a;
  border: 1px solid rgba(255,255,255,0.15);
  border-radius: 8px; padding: 12px 40px 12px 14px;
  color: #fff; font-family: 'Poppins', sans-serif;
  outline: none;
  transition: border-color 0.25s, background 0.25s;
  width: 100%;
  font-size: 16px; /* iOS zoom prevention */
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
}
.reg-form-group input { padding: 12px 14px; }
.reg-form-group input::placeholder { color: rgba(255,255,255,0.35); }
.reg-form-group select {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%2300c9a7' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 14px center;
  cursor: pointer;
}
.reg-form-group select option {
  background: #1a2660;
  color: #fff;
  padding: 8px;
}
.reg-form-group select option:disabled,
.reg-form-group select option[value=""] {
  color: rgba(255,255,255,0.45);
}
.reg-form-group input:focus,
.reg-form-group select:focus {
  border-color: var(--reg-teal);
  background-color: #1f3068;
}
.reg-form-group input.err-field,
.reg-form-group select.err-field {
  border-color: var(--reg-red);
  background-color: #2a1a2e;
}
.reg-err {
  font-size: 0.72rem; color: #ff7070;
  display: none;
}
.reg-err.show { display: block; }

/* Checkbox */
.reg-check-wrap {
  display: flex; gap: 12px; align-items: flex-start;
}
.reg-check-wrap input[type="checkbox"] {
  width: 18px; height: 18px; min-width: 18px;
  accent-color: var(--reg-teal);
  margin-top: 2px; cursor: pointer;
  border-radius: 3px; padding: 0;
  /* Restore native checkbox rendering overridden by global input styles */
  -webkit-appearance: checkbox !important;
  -moz-appearance: checkbox !important;
  appearance: checkbox !important;
  background: revert !important;
  border: revert !important;
}
.reg-check-wrap label {
  font-size: 0.78rem; color: var(--reg-gray);
  line-height: 1.5; cursor: pointer;
}

/* Submit area */
.reg-submit-area {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 0;
}
.reg-urgency {
  font-size: 0.8rem; color: #ff7070;
  font-weight: 600; margin-bottom: 14px;
}
.reg-btn-submit {
  background: linear-gradient(135deg, var(--reg-red), var(--reg-purple));
  color: #fff; border: none; cursor: pointer;
  padding: 16px 40px; border-radius: 50px;
  font-family: 'Poppins', sans-serif;
  font-size: 1rem; font-weight: 800;
  letter-spacing: 1px; text-transform: uppercase;
  transition: transform 0.2s, box-shadow 0.2s;
  box-shadow: 0 6px 30px rgba(232,41,58,0.35);
  width: 100%; max-width: 360px;
  display: block;
  margin: 0 auto;
  -webkit-tap-highlight-color: transparent;
  touch-action: manipulation;
}
.reg-btn-submit:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 40px rgba(232,41,58,0.5);
}
.reg-btn-submit:active { transform: translateY(0); }
.reg-social-proof {
  font-size: 0.76rem; color: var(--reg-gray);
  margin-top: 12px;
}

/* Success Box */
.reg-success-box {
  display: none; text-align: center; padding: 40px 20px;
}
.reg-success-box.show { display: block; }
.reg-success-icon { font-size: 3.5rem; margin-bottom: 16px; }
.reg-success-box h2 {
  font-size: clamp(1.2rem, 3vw, 1.6rem);
  font-weight: 800; color: var(--reg-teal);
  margin-bottom: 14px;
}
.reg-success-box p { font-size: 0.9rem; color: var(--reg-text); line-height: 1.8; }

/* ── WhatsApp CTA ────────────────────────────────────── */
.reg-wa-wrap {
  text-align: center; margin: 0 0 40px;
}
.reg-wa-wrap p {
  font-size: 0.85rem; color: var(--reg-gray); margin-bottom: 14px;
}
.reg-btn-wa {
  display: inline-flex; align-items: center; gap: 8px;
  background: #25D366; color: #fff;
  border-radius: 50px; padding: 13px 28px;
  font-family: 'Poppins', sans-serif;
  font-size: 0.9rem; font-weight: 700;
  text-decoration: none; letter-spacing: 0.5px;
  transition: transform 0.2s, box-shadow 0.2s;
  box-shadow: 0 4px 20px rgba(37,211,102,0.3);
}
.reg-btn-wa:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 30px rgba(37,211,102,0.5);
}

/* ── Confetti canvas ─────────────────────────────────── */
#reg-confetti-canvas {
  position: fixed; top: 0; left: 0;
  width: 100%; height: 100%;
  pointer-events: none; z-index: 9999;
}

/* ── Mobile / iPhone Responsive ─────────────────────── */
@media (max-width: 640px) {
  .reg-form-card {
    padding: 24px 16px;
    border-radius: 12px;
  }
  .reg-form-grid {
    grid-template-columns: 1fr;
    gap: 16px;
  }
  .reg-form-group.full {
    grid-column: 1;
  }
  .reg-form-group input,
  .reg-form-group select {
    font-size: 16px; /* Prevent iOS auto-zoom */
    padding: 14px 40px 14px 14px;
  }
  .reg-form-group input { padding: 14px; }
  .reg-btn-submit {
    font-size: 0.9rem;
    padding: 16px 20px;
    max-width: 100%;
    letter-spacing: 0.5px;
  }
  .reg-info-box { padding: 16px; }
  .reg-info-box ul li { font-size: 0.8rem; }
  .reg-hero { padding: 36px 16px 28px; }
  .reg-container { padding: 0 14px 40px; }
  .reg-sec-title { margin: 32px 0 16px; }
  .reg-wa-wrap { padding: 0 14px; }
  .reg-btn-wa { font-size: 0.82rem; padding: 12px 20px; width: 100%; justify-content: center; }
}
</style>


{{-- ── Hero Section ────────────────────────────────── --}}
<div class="reg-hero">
    <h1>Claim Your <span class="hl">Win Bollywood Tickets</span></h1>
    <p>Register today for a chance to experience live music magic in Houston, TX</p>
    <div class="reg-spots-pill">
        🔥 &nbsp;Celebrate Eid with Us — Only 100 Free Tickets Available!
    </div>
</div>

{{-- ── Main Content ────────────────────────────────── --}}
<div class="reg-container">

    {{-- Event Card --}}
    <div class="reg-sec-title">Featured Event</div>

    <div class="reg-events-grid">
        <div class="reg-event-card">
            <div class="poster-wrap">
                <img src="{{ asset('assets/images/events/newposters/sonu-nigam-houston.jpeg') }}"
                     alt="Sonu Nigam Houston"
                     onerror="this.style.background='linear-gradient(135deg,#111d45,#1a2660)';this.style.aspectRatio='16/9';">
            </div>
            <div class="reg-event-card-body">
                <h3>🎤 Sonu Nigam </h3>
                <p>📍 Houston, TX &nbsp;·&nbsp; Coming Soon</p>
                <span class="reg-free-tag">Win Tickets</span>
            </div>
        </div>
    </div>

    {{-- Info Box --}}
    <div class="reg-info-box">
        <h4>ℹ &nbsp;Important Registration Information</h4>
        <ul>
            <li>Please provide <strong>valid and genuine details</strong></li>
            <li>Ticket confirmations will be sent to your <strong>registered email</strong></li>
            <li><strong>One registration per person</strong> (email, name &amp; phone number)</li>
            <li>Duplicate entries will be <strong>automatically disqualified</strong></li>
        </ul>
    </div>

    {{-- Registration Form --}}
    <div class="reg-form-card" id="regFormCard">
        <h2>🎟 Register for <span class="hl">Free Tickets</span></h2>

        <form id="regForm" novalidate>
            @csrf
            <div class="reg-form-grid">

                <div class="reg-form-group">
                    <label for="reg_fullName">Full Name <span class="req">*</span></label>
                    <input type="text" id="reg_fullName" name="full_name"
                           placeholder="Your full name" required autocomplete="name">
                    <span class="reg-err" id="reg_nameErr">Please enter your full name</span>
                </div>

                <div class="reg-form-group">
                    <label for="reg_email">Email Address <span class="req">*</span></label>
                    <input type="email" id="reg_email" name="email"
                           placeholder="your@email.com" required autocomplete="email">
                    <span class="reg-err" id="reg_emailErr">Please enter a valid email address</span>
                </div>

                <div class="reg-form-group">
                    <label for="reg_phone">Phone / WhatsApp <span class="req">*</span></label>
                    <input type="tel" id="reg_phone" name="phone"
                           placeholder="+1 (000) 000-0000" required autocomplete="tel">
                    <span class="reg-err" id="reg_phoneErr">Please enter your phone number</span>
                </div>

                <div class="reg-form-group">
                    <label for="reg_city">Your City <span class="req">*</span></label>
                    <input type="text" id="reg_city" name="city"
                           placeholder="e.g. Houston, Dallas..." required autocomplete="address-level2">
                    <span class="reg-err" id="reg_cityErr">Please enter your city</span>
                </div>

                <div class="reg-form-group full">
                    <label for="reg_event">Select Event <span class="req">*</span></label>
                    <select id="reg_event" name="event" required>
                        <option value="">— Choose Your Event —</option>
                        <option value="sonu_nigam_houston">🎤 Sonu Nigam | Houston, TX</option>
                    </select>
                    <span class="reg-err" id="reg_eventErr">Please select an event</span>
                </div>

                <div class="reg-form-group full">
                    <label for="reg_source">How did you hear about us?</label>
                    <select id="reg_source" name="source">
                        <option value="">— Select Source —</option>
                        <option value="facebook">📘 Facebook</option>
                        <option value="instagram">📸 Instagram</option>
                        <option value="friend">👥 Friend / Family</option>
                        <option value="other">🔍 Other</option>
                    </select>
                </div>

                <div class="reg-form-group full">
                    <div class="reg-check-wrap">
                        <input type="checkbox" id="reg_terms" name="terms" required>
                        <label for="reg_terms">
                            I confirm that the details provided are <strong>genuine and accurate</strong>.
                            I understand that <strong>duplicate entries will be disqualified</strong> and
                            <strong>one registration per person</strong> is allowed.
                            Free ticket availability is subject to the first 250 registrations only.
                        </label>
                    </div>
                    <span class="reg-err" id="reg_termsErr">Please accept the terms to continue</span>
                </div>

                <div class="reg-form-group full reg-submit-area">
                    <p class="reg-urgency">⚡ Limited spots remaining — Don't miss out!</p>
                    <button type="submit" class="reg-btn-submit">
                        🎟 &nbsp;Register My Free Ticket
                    </button>
                    <p class="reg-social-proof">Join thousands of Bollywood fans across Houston &amp; Dallas!</p>
                </div>

            </div>
        </form>

        {{-- Success Message --}}
        <div class="reg-success-box" id="regSuccessBox">
            <div class="reg-success-icon">🎉</div>
            <h2>Registration Successful!</h2>
            <p>
                Thank you! Your registration has been received.<br>
                Registration confirmation has been sent to the provided email🎤✨
            </p>
        </div>
    </div>

    {{-- WhatsApp CTA --}}
    <div class="reg-wa-wrap">
        <p>Have questions? Chat with us directly</p>
        <a href="https://wa.me/18556725849" class="reg-btn-wa" target="_blank" rel="noopener noreferrer">
            💬 &nbsp;WhatsApp Us
        </a>
    </div>

</div>{{-- end reg-container --}}

{{-- Confetti Canvas --}}
<canvas id="reg-confetti-canvas"></canvas>

<script>
/* ── Field validation ───────────────────────────────── */
function regShowErr(fieldId, errId, show) {
    var f = document.getElementById(fieldId);
    var e = document.getElementById(errId);
    if (!f || !e) return;
    if (show) {
        f.classList.add('err-field');
        e.classList.add('show');
    } else {
        f.classList.remove('err-field');
        e.classList.remove('show');
    }
}

function regValidateField(id) {
    var v = document.getElementById(id).value.trim();
    var map = {
        reg_fullName: 'reg_nameErr',
        reg_email:    'reg_emailErr',
        reg_phone:    'reg_phoneErr',
        reg_city:     'reg_cityErr',
        reg_event:    'reg_eventErr'
    };
    if (id === 'reg_email') {
        regShowErr(id, map[id], !v || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v));
    } else {
        regShowErr(id, map[id], !v);
    }
}

['reg_fullName','reg_email','reg_phone','reg_city','reg_event'].forEach(function(id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.addEventListener('input',  function() { regValidateField(id); });
    el.addEventListener('change', function() { regValidateField(id); });
});

/* ── Form submit ────────────────────────────────────── */
var regForm = document.getElementById('regForm');
if (regForm) {
    regForm.addEventListener('submit', function(e) {
        e.preventDefault();
        var valid = true;

        ['reg_fullName','reg_email','reg_phone','reg_city','reg_event'].forEach(function(id) {
            regValidateField(id);
            var v = document.getElementById(id).value.trim();
            if (!v) valid = false;
            if (id === 'reg_email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) valid = false;
        });

        var t = document.getElementById('reg_terms');
        if (!t.checked) { regShowErr('reg_terms', 'reg_termsErr', true); valid = false; }
        else              regShowErr('reg_terms', 'reg_termsErr', false);

        if (!valid) return;

        // Collect form data
        var formData = new FormData(regForm);

        // Disable submit button to prevent double-submit
        var btn = regForm.querySelector('.reg-btn-submit');
        btn.disabled = true;
        btn.textContent = '⏳  Submitting…';

        // POST to backend
        fetch('{{ route("register.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                // Show success box + confetti
                regForm.style.display = 'none';
                document.getElementById('regSuccessBox').classList.add('show');
                regLaunchConfetti();
                document.getElementById('regFormCard').scrollIntoView({ behavior: 'smooth' });
            } else {
                // Server returned an error (e.g. duplicate entry)
                btn.disabled = false;
                btn.innerHTML = '🎟 &nbsp;Register My Free Ticket';
                alert(data.message || 'Something went wrong. Please try again.');
            }
        })
        .catch(function(err) {
            // Network or unexpected error
            btn.disabled = false;
            btn.innerHTML = '🎟 &nbsp;Register My Free Ticket';
            alert('Network error. Please check your connection and try again.');
            console.error('Registration fetch error:', err);
        });

    });
}

/* ── Confetti ───────────────────────────────────────── */
function regLaunchConfetti() {
    var canvas = document.getElementById('reg-confetti-canvas');
    var ctx    = canvas.getContext('2d');
    canvas.width  = window.innerWidth;
    canvas.height = window.innerHeight;

    var cols    = ['#e8293a','#00c9a7','#ffffff','#f5c518','#7b2ff7','#346AB4'];
    var pieces  = [];
    var running = true;

    for (var i = 0; i < 160; i++) {
        pieces.push({
            x:  Math.random() * canvas.width,
            y:  Math.random() * canvas.height - canvas.height,
            w:  Math.random() * 10 + 5,
            h:  Math.random() * 5  + 3,
            r:  Math.random() * Math.PI * 2,
            dr: (Math.random() - 0.5) * 0.2,
            dy: Math.random() * 3 + 2,
            dx: (Math.random() - 0.5) * 2,
            c:  cols[Math.floor(Math.random() * cols.length)]
        });
    }

    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        pieces.forEach(function(p) {
            ctx.save();
            ctx.translate(p.x, p.y);
            ctx.rotate(p.r);
            ctx.fillStyle = p.c;
            ctx.fillRect(-p.w/2, -p.h/2, p.w, p.h);
            ctx.restore();
            p.y  += p.dy;
            p.x  += p.dx;
            p.r  += p.dr;
            if (p.y > canvas.height) { p.y = -20; p.x = Math.random() * canvas.width; }
        });
        if (running) requestAnimationFrame(draw);
    }

    draw();
    setTimeout(function() {
        running = false;
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }, 4000);
}
</script>
