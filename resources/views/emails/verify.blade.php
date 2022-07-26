<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Verification</title>
</head>
<body>
    <h1>Welcome {{ $user->nama }} to our website!!</h1>
    <p>Click <a href="{{ route('email.verify', $token->token) }}">here</a> to verify your email!</p>
</body>
</html>
