@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar com lista de grupos (opcional, para mobile pode ser colapsável) -->
        <div class="col-md-3 d-none d-md-block bg-light" style="height: calc(100vh - 100px); overflow-y: auto;">
            <div class="p-3">
                <h5 class="mb-3">
                    <i class="fas fa-comments"></i> Grupo
                </h5>
                <a href="{{ route('mensagens.index') }}" class="btn btn-outline-primary btn-block mb-3">
                    <i class="fas fa-arrow-left"></i> Ver Todos os Grupos
                </a>
               <div class="list-group">
    @foreach($grupo->membros as $membro)
        <div class="list-group-item">
            <div class="d-flex align-items-center">
                <div class="bg-{{ $grupo->cor }} text-white rounded-circle d-flex align-items-center justify-content-center mr-3" 
                     style="width: 40px; height: 40px;">
                    <strong>{{ strtoupper(substr($membro->name, 0, 1)) }}</strong>
                </div>
                <div>
                    <strong>{{ $membro->name }}</strong>
                    @if($membro->id == auth()->id())
                        <span class="badge badge-success ml-2">Você</span>
                    @endif
                    @if($membro->role === 'admin')
                        <span class="badge badge-danger ml-2">
                            <i class="fas fa-crown"></i> Admin
                        </span>
                    @endif
                    <br>
                    <small class="text-muted">{{ $membro->email }}</small>
                </div>
            </div>
        </div>
    @endforeach
