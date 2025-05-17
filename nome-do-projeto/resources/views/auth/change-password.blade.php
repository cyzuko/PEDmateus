@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">  <!-- largura menor pra formulário -->
            <div class="card">
                <div class="card-header text-center">
                    <h4>Alterar Senha</h4>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success text-center">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger text-center">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Senha Atual</label>
                            <input id="current_password" type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required autofocus>
                            @error('current_password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nova Senha</label>
                            <input id="new_password" type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" required>
                            @error('new_password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirmar Nova Senha</label>
                            <input id="new_password_confirmation" type="password" name="new_password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Alterar Senha</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
