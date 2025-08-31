@extends('layouts.app')

@section('title', 'Detalhes da Explicação - Admin')

@section('content')
<div class="container-fluid">
    <!-- Header com navegação -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">
                        <i class="fas fa-eye mr-2"></i>
                        Detalhes da Explicação
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.explicacoes.index') }}">Explicações</a>
                            </li>
                            <li class="breadcrumb-item active">Detalhes</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar">
                    <a href="{{ route('admin.explicacoes.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informações Principais -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle text-primary mr-2"></i>
                        Informações da Explicação
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Professor:</strong></td>
                                    <td>
                                        {{ $explicacao->user->name }}
                                        <br>
                                        <small class="text-muted">{{ $explicacao->user->email }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Disciplina:</strong></td>
                                    <td>
                                        <span class="badge badge-light p-2">{{ $explicacao->disciplina }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Data:</strong></td>
                                    <td>
                                        <i class="fas fa-calendar text-primary mr-1"></i>
                                        {{ date('d/m/Y', strtotime($explicacao->data_explicacao)) }}
                                        <br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($explicacao->data_explicacao)->format('l') }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Horário:</strong></td>
                                    <td>
                                        <i class="fas fa-clock text-info mr-1"></i>
                                        {{ $explicacao->hora_inicio }} - {{ $explicacao->hora_fim }}
                                        @php
                                            $inicio = strtotime($explicacao->hora_inicio);
                                            $fim = strtotime($explicacao->hora_fim);
                                            $duracao = ($fim - $inicio) / 60;
                                        @endphp
                                        <br>
                                        <small class="text-muted">Duração: {{ $duracao }} minutos</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Local:</strong></td>
                                    <td>
                                        <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                        {{ $explicacao->local }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Aluno:</strong></td>
                                    <td>
                                        <i class="fas fa-user text-secondary mr-1"></i>
                                        {{ $explicacao->nome_aluno }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Contacto:</strong></td>
                                    <td>
                                        <i class="fas fa-phone text-success mr-1"></i>
                                        {{ $explicacao->contacto_aluno }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Preço:</strong></td>
                                    <td>
                                        <span class="h4 text-success mb-0">
                                            <i class="fas fa-euro-sign"></i>{{ number_format($explicacao->preco, 2) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @php
                                            $statusLabels = [
                                                'agendada' => ['Agendada', 'warning'],
                                                'confirmada' => ['Confirmada', 'info'],
                                                'concluida' => ['Concluída', 'success'],
                                                'cancelada' => ['Cancelada', 'danger'],
                                            ];
                                            $statusInfo = $statusLabels[$explicacao->status] ?? ['Desconhecido', 'secondary'];
                                        @endphp
                                        <span class="badge badge-{{ $statusInfo[1] }} p-2">
                                            {{ $statusInfo[0] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Criada em:</strong></td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $explicacao->created_at->format('d/m/Y H:i') }}
                                            <br>
                                            ({{ $explicacao->created_at->diffForHumans() }})
                                        </small>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($explicacao->observacoes)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h6><i class="fas fa-sticky-note text-warning mr-2"></i>Observações do Professor:</h6>
                                <div class="alert alert-light">
                                    {{ $explicacao->observacoes }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Painel de Aprovação -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tasks text-warning mr-2"></i>
                        Status de Aprovação
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Status atual -->
                    <div class="mb-3">
                        @if($explicacao->aprovacao_admin === 'pendente' || !$explicacao->aprovacao_admin)
                            <div class="alert alert-warning">
                                <i class="fas fa-clock mr-2"></i>
                                <strong>Aguardando Aprovação</strong>
                                <p class="mb-0 mt-2">Esta explicação está pendente de aprovação administrativa.</p>
                            </div>
                        @elseif($explicacao->aprovacao_admin === 'aprovada')
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle mr-2"></i>
                                <strong>Aprovada</strong>
                                <p class="mb-1 mt-2">Aprovada em: {{ $explicacao->data_aprovacao->format('d/m/Y H:i') }}</p>
                                @if($explicacao->aprovadoPor)
                                    <p class="mb-0">Por: {{ $explicacao->aprovadoPor->name }}</p>
                                @endif
                            </div>
                        @elseif($explicacao->aprovacao_admin === 'rejeitada')
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle mr-2"></i>
                                <strong>Rejeitada</strong>
                                <p class="mb-1 mt-2">Rejeitada em: {{ $explicacao->data_aprovacao->format('d/m/Y H:i') }}</p>
                                @if($explicacao->aprovadoPor)
                                    <p class="mb-0">Por: {{ $explicacao->aprovadoPor->name }}</p>
                                @endif
                            </div>
                            @if($explicacao->motivo_rejeicao)
                                <div class="alert alert-light">
                                    <h6>Motivo da Rejeição:</h6>
                                    <p class="mb-0">{{ $explicacao->motivo_rejeicao }}</p>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Ações de Aprovação -->
                    <div class="d-grid gap-2">
                        @if($explicacao->aprovacao_admin === 'pendente' || !$explicacao->aprovacao_admin)
                            <!-- Aprovar -->
                            <form method="POST" action="{{ route('admin.explicacoes.aprovar', $explicacao->id) }}" 
                                  onsubmit="return confirm('Tem certeza que deseja aprovar esta explicação?')" class="mb-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-check mr-2"></i>
                                    Aprovar Explicação
                                </button>
                            </form>
                            
                            <!-- Rejeitar -->
                            <button type="button" class="btn btn-danger btn-lg w-100" 
                                    onclick="mostrarModalRejeicao()">
                                <i class="fas fa-times mr-2"></i>
                                Rejeitar Explicação
                            </button>
                        @else
                            <!-- Reverter -->
                            <form method="POST" action="{{ route('admin.explicacoes.reverter', $explicacao->id) }}" 
                                  onsubmit="return confirm('Tem certeza que deseja reverter esta decisão? A explicação voltará para pendente.')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning btn-lg w-100">
                                    <i class="fas fa-undo mr-2"></i>
                                    Reverter Decisão
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Histórico/Timeline -->
            @if($explicacao->data_aprovacao)
            <div class="card shadow mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-history text-info mr-2"></i>
                        Histórico
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <i class="fas fa-plus-circle text-primary"></i>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $explicacao->created_at->format('d/m/Y H:i') }}</small>
                                <p class="mb-0">Explicação criada por {{ $explicacao->user->name }}</p>
                            </div>
                        </div>
                        @if($explicacao->data_aprovacao)
                        <div class="timeline-item">
                            @if($explicacao->aprovacao_admin === 'aprovada')
                                <i class="fas fa-check-circle text-success"></i>
                            @else
                                <i class="fas fa-times-circle text-danger"></i>
                            @endif
                            <div class="timeline-content">
                                <small class="text-muted">{{ $explicacao->data_aprovacao->format('d/m/Y H:i') }}</small>
                                <p class="mb-0">
                                    {{ $explicacao->aprovacao_admin === 'aprovada' ? 'Aprovada' : 'Rejeitada' }}
                                    @if($explicacao->aprovadoPor)
                                        por {{ $explicacao->aprovadoPor->name }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para Rejeição -->
<div class="modal fade" id="modalRejeicao" tabindex="-1" role="dialog" aria-labelledby="modalRejeicaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalRejeicaoLabel">
                    <i class="fas fa-times-circle mr-2"></i>
                    Rejeitar Explicação
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.explicacoes.rejeitar', $explicacao->id) }}" id="formRejeicao">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Atenção:</strong> Esta ação irá rejeitar a explicação e notificar o professor sobre os motivos.
                    </div>
                    
                    <div class="form-group">
                        <label for="motivo_rejeicao">
                            <strong>Motivo da Rejeição <span class="text-danger">*</span></strong>
                        </label>
                        <textarea class="form-control" 
                                  id="motivo_rejeicao" 
                                  name="motivo_rejeicao" 
                                  rows="5" 
                                  required 
                                  minlength="10"
                                  maxlength="500"
                                  placeholder="Descreva detalhadamente o motivo da rejeição para que o professor possa corrigir..."></textarea>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Seja específico nos motivos para ajudar o professor a corrigir os problemas identificados.
                            <br>
                            <span id="contador-caracteres">0/500 caracteres</span>
                        </small>
                    </div>

                    <div class="form-group">
                        <label class="text-muted">
                            <i class="fas fa-lightbulb mr-1"></i>
                            Clique num motivo comum para pré-preencher:
                        </label>
                        <div class="d-flex flex-wrap">
                            <button type="button" class="btn btn-outline-secondary btn-sm mr-2 mb-2 motivo-exemplo" 
                                    data-motivo="Horário incompatível com disponibilidade estabelecida.">
                                <i class="fas fa-clock mr-1"></i>Horário incompatível
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm mr-2 mb-2 motivo-exemplo" 
                                    data-motivo="Preço não está de acordo com a tabela de valores estabelecida.">
                                <i class="fas fa-euro-sign mr-1"></i>Preço incorreto
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm mr-2 mb-2 motivo-exemplo" 
                                    data-motivo="Informações do aluno incompletas ou incorretas.">
                                <i class="fas fa-user mr-1"></i>Info. aluno incompletas
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm mr-2 mb-2 motivo-exemplo" 
                                    data-motivo="Local da explicação não especificado adequadamente.">
                                <i class="fas fa-map-marker-alt mr-1"></i>Local inadequado
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm mr-2 mb-2 motivo-exemplo" 
                                    data-motivo="Disciplina não corresponde às habilitações do professor.">
                                <i class="fas fa-book mr-1"></i>Disciplina incorreta
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm mr-2 mb-2 motivo-exemplo" 
                                    data-motivo="Dados de contacto do aluno em falta ou inválidos.">
                                <i class="fas fa-phone mr-1"></i>Contacto inválido
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger" id="btnConfirmarRejeicao">
                        <i class="fas fa-times-circle mr-1"></i>
                        Confirmar Rejeição
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@endsection

<!-- Script inline direto no HTML -->
<script>
// Função para mostrar modal de rejeição - GLOBAL
function mostrarModalRejeicao() {
    console.log('Tentando abrir modal de rejeição...');
    
    const modal = document.getElementById('modalRejeicao');
    const textarea = document.getElementById('motivo_rejeicao');
    
    if (!modal) {
        console.error('Modal não encontrado');
        alert('Erro: Modal não encontrado');
        return;
    }
    
    // Limpar textarea
    if (textarea) {
        textarea.value = '';
        updateCharacterCount();
    }
    
    // Tentar com jQuery primeiro (Bootstrap 4)
    if (typeof $ !== 'undefined' && $.fn.modal) {
        $('#modalRejeicao').modal('show');
    }
    // Se não tiver jQuery, tentar com Bootstrap 5
    else if (typeof bootstrap !== 'undefined') {
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    }
    // Fallback manual
    else {
        modal.style.display = 'block';
        modal.classList.add('show');
        document.body.classList.add('modal-open');
        
        // Criar backdrop
        const backdrop = document.createElement('div');
        backdrop.classList.add('modal-backdrop', 'fade', 'show');
        backdrop.id = 'modal-backdrop-manual';
        document.body.appendChild(backdrop);
    }
}

function fecharModal() {
    const modal = document.getElementById('modalRejeicao');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.remove('show');
        document.body.classList.remove('modal-open');
        
        const backdrop = document.getElementById('modal-backdrop-manual');
        if (backdrop) {
            backdrop.remove();
        }
    }
}

function updateCharacterCount() {
    const textarea = document.getElementById('motivo_rejeicao');
    const contador = document.getElementById('contador-caracteres');
    
    if (textarea && contador) {
        const length = textarea.value.length;
        contador.textContent = `${length}/500 caracteres`;
        
        if (length > 450) {
            contador.classList.add('text-warning');
            contador.classList.remove('text-danger');
        } else if (length >= 500) {
            contador.classList.remove('text-warning');
            contador.classList.add('text-danger');
        } else {
            contador.classList.remove('text-warning', 'text-danger');
        }
    }
}

// Quando o documento carregar
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM carregado - inicializando funcionalidades admin show');
    
    // Event listeners para fechar modal
    document.querySelectorAll('[data-dismiss="modal"]').forEach(btn => {
        btn.addEventListener('click', fecharModal);
    });
    
    // Fechar modal ao clicar fora
    const modal = document.getElementById('modalRejeicao');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModal();
            }
        });
    }
    
    // Contador de caracteres
    const textarea = document.getElementById('motivo_rejeicao');
    if (textarea) {
        textarea.addEventListener('input', updateCharacterCount);
    }

    // Preencher motivo com exemplos
    document.querySelectorAll('.motivo-exemplo').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const motivo = this.getAttribute('data-motivo');
            const textarea = document.getElementById('motivo_rejeicao');
            
            if (textarea) {
                if (textarea.value.trim()) {
                    textarea.value += '\n\n' + motivo;
                } else {
                    textarea.value = motivo;
                }
                
                textarea.focus();
                updateCharacterCount();
            }
        });
    });

    // Validação do formulário antes de enviar
    const formRejeicao = document.getElementById('formRejeicao');
    if (formRejeicao) {
        formRejeicao.addEventListener('submit', function(e) {
            const textarea = document.getElementById('motivo_rejeicao');
            
            if (!textarea || textarea.value.trim().length < 10) {
                e.preventDefault();
                alert('Por favor, forneça um motivo detalhado para a rejeição (mínimo 10 caracteres).');
                if (textarea) textarea.focus();
                return false;
            }
            
            if (!confirm('Tem certeza que deseja rejeitar esta explicação?')) {
                e.preventDefault();
                return false;
            }
            
            const btn = document.getElementById('btnConfirmarRejeicao');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>A rejeitar...';
                btn.disabled = true;
            }
        });
    }
    
    // Mostrar mensagens de sucesso/erro
    @if(session('success'))
        if (typeof toastr !== 'undefined') {
            toastr.success('{{ session('success') }}');
        } else {
            alert('{{ session('success') }}');
        }
    @endif

    @if(session('error'))
        if (typeof toastr !== 'undefined') {
            toastr.error('{{ session('error') }}');
        } else {
            alert('{{ session('error') }}');
        }
    @endif
});
</script>

