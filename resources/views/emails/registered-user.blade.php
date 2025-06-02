<!DOCTYPE html>
<html>
<head>
    <title>Welcome to {{ config('app.name') }}</title>
</head>
<body>
<h1>Welcome, {{ $user->name }}!</h1>
<p>Thank you for registering on {{ config('app.name') }}. We're excited to have you on board!</p>
<p>This is your voucher code {{ $voucher->code }}.</p>
</body>
</html>
