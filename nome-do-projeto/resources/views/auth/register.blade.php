<form method="POST" action="{{ url('register') }}">
    @csrf
    <div>
        <label for="name">Nome:</label>
        <input type="text" name="name" required>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" name="email" required>
    </div>
    <div>
        <label for="password">Senha:</label>
        <input type="password" name="password" required>
    </div>
    <div>
        <label for="password_confirmation">Confirmar Senha:</label>
        <input type="password" name="password_confirmation" required>
    </div>
    <div>
        <button type="submit">Registrar</button>
    </div>
</form>