</div>
            </div>
        </div>

        <!-- Área de Chat -->
        <div class="col-md-9 col-12 px-0">
            <div class="card shadow-sm" style="height: calc(100vh - 100px);">
                <!-- Cabeçalho do Chat -->
                <div class="card-header bg-{{ $grupo->cor }} text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                <i class="fas fa-{{ $grupo->icone }}"></i>
                                {{ $grupo->nome }}
                            </h5>
                            <small>{{ $grupo->membros->count() }} membros</small>
                        </div>
                        <button class="btn btn-sm btn-light" 
                                data-toggle="modal" 
                                data-target="#infoGrupo">
                            <i class="fas fa-info-circle"></i>
                        </button>
                    </div>
                </div>

                <!-- Corpo do Chat - Mensagens -->
                <div class="card-body p-3" 
                     id="chatMessages" 
                     style="height: calc(100vh - 280px); overflow-y: auto;">
                    
                    @forelse($mensagens as $mensagem)
                        <div class="mb-3 {{ $mensagem->user_id == auth()->id() ? 'text-right' : '' }}" 
                             id="mensagem-{{ $mensagem->id }}">
                            
                            @if($mensagem->user_id == auth()->id())
                                <!-- Mensagem do usuário atual (direita) -->
                                <div class="d-inline-block" style="max-width: 70%;">
                                    <div class="bg-primary text-white rounded p-3 position-relative">
                                        <div class="message-content">
                                            {{ $mensagem->conteudo }}
                                        </div>
                                        @if($mensagem->editada)
                                            <small class="d-block mt-1 opacity-75">
                                                <i class="fas fa-edit"></i> Editada
                                            </small>
                                        @endif
                                        
                                        <!-- Menu de ações -->
                                        <div class="dropdown position-absolute" style="top: 5px; right: 5px;">
                                            <button class="btn btn-sm btn-link text-white p-0" 
                                                    data-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <button class="dropdown-item" 
                                                        onclick="editarMensagem({{ $mensagem->id }}, '{{ addslashes($mensagem->conteudo) }}')">
                                                    <i class="fas fa-edit"></i> Editar
                                                </button>
                                                <form action="{{ route('mensagens.destroy', $mensagem) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Eliminar mensagem?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fas fa-trash"></i> Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        {{ $mensagem->created_at->format('H:i') }}
                                    </small>
                                </div>
                            @else
                                <!-- Mensagem de outro usuário (esquerda) -->
                                <div class="d-inline-block" style="max-width: 70%;">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mr-2" 
                                             style="width: 35px; height: 35px; min-width: 35px;">
                                            <strong class="text-{{ $grupo->cor }}">
                                                {{ strtoupper(substr($mensagem->user->name, 0, 1)) }}
                                            </strong>
                                        </div>
                                        <div>
                                            <div class="bg-light rounded p-3">
                                                <strong class="d-block mb-1 text-{{ $grupo->cor }}">
                                                    {{ $mensagem->user->name }}
                                                </strong>
                                                <div class="message-content">
                                                    {{ $mensagem->conteudo }}
                                                </div>
                                                @if($mensagem->editada)
                                                    <small class="text-muted d-block mt-1">
                                                        <i class="fas fa-edit"></i> Editada
                                                    </small>
                                                @endif
                                            </div>
                                            <small class="text-muted d-block mt-1">
                                                {{ $mensagem->created_at->format('H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-comments fa-3x mb-3 opacity-50"></i>
                            <h5>Nenhuma mensagem ainda</h5>
                            <p>Seja o primeiro a enviar uma mensagem!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Footer do Chat - Caixa de Envio -->
                <div class="card-footer bg-light">
                    <form id="formEnviarMensagem" onsubmit="enviarMensagem(event)">
                        @csrf
                        <div class="input-group">
                            <textarea class="form-control" 
                                      id="mensagemTexto" 
                                      name="conteudo" 
                                      rows="2" 
                                      placeholder="Digite sua mensagem..." 
                                      required
                                      maxlength="5000"
                                      style="resize: none;"></textarea>
                            <div class="input-group-append">
                                <button type="submit" 
                                        class="btn btn-{{ $grupo->cor }}" 
                                        id="btnEnviar">
                                    <i class="fas fa-paper-plane"></i>
                                    <span class="d-none d-md-inline ml-1">Enviar</span>
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">
                            <span id="charCount">0</span>/5000 caracteres
                        </small>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Informações do Grupo -->
<div class="modal fade" id="infoGrupo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-{{ $grupo->cor }} text-white">
                <h5 class="modal-title">
                    <i class="fas fa-{{ $grupo->icone }}"></i>
                    {{ $grupo->nome }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if($grupo->descricao)
                    <p><strong>Descrição:</strong> {{ $grupo->descricao }}</p>
                    <hr>
                @endif
                
                <h6 class="mb-3"><strong>Membros ({{ $grupo->membros->count() }}):</strong></h6>
                <div class="list-group">
                    @foreach($grupo->membros as $membro)
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="bg-{{ $grupo->cor }} text-white rounded-circle d-flex align-items-center justify-content-center mr-3" 
                                     style="width: 40px; height: 40px;">
                                    <strong>{{ strtoupper(substr($membro->name, 0, 1)) }}</strong>
                                </div>
                                <div>
                                    <strong>{{ $membro->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $membro->email }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edição de Mensagem -->
<div class="modal fade" id="modalEditarMensagem" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit"></i> Editar Mensagem
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="formEditarMensagem" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <textarea class="form-control" 
                                  id="editarConteudo" 
                                  name="conteudo" 
                                  rows="5" 
                                  required 
                                  maxlength="5000"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const grupoId = {{ $grupo->id }};
let ultimaMensagemId = {{ $mensagens->last()->id ?? 0 }};
let autoScrollEnabled = true;

// Atualizar contagem de caracteres
document.getElementById('mensagemTexto').addEventListener('input', function() {
    document.getElementById('charCount').textContent = this.value.length;
});

// Enviar mensagem via AJAX
function enviarMensagem(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const btnEnviar = document.getElementById('btnEnviar');
    const textarea = document.getElementById('mensagemTexto');
    
    btnEnviar.disabled = true;
    btnEnviar.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    fetch('{{ route('mensagens.store', $grupo) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            textarea.value = '';
            document.getElementById('charCount').textContent = '0';
            carregarNovasMensagens();
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao enviar mensagem. Tente novamente.');
    })
    .finally(() => {
        btnEnviar.disabled = false;
        btnEnviar.innerHTML = '<i class="fas fa-paper-plane"></i> <span class="d-none d-md-inline ml-1">Enviar</span>';
    });
}

// Carregar novas mensagens
function carregarNovasMensagens() {
    fetch(`/mensagens/${grupoId}/carregar-novas?ultima_mensagem_id=${ultimaMensagemId}`)
        .then(response => response.json())
        .then(mensagens => {
            if (mensagens.length > 0) {
                mensagens.forEach(msg => {
                    adicionarMensagemAoChat(msg);
                    ultimaMensagemId = msg.id;
                });
                
                if (autoScrollEnabled) {
                    scrollToBottom();
                }
            }
        })
        .catch(error => console.error('Erro ao carregar mensagens:', error));
}

// Adicionar mensagem ao chat
function adicionarMensagemAoChat(mensagem) {
    const chatMessages = document.getElementById('chatMessages');
    const isOwn = mensagem.user_id == {{ auth()->id() }};
    
    const div = document.createElement('div');
    div.className = `mb-3 ${isOwn ? 'text-right' : ''}`;
    div.id = `mensagem-${mensagem.id}`;
    
    const time = new Date(mensagem.created_at).toLocaleTimeString('pt-PT', {hour: '2-digit', minute: '2-digit'});
    
    if (isOwn) {
        div.innerHTML = `
            <div class="d-inline-block" style="max-width: 70%;">
                <div class="bg-primary text-white rounded p-3">
                    ${mensagem.conteudo}
                </div>
                <small class="text-muted d-block mt-1">${time}</small>
            </div>
        `;
    } else {
        const inicial = mensagem.user.name.charAt(0).toUpperCase();
        div.innerHTML = `
            <div class="d-inline-block" style="max-width: 70%;">
                <div class="d-flex align-items-start">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mr-2" 
                         style="width: 35px; height: 35px; min-width: 35px;">
                        <strong class="text-{{ $grupo->cor }}">${inicial}</strong>
                    </div>
                    <div>
                        <div class="bg-light rounded p-3">
                            <strong class="d-block mb-1 text-{{ $grupo->cor }}">${mensagem.user.name}</strong>
                            ${mensagem.conteudo}
                        </div>
                        <small class="text-muted d-block mt-1">${time}</small>
                    </div>
                </div>
            </div>
        `;
    }
    
    chatMessages.appendChild(div);
}

// Editar mensagem
function editarMensagem(id, conteudo) {
    document.getElementById('editarConteudo').value = conteudo;
    document.getElementById('formEditarMensagem').action = `/mensagens/${id}`;
    $('#modalEditarMensagem').modal('show');
}

// Scroll automático
function scrollToBottom() {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Detectar se usuário scrollou manualmente
document.getElementById('chatMessages').addEventListener('scroll', function() {
    const isAtBottom = this.scrollHeight - this.clientHeight <= this.scrollTop + 100;
    autoScrollEnabled = isAtBottom;
});

// Inicialização
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
    
    // Polling para novas mensagens a cada 3 segundos
    setInterval(carregarNovasMensagens, 3000);
    
    // Enter para enviar (Shift+Enter para nova linha)
    document.getElementById('mensagemTexto').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('formEnviarMensagem').dispatchEvent(new Event('submit'));
        }
    });
});
</script>

<style>
.message-content {
    word-wrap: break-word;
    white-space: pre-wrap;
}

#chatMessages::-webkit-scrollbar {
    width: 8px;
}

#chatMessages::-webkit-scrollbar-track {
    background: #f1f1f1;
}

#chatMessages::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

#chatMessages::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
@endsection