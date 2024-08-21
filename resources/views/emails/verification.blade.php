<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <h1>Thank you for registering!</h1>
    <p>Click the link below to activate your account:</p>
    <a href="{{ $url }}">Activate Account</a>
    <p>If the link doesn't work, you can copy and paste this URL into your browser:</p>
    <p>{{ $url }}</p>
    <br>
    <p>Thank you!</p>
</body> 
</html>