@push('styles')
<style>
.timeline {
    position: relative;
    padding: 0;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.timeline-item i {
    position: absolute;
    left: 0;
    top: 0;
    width: 1.5rem;
    height: 1.5rem;
    line-height: 1.5rem;
    text-align: center;
    background: white;
    border-radius: 50%;
}

.timeline-item:not(:last-child):before {
    content: '';
    position: absolute;
    left: 0.75rem;
    top: 1.5rem;
    width: 2px;
    height: calc(100% + 0.5rem);
    background: #e9ecef;
}

.timeline-content {
    background: #f8f9fa;
    padding: 0.75rem;
    border-radius: 0.375rem;
    border-left: 3px solid #dee2e6;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.btn-lg {
    font-weight: 600;
}

.alert {
    border: none;
    border-radius: 0.5rem;
}

.motivo-exemplo:hover {
    background-color: #e9ecef;
}
</style>
@endpushmotivo_rejeicao');
            
            if (textarea) {
                if (textarea.value.trim()) {
                    textarea.value += '\n\n' + motivo;
                } else {
                    textarea.value = motivo;
                }
                
                // Focar no textarea
                textarea.focus();
                updateCharacterCount();
            }
        });
    });

    // Validação do formulário antes de enviar
    const formRejeicao = document.getElementById('formRejeicao');
    if (formRejeicao) {
        formRejeicao.addEventListener('submit', function(e) {
            const textarea = document.getElementById('motivo_rejeicao');
            
            if (!textarea || textarea.value.trim().length < 10) {
                e.preventDefault();
                alert('Por favor, forneça um motivo detalhado para a rejeição (mínimo 10 caracteres).');
                if (textarea) textarea.focus();
                return false;
            }
            
            // Confirmação final
            if (!confirm('Tem certeza que deseja rejeitar esta explicação?')) {
                e.preventDefault();
                return false;
            }
            
            // Mostrar loading no botão
            const btn = document.getElementById('btnConfirmarRejeicao');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>A rejeitar...';
                btn.disabled = true;
            }
        });
    }
});

