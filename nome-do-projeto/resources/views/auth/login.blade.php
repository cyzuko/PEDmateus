<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

    <h2>Login</h2>

    <form action="{{ url('login') }}" method="POST">
        @csrf
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" required>
        </div>

        <div>
            <label for="password">Senha:</label>
            <input type="password" name="password" required>
        </div>

        <div>
            <label for="remember">
                <input type="checkbox" name="remember"> Lembrar-me
            </label>
        </div>

        <button type="submit">Login</button>
    </form>

    @if($errors->any())
        <div>
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

</body>
</html>
