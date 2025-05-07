<!DOCTYPE html>
<html>
<head>
    <title>Tenant Request Rejected</title>
</head>
<body>
    <p>Dear {{ $request->username }},</p>

    <p>We regret to inform you that your tenant request for the organization "{{ $request->organization }}" has been rejected.</p>

    <p>If you believe this was a mistake or have questions, feel free to contact our support team.</p>

    <p>Thank you for your interest.</p>

    <p>Best regards,<br>LostAndFound</p>
</body>
</html>
