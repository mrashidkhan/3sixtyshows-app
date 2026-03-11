<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .email-wrapper { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .email-header { background: #c0392b; color: #ffffff; padding: 30px; text-align: center; }
        .email-header h1 { margin: 0; font-size: 22px; }
        .email-body { padding: 30px; color: #333333; }
        .detail-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .detail-table th { background: #f0f0f0; text-align: left; padding: 10px 14px; font-size: 13px; color: #666; text-transform: uppercase; }
        .detail-table td { padding: 12px 14px; border-bottom: 1px solid #eeeeee; font-size: 15px; }
        .email-footer { background: #f9f9f9; padding: 20px 30px; text-align: center; font-size: 13px; color: #999; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1>📩 New Contact Form Submission</h1>
        </div>
        <div class="email-body">
            <p>You have received a new message from your website contact form:</p>
            <table class="detail-table">
                <tr><th>Field</th><th>Details</th></tr>
                <tr><td><strong>Name</strong></td><td>{{ $name }}</td></tr>
                <tr><td><strong>Email</strong></td><td><a href="mailto:{{ $email }}">{{ $email }}</a></td></tr>
                <tr><td><strong>Phone</strong></td><td>{{ $phone }}</td></tr>
                <tr><td><strong>Subject</strong></td><td>{{ $subject }}</td></tr>
                <tr><td><strong>Message</strong></td><td>{{ $user_message }}</td></tr>
            </table>
            <p>You can reply directly to this email to respond to {{ $name }}.</p>
        </div>
        <div class="email-footer">Automated notification from your website contact form.</div>
    </div>
</body>
</html>
