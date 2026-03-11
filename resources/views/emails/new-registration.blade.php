<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Registration From Opaltickets website</title>
<style>
  body { margin: 0; padding: 0; background: #f4f6f9; font-family: 'Segoe UI', Arial, sans-serif; }
  .wrapper { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
  .header { background: linear-gradient(135deg, #346AB4, #4A7AB5); padding: 28px 32px; text-align: center; }
  .header h1 { color: #fff; margin: 0; font-size: 1.4rem; font-weight: 700; letter-spacing: 0.5px; }
  .header p  { color: rgba(255,255,255,0.85); margin: 6px 0 0; font-size: 0.88rem; }
  .body { padding: 32px; }
  .body h2 { margin: 0 0 20px; font-size: 1.05rem; color: #1a2660; font-weight: 700; }
  table { width: 100%; border-collapse: collapse; }
  td { padding: 11px 14px; font-size: 0.88rem; color: #333; border-bottom: 1px solid #eef0f4; }
  td:first-child { font-weight: 600; color: #346AB4; width: 38%; white-space: nowrap; }
  tr:last-child td { border-bottom: none; }
  .event-badge {
    display: inline-block;
    background: linear-gradient(135deg, #346AB4, #4A7AB5);
    color: #fff; border-radius: 20px;
    padding: 3px 14px; font-size: 0.8rem; font-weight: 700;
  }
  .meta { background: #f8f9fc; border-radius: 8px; padding: 14px 16px; margin-top: 24px; font-size: 0.8rem; color: #6b7280; }
  .meta strong { color: #374151; }
  .footer { background: #f4f6f9; padding: 18px 32px; text-align: center; font-size: 0.76rem; color: #9ca3af; border-top: 1px solid #e5e7eb; }
  .footer a { color: #346AB4; text-decoration: none; }
</style>
</head>
<body>
<div class="wrapper">

  <!-- Header -->
  <div class="header">
    <h1>🎟 New Free Ticket Registration</h1>
    <p>3Sixtyshows — Sonu Nigam Houston</p>
  </div>

  <!-- Body -->
  <div class="body">
    <h2>Registration Details</h2>
    <table>
      <tr>
        <td>Full Name</td>
        <td>{{ $registration->full_name }}</td>
      </tr>
      <tr>
        <td>Email</td>
        <td><a href="mailto:{{ $registration->email }}" style="color:#346AB4;">{{ $registration->email }}</a></td>
      </tr>
      <tr>
        <td>Phone / WhatsApp</td>
        <td>{{ $registration->phone }}</td>
      </tr>
      <tr>
        <td>City</td>
        <td>{{ $registration->city }}</td>
      </tr>
      <tr>
        <td>Event</td>
        <td><span class="event-badge">{{ str_replace('_', ' ', ucwords($registration->event, '_')) }}</span></td>
      </tr>
      <tr>
        <td>Source</td>
        <td>{{ $registration->source ?: '—' }}</td>
      </tr>
      <tr>
        <td>Status</td>
        <td>{{ ucfirst($registration->status) }}</td>
      </tr>
    </table>

    <!-- Meta info -->
    <div class="meta">
      <strong>Registered At:</strong> {{ $registration->created_at->format('D, d M Y — h:i A') }} UTC<br>
      <strong>Registration #:</strong> {{ $registration->id }}
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    This is an automated notification from <a href="https://opaltickets.com">3Sixtyshows</a>.<br>
    Please do not reply
