@extends('adminlte::auth.auth-page')

@section('auth_type', 'login')
@section('title', 'Login')
@section('auth_header', 'Bem-vindo! Faça login')

@section('auth_body')
<form method="POST" action="{{ route('login') }}">
    @csrf
    
    <x-adminlte-input name="email" label="Email" type="email"
                      placeholder="Digite seu email" value="{{ old('email') }}"
                      error-key="email" required autofocus />
    
    <x-adminlte-input name="password" label="Senha" type="password"
                      placeholder="Digite sua senha" error-key="password" required />
    
    <x-adminlte-button label="Entrar" theme="primary" class="btn-block mt-3" type="submit"/>
</form>
@endsection

@section('auth_footer')
    <p class="my-0">
        <a href="{{ route('register') }}">Ainda não tem conta? Registe-se</a>
    </p>
@endsection

@push('css')
<style>
    /* Esconde o logo e título original */
    .login-logo, .register-logo {
        display: none !important;
    }
    
    /* Adiciona Eureka no topo da página */
    .login-page::before {
        content: "Eureka";
        display: block;
        text-align: center;
        font-size: 3rem;
        font-weight: bold;
        color: #007bff;
        margin-top: 10rem;
        margin-bottom: 2rem;
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1000;
    }
    
    /* Adiciona espaço no topo para o título */
    .login-box {
        margin-top: 6rem !important;
    }
</style>
@endpush