// Mostrar mensagens de sucesso/erro
@if(session('success'))
    if (typeof toastr !== 'undefined') {
        toastr.success('{{ session('success') }}');
    } else {
        alert('{{ session('success') }}');
    }
@endif

@if(session('error'))
    if (typeof toastr !== 'undefined') {
        toastr.error('{{ session('error') }}');
    } else {
        alert('{{ session('error') }}');
    }
@endif
</script>
@endpush

@push('styles')
<style>
.timeline {
    position: relative;
    padding: 0;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.timeline-item i {
    position: absolute;
    left: 0;
    top: 0;
    width: 1.5rem;
    height: 1.5rem;
    line-height: 1.5rem;
    text-align: center;
    background: white;
    border-radius: 50%;
}

.timeline-item:not(:last-child):before {
    content: '';
    position: absolute;
    left: 0.75rem;
    top: 1.5rem;
    width: 2px;
    height: calc(100% + 0.5rem);
    background: #e9ecef;
}

.timeline-content {
    background: #f8f9fa;
    padding: 0.75rem;
    border-radius: 0.375rem;
    border-left: 3px solid #dee2e6;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.btn-lg {
    font-weight: 600;
}

.alert {
    border: none;
    border-radius: 0.5rem;
}

.motivo-exemplo:hover {
    background-color: #e9ecef;
}
</style>
@endpush