<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .email-wrapper { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .email-header { background: #1a1a2e; color: #ffffff; padding: 30px; text-align: center; }
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
            <h1>Thank You, {{ $name }}!</h1>
        </div>
        <div class="email-body">
            <p>We have received your message and will get back to you as soon as possible.</p>
            <p>Here is a copy of your submitted details:</p>
            <table class="detail-table">
                <tr><th>Field</th><th>Details</th></tr>
                <tr><td><strong>Name</strong></td><td>{{ $name }}</td></tr>
                <tr><td><strong>Email</strong></td><td>{{ $email }}</td></tr>
                <tr><td><strong>Phone</strong></td><td>{{ $phone }}</td></tr>
                <tr><td><strong>Subject</strong></td><td>{{ $subject }}</td></tr>
                <tr><td><strong>Message</strong></td><td>{{ $user_message }}</td></tr>
            </table>
            <p>If you did not submit this form, please ignore this email.</p>
            <p>Best regards,<br><strong>3Sixtyshows Team</strong></p>
        </div>
        <div class="email-footer">&copy; {{ date('Y') }} 3Sixty Shows. All rights reserved.</div>
    </div>
</body>
</html>
