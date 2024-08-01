<!DOCTYPE html>
<html>
<head>
    <title>Verification Email</title>
</head>
<body>
    <p>Please click the following link to verify your account:</p>
    <a href="{{ route('activate', ['activation_key' => $activationKey]) }}">Verify Account</a>
</body>
</html>
