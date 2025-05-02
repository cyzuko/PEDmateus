@extends('layouts.app-layout')

@section('content')
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4 text-center">Login</h2>
        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                <input type="password" id="password" name="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
            </div>
            <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-md">Entrar</button>
        </form>
    </div>
@endsection
