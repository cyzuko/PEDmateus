@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-user-edit"></i> Editar Utilizador
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">
                                <i class="fas fa-user"></i> Nome Completo *
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required 
                                   autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i> Email *
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">
                                <i class="fas fa-lock"></i> Nova Senha
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password">
                            <small class="form-text text-muted">
                                Deixe em branco para manter a senha atual. Mínimo de 8 caracteres se for alterar.
                            </small>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">
                                <i class="fas fa-lock"></i> Confirmar Nova Senha
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation">
                        </div>

                        <div class="form-group">
                            <label for="role">
                                <i class="fas fa-user-tag"></i> Tipo de Utilizador *
                            </label>
                            <select class="form-control @error('role') is-invalid @enderror" 
                                    id="role" 
                                    name="role" 
                                    required>
                                <option value="">Selecione...</option>
                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>
                                    Utilizador Normal
                                </option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                    Administrador
                                </option>
                            </select>
                            @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Administradores têm acesso total ao sistema
                            </small>
                        </div>

                        @if($user->id === auth()->id())
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Atenção:</strong> Está a editar a sua própria conta!
                            </div>
                        @endif

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-warning btn-block">
                                <i class="fas fa-save"></i> Guardar Alterações
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-block mt-2">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card de informações adicionais -->
            <div class="card shadow mt-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-info-circle"></i> Informações Adicionais
                    </h5>
                    <ul class="list-unstyled mb-0">
                        <li><strong>ID:</strong> {{ $user->id }}</li>
                        <li><strong>Registado em:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</li>
                        <li><strong>Última atualização:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.form-control:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255,193,7,.25);
}

.btn-block + .btn-block {
    margin-top: 0.5rem;
}
</style>
@endsection