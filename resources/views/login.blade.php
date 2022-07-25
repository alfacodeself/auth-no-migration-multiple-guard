<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log In</title>
</head>
<body>
    @if (session('error'))
        <h1>{{ session('error') }}</h1>
    @endif
    <form action="{{ route('login') }}" method="post">
        @csrf
        @method('POST')
        <table>
            <tr>
                <td>Username</td>
                <td>:</td>
                <td>
                    <input type="text" name="email" required>
                </td>
                @error('email')
                    <td>
                        <strong>{{ $message }}</strong>
                    </td>
                @enderror
            </tr>
            <tr>
                <td>Password</td>
                <td>:</td>
                <td>
                    <input type="password" name="password" required>
                </td>
                @error('password')
                    <td>
                        <strong>{{ $message }}</strong>
                    </td>
                @enderror
            </tr>
            <tr>
                <td colspan="2"></td>
                <td>
                    <button type="submit">Log In</button>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
