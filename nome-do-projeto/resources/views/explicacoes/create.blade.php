@extends('layouts.app')
@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <!-- Header Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-1 fw-bold text-primary">
                                <i class="fas fa-plus-circle me-2"></i>Nova Explicação
                            </h2>
                            <p class="text-muted mb-0">Adicione uma nova explicação ao sistema</p>
                        </div>
                        <div class="d-none d-md-block">
                            <i class="fas fa-chalkboard-teacher text-primary" style="font-size: 3rem; opacity: 0.1;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('explicacoes.store') }}">
                @csrf
                
                <!-- Dados da Explicação -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-0 py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-info-circle text-primary me-2"></i>Dados da Explicação
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Disciplina -->
                            <div class="col-md-6">
                                <label for="disciplina" class="form-label fw-semibold">
                                    <i class="fas fa-book me-1 text-muted"></i>Disciplina *
                                </label>
                                <input id="disciplina" type="text" 
                                    class="form-control form-control-lg @error('disciplina') is-invalid @enderror" 
                                    name="disciplina" value="{{ old('disciplina') }}" 
                                    placeholder="Ex: Matemática, Português..." required>
                                @error('disciplina')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Data -->
                            <div class="col-md-6">
                                <label for="data_explicacao" class="form-label fw-semibold">
                                    <i class="fas fa-calendar me-1 text-muted"></i>Data da Explicação *
                                </label>
                                <input id="data_explicacao" type="date" 
                                    class="form-control form-control-lg @error('data_explicacao') is-invalid @enderror" 
                                    name="data_explicacao" value="{{ old('data_explicacao') }}" required>
                                @error('data_explicacao')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Hora Início -->
                            <div class="col-md-6">
                                <label for="hora_inicio" class="form-label fw-semibold">
                                    <i class="fas fa-clock me-1 text-muted"></i>Hora Início *
                                </label>
                                <input id="hora_inicio" type="time" 
                                    class="form-control form-control-lg @error('hora_inicio') is-invalid @enderror" 
                                    name="hora_inicio" value="{{ old('hora_inicio') }}" required>
                                @error('hora_inicio')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Hora Fim -->
                            <div class="col-md-6">
                                <label for="hora_fim" class="form-label fw-semibold">
                                    <i class="fas fa-clock me-1 text-muted"></i>Hora Fim *
                                </label>
                                <input id="hora_fim" type="time" 
                                    class="form-control form-control-lg @error('hora_fim') is-invalid @enderror" 
                                    name="hora_fim" value="{{ old('hora_fim') }}" required>
                                @error('hora_fim')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dados do Aluno -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-0 py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-user-graduate text-primary me-2"></i>Dados do Aluno
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Nome do Aluno -->
                            <div class="col-md-6">
                                <label for="nome_aluno" class="form-label fw-semibold">
                                    <i class="fas fa-user me-1 text-muted"></i>Nome do Aluno *
                                </label>
                                <input id="nome_aluno" type="text" 
                                    class="form-control form-control-lg @error('nome_aluno') is-invalid @enderror" 
                                    name="nome_aluno" value="{{ old('nome_aluno') }}" 
                                    placeholder="Nome completo do aluno..." required>
                                @error('nome_aluno')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Contacto -->
                            <div class="col-md-6">
                                <label for="contacto_aluno" class="form-label fw-semibold">
                                    <i class="fas fa-phone me-1 text-muted"></i>Contacto do Aluno *
                                </label>
                                <input id="contacto_aluno" type="text" 
                                    class="form-control form-control-lg @error('contacto_aluno') is-invalid @enderror" 
                                    name="contacto_aluno" value="{{ old('contacto_aluno') }}" 
                                    placeholder="Telefone ou email do aluno..." required>
                                @error('contacto_aluno')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Local e Preço -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-0 py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>Local e Preço
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Local -->
                            <div class="col-md-6">
                                <label for="local" class="form-label fw-semibold">
                                    <i class="fas fa-map-marker-alt me-1 text-muted"></i>Local *
                                </label>
                                <input id="local" type="text" 
                                    class="form-control form-control-lg @error('local') is-invalid @enderror" 
                                    name="local" value="{{ old('local') }}" 
                                    placeholder="Ex: Online, Biblioteca, Casa do aluno..." required>
                                @error('local')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Preço -->
                            <div class="col-md-6">
                                <label for="preco" class="form-label fw-semibold">
                                    <i class="fas fa-euro-sign me-1 text-muted"></i>Preço *
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light">€</span>
                                    <input id="preco" type="number" step="0.01" min="0"
                                        class="form-control @error('preco') is-invalid @enderror" 
                                        name="preco" value="{{ old('preco') }}" 
                                        placeholder="0,00" required>
                                </div>
                                @error('preco')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observações -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-0 py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-sticky-note text-primary me-2"></i>Observações
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <label for="observacoes" class="form-label fw-semibold">
                            <i class="fas fa-comment me-1 text-muted"></i>Informações Adicionais
                        </label>
                        <textarea id="observacoes" name="observacoes" 
                            class="form-control @error('observacoes') is-invalid @enderror" 
                            rows="4" placeholder="Informações adicionais sobre a explicação...">{{ old('observacoes') }}</textarea>
                        @error('observacoes')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Notificações -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-0 py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-bell text-primary me-2"></i>Notificações
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check form-check-lg p-3 bg-light rounded-3">
                                    <input class="form-check-input" type="checkbox" name="enviar_email_aluno" 
                                        id="enviar_email_aluno" value="1" {{ old('enviar_email_aluno') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="enviar_email_aluno">
                                        <i class="fas fa-envelope text-primary me-2"></i>
                                        Notificar Aluno por Email
                                    </label>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Enviar email de confirmação para o aluno
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-check-lg p-3 bg-light rounded-3">
                                    <input class="form-check-input" type="checkbox" name="enviar_email_admin" 
                                        id="enviar_email_admin" value="1" {{ old('enviar_email_admin') ? 'checked' : 'checked' }}>
                                    <label class="form-check-label fw-semibold" for="enviar_email_admin">
                                        <i class="fas fa-user-shield text-success me-2"></i>
                                        Notificar Administrador
                                    </label>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Enviar para aprovação automática
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3 justify-content-end flex-wrap">
                            <a href="{{ route('explicacoes.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-save me-2"></i>Criar Explicação
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Estilos customizados seguindo o padrão das faturas */
.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

/* Espaçamento consistente entre ícones e texto */
.fas, .far, .fab {
    margin-right: 0.5rem !important;
}

.btn i {
    margin-right: 0.5rem !important;
}

.card-header i {
    margin-right: 0.75rem !important;
}

/* Espaçamento para grupos de botões */
.d-flex.gap-3 .btn {
    margin: 0.25rem;
}

/* Estilo melhorado para os checkboxes */
.form-check-lg .form-check-input {
    width: 1.5em;
    height: 1.5em;
    margin-top: 0.125em;
}

.form-check-lg .form-check-label {
    font-size: 1.1rem;
    padding-left: 0.5rem;
}

/* Responsividade para dispositivos móveis */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .card-body {
        padding: 20px !important;
    }
    
    .btn-lg {
        padding: 12px 20px;
        font-size: 1rem;
    }
    
    .form-control-lg {
        padding: 12px 16px;
        font-size: 1rem;
    }
}

/* Animações para aparecer */
.card {
    animation: fadeInUp 0.5s ease-out forwards;
    opacity: 0;
    transform: translateY(20px);
}

.card:nth-child(1) { animation-delay: 0.1s; }
.card:nth-child(2) { animation-delay: 0.2s; }
.card:nth-child(3) { animation-delay: 0.3s; }
.card:nth-child(4) { animation-delay: 0.4s; }
.card:nth-child(5) { animation-delay: 0.5s; }
.card:nth-child(6) { animation-delay: 0.6s; }

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Melhoria nos inputs */
.input-group-text {
    background: #f8f9fa !important;
    border-color: #dee2e6;
    color: #6c757d;
    font-weight: 600;
}

.form-control:focus + .input-group-text {
    border-color: #0d6efd;
}
</style>
@endsection