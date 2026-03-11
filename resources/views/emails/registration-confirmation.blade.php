<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registration Confirmed — 3Sixtyshows</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { background: #f0f2f7; font-family: 'Segoe UI', Arial, sans-serif; -webkit-font-smoothing: antialiased; }

  .wrapper { max-width: 600px; margin: 30px auto 50px; border-radius: 14px; overflow: hidden; box-shadow: 0 8px 40px rgba(0,0,0,0.12); }

  /* ── Header ── */
  .header {
    background: linear-gradient(135deg, #0d1535 0%, #1a2660 60%, #346AB4 100%);
    padding: 36px 32px 28px;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  .header::before {
    content: '';
    position: absolute; top: -60px; left: 50%;
    transform: translateX(-50%);
    width: 300px; height: 200px;
    background: radial-gradient(ellipse, rgba(0,201,167,0.12) 0%, transparent 70%);
  }
  .header .emoji-icon { font-size: 3rem; display: block; margin-bottom: 12px; position: relative; }
  .header h1 {
    color: #fff; font-size: 1.4rem; font-weight: 800;
    letter-spacing: 0.5px; position: relative;
    line-height: 1.3;
  }
  .header h1 span { color: #00c9a7; }
  .header p { color: rgba(255,255,255,0.75); font-size: 0.85rem; margin-top: 8px; position: relative; }

  /* ── Body ── */
  .body { background: #ffffff; padding: 36px 32px; }

  .greeting { font-size: 1rem; color: #1a2660; font-weight: 700; margin-bottom: 14px; }
  .intro { font-size: 0.88rem; color: #4b5563; line-height: 1.7; margin-bottom: 28px; }

  /* ── Booking summary card ── */
  .summary-card {
    background: #f8f9fc;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 28px;
  }
  .summary-header {
    background: linear-gradient(135deg, #346AB4, #4A7AB5);
    padding: 12px 20px;
    font-size: 0.8rem; font-weight: 700;
    color: #fff; letter-spacing: 1.5px;
    text-transform: uppercase;
  }
  .summary-body { padding: 0; }
  table { width: 100%; border-collapse: collapse; }
  td {
    padding: 13px 20px;
    font-size: 0.86rem;
    color: #374151;
    border-bottom: 1px solid #f0f2f7;
    vertical-align: middle;
  }
  td:first-child {
    font-weight: 600; color: #346AB4;
    width: 36%; white-space: nowrap;
  }
  tr:last-child td { border-bottom: none; }

  .event-badge {
    display: inline-block;
    background: linear-gradient(135deg, #346AB4, #4A7AB5);
    color: #fff; border-radius: 20px;
    padding: 4px 14px; font-size: 0.78rem; font-weight: 700;
    letter-spacing: 0.5px;
  }
  .status-badge {
    display: inline-block;
    background: #fef3c7; color: #92400e;
    border-radius: 20px; padding: 3px 12px;
    font-size: 0.75rem; font-weight: 700;
    letter-spacing: 0.5px; text-transform: uppercase;
  }

  /* ── What happens next ── */
  .next-steps { margin-bottom: 28px; }
  .next-steps h3 { font-size: 0.88rem; font-weight: 700; color: #1a2660; margin-bottom: 14px; }
  .step {
    display: flex; gap: 14px; align-items: flex-start;
    margin-bottom: 12px;
  }
  .step-num {
    min-width: 28px; height: 28px;
    background: linear-gradient(135deg, #346AB4, #4A7AB5);
    color: #fff; border-radius: 50%;
    font-size: 0.75rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-top: 1px;
  }
  .step-text { font-size: 0.84rem; color: #4b5563; line-height: 1.6; }
  .step-text strong { color: #1a2660; }

  /* ── Warning box ── */
  .warning-box {
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 10px;
    padding: 14px 18px;
    margin-bottom: 28px;
    font-size: 0.82rem;
    color: #78350f;
    line-height: 1.6;
  }
  .warning-box strong { color: #92400e; }

  /* ── CTA Button ── */
  .cta-wrap { text-align: center; margin-bottom: 28px; }
  .cta-btn {
    display: inline-block;
    background: linear-gradient(135deg, #346AB4, #4A7AB5);
    color: #fff !important;
    text-decoration: none;
    padding: 14px 32px;
    border-radius: 50px;
    font-size: 0.9rem; font-weight: 700;
    letter-spacing: 0.5px;
  }

  /* ── WhatsApp ── */
  .wa-strip {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    padding: 14px 18px;
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 28px;
  }
  .wa-icon { font-size: 1.6rem; flex-shrink: 0; }
  .wa-text { font-size: 0.82rem; color: #065f46; line-height: 1.5; }
  .wa-text a { color: #059669; font-weight: 700; text-decoration: none; }

  /* ── Footer ── */
  .footer {
    background: #f8f9fc;
    border-top: 1px solid #e5e7eb;
    padding: 20px 32px;
    text-align: center;
  }
  .footer p { font-size: 0.75rem; color: #9ca3af; line-height: 1.7; }
  .footer a { color: #346AB4; text-decoration: none; }
  .footer .brand { font-weight: 700; color: #346AB4; font-size: 0.82rem; margin-bottom: 6px; display: block; }

  @media (max-width: 600px) {
    .wrapper { margin: 0; border-radius: 0; }
    .header, .body, .footer { padding-left: 20px; padding-right: 20px; }
    td { padding: 11px 14px; }
  }
</style>
</head>
<body>
<div class="wrapper">

  {{-- ── Header ── --}}
  <div class="header">
    <span class="emoji-icon">🎟</span>
    <h1>You're <span>Registered!</span></h1>
    <p>Your free ticket request has been received successfully</p>
  </div>

  {{-- ── Body ── --}}
  <div class="body">

    <p class="greeting">Hi {{ $registration->full_name }},</p>
    <p class="intro">
      Thank you for registering for <strong>Sonu Nigam Houston</strong> through 3Sixtyshows!
      We've received your registration. We will select winners from registered users. Winners will be announced on <strong>March 31, 2026 </strong>.
    </p>

    {{-- Summary Card --}}
    <div class="summary-card">
      <div class="summary-header">📋 &nbsp;Your Registration Summary</div>
      <div class="summary-body">
        <table>
          <tr>
            <td>Name</td>
            <td>{{ $registration->full_name }}</td>
          </tr>
          <tr>
            <td>Email</td>
            <td>{{ $registration->email }}</td>
          </tr>
          <tr>
            <td>Phone</td>
            <td>{{ $registration->phone }}</td>
          </tr>
          <tr>
            <td>City</td>
            <td>{{ $registration->city }}</td>
          </tr>
          <tr>
            <td>Event</td>
            <td>
              <span class="event-badge">
                🎤 Sonu Nigam — Houston, TX
              </span>
            </td>
          </tr>
          <tr>
            <td>Status</td>
            <td><span class="status-badge">⏳ Pending Review</span></td>
          </tr>
          <tr>
            <td>Registered At</td>
            <td>{{ $registration->created_at->format('D, d M Y — h:i A') }} UTC</td>
          </tr>
        </table>
      </div>
    </div>

    {{-- What Happens Next --}}
    <div class="next-steps">
      <h3>📌 What Happens Next?</h3>
      <div class="step">
        <div class="step-num">1</div>
        <div class="step-text">We will select winners from registered users. Winners will be announced on <strong>March 31, 2026 </strong>.</div>
      </div>
      <div class="step">
        <div class="step-num">2</div>
        <div class="step-text">If approved, your <strong>free ticket confirmation</strong> will be sent to <strong>{{ $registration->email }}</strong>.</div>
      </div>
      <div class="step">
        <div class="step-num">3</div>
        <div class="step-text">Present your <strong>confirmation email</strong> at the event entrance. No print required — digital is accepted.</div>
      </div>
    </div>

    {{-- Warning --}}
    <div class="warning-box">
      ⚠️ <strong>Important:</strong> Free tickets are limited to the first 100 registrations only.
      Duplicate entries are automatically disqualified. One registration is allowed per person.
      Keep this email for your records.
    </div>

    {{-- CTA --}}
    <div class="cta-wrap">
      <a href="https://opaltickets.com/events" class="cta-btn">🎵 &nbsp;Browse More Events</a>
    </div>

    {{-- WhatsApp --}}
    <div class="wa-strip">
      <div class="wa-icon">💬</div>
      <div class="wa-text">
        Have questions about your registration?<br>
        <a href="https://wa.me/18556725849">Chat with us on WhatsApp</a> — we typically reply within a few hours.
      </div>
    </div>

  </div>

  {{-- ── Footer ── --}}
  <div class="footer">
    <span class="brand">3Sixtyshows</span>
    <p>
      This is an automated confirmation from <a href="https://opaltickets.com">3Sixtyshows</a>.<br>
      If you did not register, please <a href="mailto:info@opaltickets.com">contact us immediately</a>.<br><br>
      © {{ date('Y') }} 3Sixtyshows. All rights reserved.
    </p>
  </div>

</div>
</body>
</html>
