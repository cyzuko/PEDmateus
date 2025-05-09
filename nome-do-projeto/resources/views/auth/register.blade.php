@extends('adminlte::auth.auth-page')

@section('auth_type', 'register')
@section('title', 'Registo')
@section('auth_header', 'Crie a sua conta')

@section('auth_body')
<form method="POST" action="{{ route('register') }}">
    @csrf

    <x-adminlte-input name="name" label="Nome" type="text"
                      placeholder="Seu nome completo" value="{{ old('name') }}"
                      error-key="name" required autofocus />

    <x-adminlte-input name="email" label="Email" type="email"
                      placeholder="Digite seu email" value="{{ old('email') }}"
                      error-key="email" required />

    <x-adminlte-input name="password" label="Senha" type="password"
                      placeholder="Crie uma senha" error-key="password" required />

    <x-adminlte-input name="password_confirmation" label="Confirmar Senha"
                      type="password" placeholder="Confirme a senha" required />

    <x-adminlte-button label="Registrar" theme="primary" class="btn-block mt-3" type="submit"/>
</form>
@endsection

@section('auth_footer')
    <p class="my-0">
        <a href="{{ route('login') }}">Já tem conta? Faça login</a>
    </p>
@endsection
