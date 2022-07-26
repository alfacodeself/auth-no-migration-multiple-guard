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
    <form action="{{ route('register') }}" method="post">
        @csrf
        @method('POST')
        <table>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>
                    <input type="text" name="nama" value="{{ old('nama') }}" required>
                </td>
                @error('nama')
                    <td>
                        <strong>{{ $message }}</strong>
                    </td>
                @enderror
            </tr>
            <tr>
                <td>Email</td>
                <td>:</td>
                <td>
                    <input type="text" name="email" value="{{ old('email') }}" required>
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
                    <input type="password" name="password" value="{{ old('password') }}" required>
                </td>
                @error('password')
                    <td>
                        <strong>{{ $message }}</strong>
                    </td>
                @enderror
            </tr>
            <tr>
                <td colspan="3">
                    <input type="radio" name="tipe" id="tipe" value="lapak">Pelapak
                    <input type="radio" name="tipe" id="tipe" value="user">Pelanggan
                </td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td>
                    <button type="submit">Register</button>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
