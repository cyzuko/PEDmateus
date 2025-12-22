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
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
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
                                    <td><strong>Encarregado de educação/aluno:</strong></td>
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
                                <h6><i class="fas fa-sticky-note text-warning mr-2"></i>Observações:</h6>
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
                            <button type="button" class="btn btn-success btn-lg w-100 mb-2" 
                                    onclick="mostrarModalAprovacao()">
                                <i class="fas fa-check mr-2"></i>
                                Aprovar Explicação
                            </button>
                            
                            <!-- Rejeitar -->
                            <button type="button" class="btn btn-danger btn-lg w-100" 
                                    onclick="mostrarModalRejeicao()">
                                <i class="fas fa-times mr-2"></i>
                                Rejeitar Explicação
                            </button>
                        @else
                            <!-- Reverter -->
                            <button type="button" class="btn btn-warning btn-lg w-100" 
                                    onclick="mostrarModalReverter()">
                                <i class="fas fa-undo mr-2"></i>
                                Reverter Decisão
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Histórico/Timeline -->
            @if($explicacao->data_aprovacao)
            <div class="mt-4 p-3" style="background: white; border-radius: 0.5rem; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);">
                <h6 class="mb-3 pb-2" style="border-bottom: 1px solid #e9ecef;">
                    <i class="fas fa-history text-info mr-2"></i>
                    Histórico
                </h6>
                <div class="timeline-simple">
                    <div class="timeline-simple-item mb-3">
                        <div class="d-flex align-items-start">
                            <div class="mr-3">
                                <div style="width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center; background: white; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    <i class="fas fa-plus-circle text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ $explicacao->created_at->format('d/m/Y H:i') }}</small>
                                <p class="mb-0">Explicação criada por {{ $explicacao->user->name }}</p>
                            </div>
                        </div>
                    </div>
                    @if($explicacao->data_aprovacao)
                    <div class="timeline-simple-item">
                        <div class="d-flex align-items-start">
                            <div class="mr-3">
                                <div style="width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center; background: white; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    @if($explicacao->aprovacao_admin === 'aprovada')
                                        <i class="fas fa-check-circle text-success"></i>
                                    @else
                                        <i class="fas fa-times-circle text-danger"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">{{ $explicacao->data_aprovacao->format('d/m/Y H:i') }}</small>
                                <p class="mb-0">
                                    {{ $explicacao->aprovacao_admin === 'aprovada' ? 'Aprovada' : 'Rejeitada' }}
                                    @if($explicacao->aprovadoPor)
                                        por {{ $explicacao->aprovadoPor->name }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para Aprovar Explicação -->
<div class="modal fade" id="modalAprovacao" tabindex="-1" role="dialog" aria-labelledby="modalAprovacaoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalAprovacaoLabel">
                    <i class="fas fa-check-circle mr-2"></i>
                    Aprovar Explicação
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form id="formAprovacao" method="POST" action="{{ route('admin.explicacoes.aprovar', $explicacao->id) }}">
                @csrf
                @method('PATCH')
                
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Confirmação:</strong> Tem certeza que deseja aprovar esta explicação?
                    </div>
                    
                    <p class="mb-0">
                        <i class="fas fa-check mr-2 text-success"></i>
                        O aluno será notificado sobre a aprovação da explicação.
                    </p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-success" id="btnConfirmarAprovacao">
                        <i class="fas fa-check-circle mr-1"></i>
                        Confirmar Aprovação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Reverter Decisão -->
<div class="modal fade" id="modalReverter" tabindex="-1" role="dialog" aria-labelledby="modalReverterLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modalReverterLabel">
                    <i class="fas fa-undo mr-2"></i>
                    Reverter Decisão
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form method="POST" action="{{ route('admin.explicacoes.reverter', $explicacao->id) }}" id="formReverter">
                @csrf
                @method('PATCH')
                
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Atenção:</strong> Tem certeza que deseja reverter esta decisão?
                    </div>
                    
                    <p class="mb-0">
                        <i class="fas fa-info-circle mr-2 text-info"></i>
                        A explicação voltará para o estado <strong>pendente</strong> de aprovação.
                    </p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning" id="btnConfirmarReverter">
                        <i class="fas fa-undo mr-1"></i>
                        Confirmar Reversão
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Rejeitar Explicação -->
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
                        <strong>Atenção:</strong> Esta ação irá rejeitar a explicação e notificar sobre os motivos.
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
                                  placeholder="Descreva detalhadamente o motivo da rejeição para que se possa corrigir..."></textarea>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Seja específico nos motivos para ajudar na correção dos problemas identificados.
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
                                    data-motivo="Disciplina não corresponde às habilitações do explicador.">
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

<!-- Script inline direto no HTML -->
<script>
// Função para mostrar modal de aprovação
function mostrarModalAprovacao() {
    const modal = document.getElementById('modalAprovacao');
    
    if (!modal) {
        alert('Erro: Modal não encontrado');
        return;
    }
    
    // Mostrar modal
    if (typeof $ !== 'undefined' && $.fn.modal) {
        $('#modalAprovacao').modal('show');
    } else if (typeof bootstrap !== 'undefined') {
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    } else {
        modal.style.display = 'block';
        modal.classList.add('show');
        document.body.classList.add('modal-open');
        
        const backdrop = document.createElement('div');
        backdrop.classList.add('modal-backdrop', 'fade', 'show');
        backdrop.id = 'modal-backdrop-aprovacao';
        document.body.appendChild(backdrop);
    }
}

// Função para mostrar modal de reversão
function mostrarModalReverter() {
    const modal = document.getElementById('modalReverter');
    
    if (!modal) {
        alert('Erro: Modal não encontrado');
        return;
    }
    
    // Mostrar modal
    if (typeof $ !== 'undefined' && $.fn.modal) {
        $('#modalReverter').modal('show');
    } else if (typeof bootstrap !== 'undefined') {
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    } else {
        modal.style.display = 'block';
        modal.classList.add('show');
        document.body.classList.add('modal-open');
        
        const backdrop = document.createElement('div');
        backdrop.classList.add('modal-backdrop', 'fade', 'show');
        backdrop.id = 'modal-backdrop-reverter';
        document.body.appendChild(backdrop);
    }
}

// Função para mostrar modal de rejeição
function mostrarModalRejeicao() {
    const modal = document.getElementById('modalRejeicao');
    const textarea = document.getElementById('motivo_rejeicao');
    
    if (!modal) {
        alert('Erro: Modal não encontrado');
        return;
    }
    
    // Limpar textarea
    if (textarea) {
        textarea.value = '';
        updateCharacterCount();
    }
    
    // Mostrar modal
    if (typeof $ !== 'undefined' && $.fn.modal) {
        $('#modalRejeicao').modal('show');
    } else if (typeof bootstrap !== 'undefined') {
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    } else {
        modal.style.display = 'block';
        modal.classList.add('show');
        document.body.classList.add('modal-open');
        
        const backdrop = document.createElement('div');
        backdrop.classList.add('modal-backdrop', 'fade', 'show');
        backdrop.id = 'modal-backdrop-rejeicao';
        document.body.appendChild(backdrop);
    }
}

function fecharModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        modal.classList.remove('show');
        document.body.classList.remove('modal-open');
        
        const backdrop = document.querySelector('.modal-backdrop');
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
    // Event listeners para fechar modais
    document.querySelectorAll('[data-dismiss="modal"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                fecharModal(modal.id);
            }
        });
    });
    
    // Fechar modais ao clicar fora
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModal(this.id);
            }
        });
    });
    
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

    // Validação do formulário de aprovação
    const formAprovacao = document.getElementById('formAprovacao');
    if (formAprovacao) {
        formAprovacao.addEventListener('submit', function(e) {
            const btn = document.getElementById('btnConfirmarAprovacao');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>A aprovar...';
                btn.disabled = true;
            }
        });
    }
    
    // Validação do formulário de reversão
    const formReverter = document.getElementById('formReverter');
    if (formReverter) {
        formReverter.addEventListener('submit', function(e) {
            const btn = document.getElementById('btnConfirmarReverter');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>A reverter...';
                btn.disabled = true;
            }
        });
    }

    // Validação do formulário de rejeição
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
/* Remover borda do card do histórico */
.historico-body {
    border-left: none !important;
    padding-left: 1.5rem !important;
    overflow: hidden;
    background: white !important;
    position: relative;
}

.historico-body::before,
.historico-body::after {
    display: none !important;
}

.historico-body * {
    border-left: none !important;
}

/* Timeline limpo sem linhas */
.timeline {
    position: relative;
    padding: 0;
    margin: 0;
    list-style: none;
    overflow: visible;
    border: none !important;
    background: white;
}

.timeline::before,
.timeline::after {
    content: none !important;
    display: none !important;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
    padding-left: 3.5rem;
    min-height: 2rem;
    background: white;
}

.timeline-item::before,
.timeline-item::after {
    content: none !important;
    display: none !important;
    border: none !important;
    background: none !important;
}

.timeline-icon {
    position: absolute;
    left: 0;
    top: 0;
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    z-index: 20;
}

.timeline-icon::before,
.timeline-icon::after {
    content: none !important;
    display: none !important;
}

.timeline-icon i {
    font-size: 1rem;
}

.timeline-content {
    background: white;
    padding: 0.75rem 1rem;
    border-radius: 0.375rem;
    position: relative;
    z-index: 10;
    margin-left: -50px;
    padding-left: 50px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
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

/* Melhorias para os modais */
.modal-header {
    border-bottom: none;
}

.modal-footer {
    border-top: none;
}
</style>
@endpush