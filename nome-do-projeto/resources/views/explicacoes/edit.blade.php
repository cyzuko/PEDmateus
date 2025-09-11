@extends('layouts.app')

@section('title', 'Editar Explicação')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            <i class="fas fa-edit me-2"></i>
                            Editar Explicação
                        </h3>
                        <div class="btn-group">
                            <a href="{{ route('explicacoes.show', $explicacao->id) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-eye"></i> Ver Detalhes
                            </a>
                            <a href="{{ route('explicacoes.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Erro ao validar os dados:</strong>
                            </div>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-times-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Status da Aprovação -->
                    @if($explicacao->aprovacao_admin === 'rejeitada')
                        <div class="alert alert-warning">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                                <h5 class="alert-heading mb-0">Explicação Rejeitada</h5>
                            </div>
                            <p class="mb-2">Esta explicação foi rejeitada pelo administrador.</p>
                            @if($explicacao->motivo_rejeicao)
                                <hr>
                                <div class="bg-light p-3 rounded">
                                    <strong>Motivo da Rejeição:</strong>
                                    <p class="mb-0 mt-1">{{ $explicacao->motivo_rejeicao }}</p>
                                </div>
                            @endif
                            <hr>
                            <p class="mb-0">
                                <i class="fas fa-lightbulb me-1 text-info"></i>
                                <small>Corrija os pontos mencionados e resubmeta a explicação para nova aprovação.</small>
                            </p>
                        </div>
                    @elseif($explicacao->aprovacao_admin === 'pendente' || !isset($explicacao->aprovacao_admin))
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock me-2"></i>
                                <div>
                                    <h6 class="mb-1">Aguardando Aprovação</h6>
                                    <p class="mb-0">Esta explicação está pendente de aprovação pelo administrador.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('explicacoes.update', $explicacao->id) }}" id="formEdicaoExplicacao" novalidate>
                        @csrf
                        @method('PUT')

                        <!-- Informações Básicas -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="disciplina" class="form-label">
                                    <i class="fas fa-book text-primary me-1"></i>
                                    Disciplina <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('disciplina') is-invalid @enderror" 
                                       id="disciplina" 
                                       name="disciplina" 
                                       value="{{ old('disciplina', $explicacao->disciplina) }}" 
                                       required 
                                       maxlength="255"
                                       placeholder="Ex: Matemática, Física, Inglês...">
                                @error('disciplina')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="preco" class="form-label">
                                    <i class="fas fa-euro-sign text-success me-1"></i>
                                    Preço (€) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('preco') is-invalid @enderror" 
                                           id="preco" 
                                           name="preco" 
                                           value="{{ old('preco', $explicacao->preco) }}" 
                                           required 
                                           min="0" 
                                           step="0.01"
                                           placeholder="0.00">
                                    <span class="input-group-text">€</span>
                                    @error('preco')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Data e Horário -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="data_explicacao" class="form-label">
                                    <i class="fas fa-calendar-alt text-info me-1"></i>
                                    Data <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control @error('data_explicacao') is-invalid @enderror" 
                                       id="data_explicacao" 
                                       name="data_explicacao" 
                                       value="{{ old('data_explicacao', $explicacao->data_explicacao) }}" 
                                       required 
                                       min="{{ date('Y-m-d') }}">
                                @error('data_explicacao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="hora_inicio" class="form-label">
                                    <i class="fas fa-clock text-warning me-1"></i>
                                    Hora Início <span class="text-danger">*</span>
                                </label>
                                <input type="time" 
                                       class="form-control @error('hora_inicio') is-invalid @enderror" 
                                       id="hora_inicio" 
                                       name="hora_inicio" 
                                       value="{{ old('hora_inicio', \Carbon\Carbon::createFromFormat('H:i:s', $explicacao->hora_inicio)->format('H:i')) }}" 
                                       required
                                       step="900">
                                @error('hora_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="hora_fim" class="form-label">
                                    <i class="fas fa-clock text-warning me-1"></i>
                                    Hora Fim <span class="text-danger">*</span>
                                </label>
                                <input type="time" 
                                       class="form-control @error('hora_fim') is-invalid @enderror" 
                                       id="hora_fim" 
                                       name="hora_fim" 
                                       value="{{ old('hora_fim', \Carbon\Carbon::createFromFormat('H:i:s', $explicacao->hora_fim)->format('H:i')) }}" 
                                       required
                                       step="900">
                                @error('hora_fim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text" id="duracao-info"></div>
                            </div>
                        </div>

                        <!-- Local -->
                        <div class="mb-4">
                            <label for="local" class="form-label">
                                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                Local <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('local') is-invalid @enderror" 
                                   id="local" 
                                   name="local" 
                                   value="{{ old('local', $explicacao->local) }}" 
                                   required 
                                   maxlength="255"
                                   placeholder="Ex: Online, Domicílio do Aluno, Biblioteca, Escola...">
                            @error('local')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Dados do Aluno -->
                        <h5 class="mb-3">
                            <i class="fas fa-user-graduate text-info me-2"></i>
                            Dados do Aluno
                        </h5>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="nome_aluno" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Nome do Aluno <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('nome_aluno') is-invalid @enderror" 
                                       id="nome_aluno" 
                                       name="nome_aluno" 
                                       value="{{ old('nome_aluno', $explicacao->nome_aluno) }}" 
                                       required 
                                       maxlength="255"
                                       placeholder="Nome completo do aluno">
                                @error('nome_aluno')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="contacto_aluno" class="form-label">
                                    <i class="fas fa-phone me-1"></i>
                                    Contacto <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('contacto_aluno') is-invalid @enderror" 
                                       id="contacto_aluno" 
                                       name="contacto_aluno" 
                                       value="{{ old('contacto_aluno', $explicacao->contacto_aluno) }}" 
                                       required 
                                       maxlength="255"
                                       placeholder="Email ou telefone do aluno/responsável">
                                @error('contacto_aluno')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Observações -->
                        <div class="mb-4">
                            <label for="observacoes" class="form-label">
                                <i class="fas fa-sticky-note text-secondary me-1"></i>
                                Observações <span class="text-muted">(opcional)</span>
                            </label>
                            <textarea class="form-control @error('observacoes') is-invalid @enderror" 
                                      id="observacoes" 
                                      name="observacoes" 
                                      rows="4" 
                                      maxlength="1000"
                                      placeholder="Informações adicionais sobre a explicação, conteúdo a abordar, material necessário...">{{ old('observacoes', $explicacao->observacoes) }}</textarea>
                            @error('observacoes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small>
                                    <span id="observacoes-count">{{ strlen(old('observacoes', $explicacao->observacoes ?? '')) }}</span>/1000 caracteres
                                </small>
                            </div>
                        </div>

                        <!-- Status Atual -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-info-circle text-info me-1"></i>
                                    Status Atual
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Status da Explicação:</strong>
                                        @php
                                            $statusLabels = [
                                                'agendada' => 'Agendada',
                                                'confirmada' => 'Confirmada',
                                                'concluida' => 'Concluída',
                                                'cancelada' => 'Cancelada',
                                            ];
                                            $statusClasses = [
                                                'agendada' => 'warning',
                                                'confirmada' => 'info',
                                                'concluida' => 'success',
                                                'cancelada' => 'danger',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusClasses[$explicacao->status] ?? 'secondary' }} ms-2">
                                            {{ $statusLabels[$explicacao->status] ?? $explicacao->status }}
                                        </span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Aprovação Admin:</strong>
                                        @if($explicacao->aprovacao_admin === 'pendente')
                                            <span class="badge bg-warning ms-2">Pendente</span>
                                        @elseif($explicacao->aprovacao_admin === 'aprovada')
                                            <span class="badge bg-success ms-2">Aprovada</span>
                                        @elseif($explicacao->aprovacao_admin === 'rejeitada')
                                            <span class="badge bg-danger ms-2">Rejeitada</span>
                                        @endif
                                    </div>
                                </div>
                                @if($explicacao->aprovacao_admin === 'aprovada' && $explicacao->data_aprovacao)
                                    <small class="text-muted mt-2 d-block">
                                        Aprovada em {{ \Carbon\Carbon::parse($explicacao->data_aprovacao)->format('d/m/Y H:i') }}
                                    </small>
                                @endif
                            </div>
                        </div>

                                        <!-- Botões de Ação -->
                <div class="text-center">
                    <button type="submit" class="btn btn-success btn-lg me-3 mb-2">
                        <i class="fas fa-save me-1"></i> Guardar Alterações
                    </button>
                    <a href="{{ route('explicacoes.show', $explicacao->id) }}" class="btn btn-secondary btn-lg me-3 mb-2">
                        <i class="fas fa-eye me-1"></i> Ver Detalhes
                    </a>
                    <a href="{{ route('explicacoes.index') }}" class="btn btn-outline-secondary btn-lg mb-2">
                        <i class="fas fa-arrow-left me-1"></i> Voltar à Lista
                    </a>
                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos do formulário
    const observacoesTextarea = document.getElementById('observacoes');
    const observacoesCount = document.getElementById('observacoes-count');
    const horaInicio = document.getElementById('hora_inicio');
    const horaFim = document.getElementById('hora_fim');
    const duracaoInfo = document.getElementById('duracao-info');
    const form = document.getElementById('formEdicaoExplicacao');

    // Contador de caracteres para observações
    if (observacoesTextarea && observacoesCount) {
        observacoesTextarea.addEventListener('input', function() {
            const length = this.value.length;
            observacoesCount.textContent = length;
            
            // Alterar cor baseado no limite
            if (length > 900) {
                observacoesCount.className = 'text-danger fw-bold';
            } else if (length > 800) {
                observacoesCount.className = 'text-warning fw-bold';
            } else {
                observacoesCount.className = 'text-muted';
            }
        });
    }
    
    // Cálculo automático da duração
    function calcularDuracao() {
        if (horaInicio.value && horaFim.value) {
            const inicio = new Date('2000-01-01 ' + horaInicio.value + ':00');
            const fim = new Date('2000-01-01 ' + horaFim.value + ':00');
            
            if (fim > inicio) {
                const diff = (fim - inicio) / (1000 * 60); // diferença em minutos
                const horas = Math.floor(diff / 60);
                const minutos = diff % 60;
                
                let duracaoTexto = '';
                if (horas > 0) {
                    duracaoTexto += horas + 'h ';
                }
                duracaoTexto += minutos + 'min';
                
                duracaoInfo.innerHTML = `<i class="fas fa-clock me-1"></i>Duração: ${duracaoTexto}`;
                duracaoInfo.className = 'form-text text-success';
                
                // Validar duração mínima
                if (diff < 30) {
                    duracaoInfo.innerHTML = `<i class="fas fa-exclamation-triangle me-1"></i>Duração mínima: 30 minutos`;
                    duracaoInfo.className = 'form-text text-warning';
                }
            } else {
                duracaoInfo.innerHTML = `<i class="fas fa-times me-1"></i>Hora de fim deve ser posterior à de início`;
                duracaoInfo.className = 'form-text text-danger';
            }
        } else {
            duracaoInfo.innerHTML = '';
        }
    }
    
    // Event listeners para cálculo de duração
    if (horaInicio && horaFim && duracaoInfo) {
        horaInicio.addEventListener('change', calcularDuracao);
        horaFim.addEventListener('change', calcularDuracao);
        
        // Calcular duração inicial
        calcularDuracao();
    }
    
    // Validação do formulário antes do submit
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const errors = [];
            
            // Validar duração
            if (horaInicio.value && horaFim.value) {
                const inicio = new Date('2000-01-01 ' + horaInicio.value + ':00');
                const fim = new Date('2000-01-01 ' + horaFim.value + ':00');
                const diff = (fim - inicio) / (1000 * 60);
                
                if (diff <= 0) {
                    errors.push('A hora de fim deve ser posterior à hora de início.');
                    isValid = false;
                }
                
                if (diff < 30) {
                    errors.push('A explicação deve ter uma duração mínima de 30 minutos.');
                    isValid = false;
                }
                
                if (diff > 480) { // 8 horas
                    errors.push('A explicação não pode ter mais de 8 horas de duração.');
                    isValid = false;
                }
            }
            
            // Validar data
            const dataExplicacao = document.getElementById('data_explicacao').value;
            if (dataExplicacao) {
                const hoje = new Date();
                hoje.setHours(0, 0, 0, 0);
                const dataEscolhida = new Date(dataExplicacao);
                
                if (dataEscolhida < hoje) {
                    errors.push('A data da explicação não pode ser anterior a hoje.');
                    isValid = false;
                }
            }
            
            // Se há erros, mostrar e cancelar submit
            if (!isValid) {
                e.preventDefault();
                alert('Por favor, corrija os seguintes erros:\n\n' + errors.join('\n'));
                return false;
            }
            
            // Confirmar submissão
            const confirmMessage = 'Tem certeza que deseja guardar as alterações na explicação?';
            if (!confirm(confirmMessage)) {
                e.preventDefault();
                return false;
            }
            
            // Mostrar loading
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Guardando...';
            }
        });
    }
    
    // Auto-ajustar altura do textarea
    if (observacoesTextarea) {
        observacoesTextarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }
    
    // Formatação do preço
    const precoInput = document.getElementById('preco');
    if (precoInput) {
        precoInput.addEventListener('blur', function() {
            const valor = parseFloat(this.value);
            if (!isNaN(valor)) {
                this.value = valor.toFixed(2);
            }
        });
    }
});
</script>

<style>
.form-label {
    font-weight: 600;
    color: #495057;
}

.card {
    border-radius: 10px;
    border: none;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.btn {
    border-radius: 6px;
    font-weight: 500;
}

.alert {
    border-radius: 8px;
    border: none;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.form-text {
    font-size: 0.875rem;
    font-weight: 500;
}

.input-group-text {
    border-color: #dee2e6;
    background-color: #f8f9fa;
    font-weight: 500;
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

.card.bg-light {
    background-color: #f8f9fa !important;
    border: 1px solid #e9ecef;
}

@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin-bottom: 0.25rem;
    }
    
    .d-grid.gap-2.d-md-flex {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
}
</style>
@endsection