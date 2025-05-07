<!DOCTYPE html>
<html>
<head>
    <title>Your Tenant Account Credentials</title>
</head>
<body>
    <h1>Hello {{ $user->name }},</h1>
    <p>Congratulations! Your tenant account has been approved. Here are your login credentials:</p>
    <p><strong>Username:</strong> {{ $user->email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>
    <p>We recommend you change your password after logging in for the first time.</p>
    <p>Thank you!</p>
</body>
</html>
