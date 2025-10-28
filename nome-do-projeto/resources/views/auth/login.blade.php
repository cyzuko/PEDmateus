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
    /* Imagem de fundo */
    .login-page, .register-page {
        background-image: url('{{ asset('images/fotofundo.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }
    
    /* Overlay escuro para melhor legibilidade */
    .login-page::after, .register-page::after {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 0;
    }
    
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
        color: #fff;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        margin-top: 10rem;
        margin-bottom: 2rem;
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1000;
    }
    
    /* Adiciona espaço no topo para o título */
    .login-box, .register-box {
        margin-top: 6rem !important;
        position: relative;
        z-index: 1;
    }
    
    /* Card com fundo semi-transparente */
    .card {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
    }
</style>
@endpush