@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Mensagens de Feedback --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        {{-- Sidebar --}}
        <aside class="col-md-3 d-none d-md-block bg-light sidebar-chat">
            <div class="p-3">
                <h5 class="mb-3">
                    <i class="fas fa-comments"></i> Grupo
                </h5>
                <a href="{{ route('mensagens.index') }}" 
                   class="btn btn-outline-primary btn-block mb-3"
                   aria-label="Voltar aos grupos">
                    <i class="fas fa-arrow-left"></i> Ver Todos os Grupos
                </a>
                
                <div class="list-group">
                    @foreach($grupo->membros as $membro)
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-{{ $grupo->cor }} text-white rounded-circle d-flex align-items-center justify-content-center mr-3" 
                                     title="{{ $membro->name }}">
                                    <strong>{{ strtoupper(substr($membro->name, 0, 1)) }}</strong>
                                </div>
                                <div>
                                    <strong>{{ $membro->name }}</strong>
                                    @if($membro->id === auth()->id())
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
        </aside>

        {{-- Área de Chat --}}
        <div class="col-md-9 col-12 px-0">
            <div class="card shadow-sm chat-container">
                {{-- Cabeçalho --}}
                <div class="card-header bg-{{ $grupo->cor }} text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                <i class="fas fa-{{ $grupo->icone }}"></i>
                                {{ $grupo->nome }}
                            </h5>
                            <small>{{ $grupo->membros->count() }} {{ Str::plural('membro', $grupo->membros->count()) }}</small>
                        </div>
                        <button class="btn btn-sm btn-light" 
                                data-toggle="modal" 
                                data-target="#infoGrupo"
                                aria-label="Informações do grupo">
                            <i class="fas fa-info-circle"></i>
                        </button>
                    </div>
                </div>

                {{-- Mensagens --}}
                <div class="card-body p-3 chat-messages" id="chatMessages">
                    @forelse($mensagens as $mensagem)
                        <div class="mb-3 {{ $mensagem->user_id === auth()->id() ? 'text-right' : '' }}" 
                             id="mensagem-{{ $mensagem->id }}">
                            
                            @if($mensagem->user_id === auth()->id())
                                {{-- Mensagem do usuário atual --}}
                                <div class="d-inline-block message-wrapper">
                                    <div class="bg-primary text-white rounded p-3 position-relative message-bubble">
                                        @if($mensagem->tipo === 'imagem' && $mensagem->arquivo_url)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $mensagem->arquivo_url) }}" 
                                                     class="img-fluid rounded cursor-pointer message-image" 
                                                     alt="Imagem"
                                                     loading="lazy"
                                                     onclick="abrirImagemModal('{{ asset('storage/' . $mensagem->arquivo_url) }}', '{{ $mensagem->arquivo_nome ?? 'Imagem' }}')">
                                            </div>
                                        @endif
                                        
                                        @if($mensagem->conteudo)
                                            <div class="message-content">
                                                {!! nl2br(e($mensagem->conteudo)) !!}
                                            </div>
                                        @endif
                                        
                                        @if($mensagem->editada)
                                            <small class="d-block mt-1 opacity-75">
                                                <i class="fas fa-edit"></i> Editada
                                            </small>
                                        @endif
                                        
                                        {{-- Menu de ações --}}
                                        <div class="dropdown position-absolute message-actions">
                                            <button class="btn btn-sm btn-link text-white p-0" 
                                                    data-toggle="dropdown"
                                                    aria-label="Opções">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if($mensagem->tipo === 'texto')
                                                    <button class="dropdown-item" 
                                                            onclick="editarMensagem({{ $mensagem->id }}, '{{ addslashes($mensagem->conteudo) }}')">
                                                        <i class="fas fa-edit"></i> Editar
                                                    </button>
                                                @endif
                                                <button type="button" 
                                                        class="dropdown-item text-danger"
                                                        onclick="confirmarEliminacao({{ $mensagem->id }}, '{{ $mensagem->tipo }}', '{{ addslashes($mensagem->conteudo ?? '') }}', '{{ $mensagem->arquivo_url ?? '' }}')">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        {{ $mensagem->created_at->format('H:i') }}
                                    </small>
                                </div>
                            @else
                                {{-- Mensagem de outro usuário --}}
                                <div class="d-inline-block message-wrapper">
                                    <div class="d-flex align-items-start">
                                        <div class="avatar-small bg-light rounded-circle d-flex align-items-center justify-content-center mr-2" 
                                             title="{{ $mensagem->user->name }}">
                                            <strong class="text-{{ $grupo->cor }}">
                                                {{ strtoupper(substr($mensagem->user->name, 0, 1)) }}
                                            </strong>
                                        </div>
                                        <div>
                                            <div class="bg-light rounded p-3 message-bubble">
                                                <strong class="d-block mb-1 text-{{ $grupo->cor }}">
                                                    {{ $mensagem->user->name }}
                                                </strong>
                                                
                                                @if($mensagem->tipo === 'imagem' && $mensagem->arquivo_url)
                                                    <div class="mb-2">
                                                        <img src="{{ asset('storage/' . $mensagem->arquivo_url) }}" 
                                                             class="img-fluid rounded cursor-pointer message-image" 
                                                             alt="Imagem"
                                                             loading="lazy"
                                                             onclick="abrirImagemModal('{{ asset('storage/' . $mensagem->arquivo_url) }}', '{{ $mensagem->arquivo_nome ?? 'Imagem' }}')">
                                                    </div>
                                                @endif
                                                
                                                @if($mensagem->conteudo)
                                                    <div class="message-content">
                                                        {!! nl2br(e($mensagem->conteudo)) !!}
                                                    </div>
                                                @endif
                                                
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

                {{-- Caixa de Envio --}}
                <div class="card-footer bg-light">
                    {{-- Preview da imagem --}}
                    <div id="imagemPreview" class="mb-2 d-none">
                        <div class="position-relative d-inline-block">
                            <img id="imagemPreviewImg" src="" class="img-thumbnail" alt="Preview" style="max-height: 100px;">
                            <button type="button" 
                                    class="btn btn-sm btn-danger position-absolute btn-remove-preview"
                                    onclick="removerImagemPreview()"
                                    aria-label="Remover imagem">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <form id="formEnviarMensagem" onsubmit="enviarMensagem(event)" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group align-items-start">
                            {{-- Botão de anexos --}}
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary btn-attachment dropdown-toggle" 
                                        type="button" 
                                        data-toggle="dropdown" 
                                        aria-label="Anexar">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" 
                                            type="button" 
                                            onclick="document.getElementById('imagemInput').click()">
                                        <i class="fas fa-image text-primary"></i> Enviar Imagem
                                    </button>
                                    <button class="dropdown-item" 
                                            type="button" 
                                            onclick="toggleEmojiPicker()">
                                        <i class="fas fa-smile text-warning"></i> Emoji
                                    </button>
                                </div>
                                <input type="file" 
                                       id="imagemInput" 
                                       name="imagem" 
                                       accept="image/jpeg,image/png,image/gif,image/webp" 
                                       class="d-none"
                                       onchange="mostrarPreviewImagem(event)">
                            </div>
                            
                            <textarea class="form-control message-textarea" 
                                      id="mensagemTexto" 
                                      name="conteudo" 
                                      rows="2" 
                                      placeholder="Digite sua mensagem..." 
                                      maxlength="5000"
                                      style="resize: none;"
                                      aria-label="Mensagem"></textarea>
                            
                            <div class="input-group-append">
                                <button type="submit" 
                                        class="btn btn-{{ $grupo->cor }}" 
                                        id="btnEnviar"
                                        aria-label="Enviar mensagem">
                                    <i class="fas fa-paper-plane"></i>
                                    <span class="d-none d-md-inline ml-1">Enviar</span>
                                </button>
                            </div>
                        </div>
                        
                        {{-- Picker de Emojis --}}
                        <div id="emojiPicker" class="emoji-picker mt-2 p-2 bg-white border rounded shadow-sm d-none">
                            <div class="emoji-grid">
                                @php
                                    $emojis = [
                                        '😀', '😃', '😄', '😁', '😅', '😂', '🤣', '😊',
                                        '😇', '🙂', '😉', '😌', '😍', '🥰', '😘', '😗',
                                        '😙', '😚', '😋', '😛', '😝', '😜', '🤪', '🤨',
                                        '🧐', '🤓', '😎', '🤩', '🥳', '😏', '😒', '😞',
                                        '😔', '😟', '😕', '😢', '😭', '😤', '😠', '😡',
                                        '🤬', '😱', '😨', '😰', '😥', '😓', '🤗', '🤔',
                                        '🤭', '🤫', '👍', '👎', '👏', '🙌', '👐', '🤝',
                                        '🙏', '❤️', '💙', '💚', '💛', '🧡', '💜', '🖤',
                                        '💔', '✨', '💯', '🔥', '⭐', '🎉', '🎊'
                                    ];
                                @endphp
                                @foreach($emojis as $emoji)
                                    <span class="emoji-item" 
                                          onclick="inserirEmoji('{{ $emoji }}')"
                                          role="button"
                                          tabindex="0">{{ $emoji }}</span>
                                @endforeach
                            </div>
                        </div>
                        
                        <small class="text-muted d-block mt-2">
                            <span id="charCount">0</span>/5000 caracteres
                        </small>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de Informações --}}
<div class="modal fade" id="infoGrupo" tabindex="-1" role="dialog" aria-labelledby="infoGrupoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-{{ $grupo->cor }} text-white">
                <h5 class="modal-title" id="infoGrupoLabel">
                    <i class="fas fa-{{ $grupo->icone }}"></i>
                    {{ $grupo->nome }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
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
                                <div class="avatar bg-{{ $grupo->cor }} text-white rounded-circle d-flex align-items-center justify-content-center mr-3">
                                    <strong>{{ strtoupper(substr($membro->name, 0, 1)) }}</strong>
                                </div>
                                <div>
                                    <strong>{{ $membro->name }}</strong>
                                    @if($membro->id === auth()->id())
                                        <span class="badge badge-success ml-1">Você</span>
                                    @endif
                                    @if($membro->role === 'admin')
                                        <span class="badge badge-danger ml-1">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal de Edição --}}
<div class="modal fade" id="modalEditarMensagem" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">
                    <i class="fas fa-edit"></i> Editar Mensagem
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditarMensagem" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editarConteudo">Mensagem</label>
                        <textarea class="form-control" 
                                  id="editarConteudo" 
                                  name="conteudo" 
                                  rows="5" 
                                  required 
                                  maxlength="5000"
                                  aria-label="Conteúdo da mensagem"></textarea>
                        <small class="form-text text-muted">
                            <span id="editCharCount">0</span>/5000 caracteres
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal de Confirmação de Eliminação --}}
<div class="modal fade" id="modalEliminarMensagem" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalEliminarLabel">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Confirmar Eliminação
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Atenção!</strong> Esta ação não pode ser desfeita.
                </div>
                
                <p>Tem certeza que deseja eliminar esta mensagem?</p>
                
                {{-- Preview da mensagem a ser eliminada --}}
                <div class="card bg-light" id="previewMensagemEliminar">
                    <div class="card-body">
                        <div id="previewImagemEliminar" class="mb-2 d-none">
                            <img id="imagemEliminar" src="" class="img-fluid rounded" style="max-height: 200px;" alt="Imagem">
                        </div>
                        <div id="previewTextoEliminar"></div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <form id="formEliminarMensagem" method="POST" action="" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Sim, Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal para visualizar imagem --}}
<div class="modal fade" id="modalVisualizarImagem" tabindex="-1" role="dialog" aria-labelledby="modalImagemLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content image-modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="modalImagemLabel">
                    <i class="fas fa-image"></i>
                    <span id="nomeImagemModal"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="imagemModalSrc" src="" class="img-fluid rounded" alt="Imagem" style="max-height: 70vh;">
            </div>
        </div>
    </div>
</div>

<style>
/* ========================================
   ESTILOS DO CHAT
   ======================================== */

/* Sidebar */
.sidebar-chat {
    height: calc(100vh - 100px);
    overflow-y: auto;
    border-right: 1px solid #dee2e6;
}

.avatar {
    width: 40px;
    height: 40px;
    font-size: 1rem;
    flex-shrink: 0;
}

.avatar-small {
    width: 35px;
    height: 35px;
    min-width: 35px;
    font-size: 0.9rem;
    flex-shrink: 0;
}

/* Container do Chat */
.chat-container {
    height: calc(100vh - 100px);
    display: flex;
    flex-direction: column;
}

.chat-messages {
    height: calc(100vh - 280px);
    overflow-y: auto;
    overflow-x: hidden;
    scroll-behavior: smooth;
}

/* Mensagens */
.message-wrapper {
    max-width: 70%;
    display: inline-block;
}

.message-bubble {
    position: relative;
    max-width: 100%;
    word-wrap: break-word;
}

.message-content {
    word-wrap: break-word;
    word-break: break-word;
    white-space: pre-wrap;
    line-height: 1.5;
    max-width: 100%;
}

.message-image {
    max-height: 300px;
    max-width: 100%;
    cursor: pointer;
    transition: transform 0.2s;
}

.message-image:hover {
    transform: scale(1.02);
}

.message-actions {
    top: 5px;
    right: 5px;
}

/* Input Area */
.input-group {
    display: flex;
    flex-wrap: nowrap;
}

.input-group-prepend {
    margin-right: 8px;
}

.input-group-append {
    margin-left: 8px;
}

.btn-attachment {
    border-radius: 50% !important;
    width: 42px;
    height: 42px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #ced4da;
    transition: all 0.2s;
    flex-shrink: 0;
}

.btn-attachment:hover {
    background-color: #f8f9fa;
    border-color: #adb5bd;
}

.btn-attachment i {
    font-size: 18px;
}

.message-textarea {
    resize: none;
    flex: 1;
}

.input-group-append .btn {
    height: 42px;
    border-radius: 0.25rem !important;
}

/* Preview de Imagem */
.btn-remove-preview {
    top: -10px;
    right: -10px;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    padding: 0;
}

/* Emoji Picker */
.emoji-picker {
    max-width: 100%;
}

.emoji-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(40px, 1fr));
    gap: 5px;
    max-height: 200px;
    overflow-y: auto;
}

.emoji-item {
    font-size: 24px;
    cursor: pointer;
    text-align: center;
    padding: 5px;
    border-radius: 5px;
    transition: all 0.2s;
    user-select: none;
}

.emoji-item:hover {
    background-color: #e9ecef;
    transform: scale(1.2);
}

/* Scrollbars */
.chat-messages::-webkit-scrollbar,
.emoji-grid::-webkit-scrollbar,
.sidebar-chat::-webkit-scrollbar {
    width: 8px;
}

.chat-messages::-webkit-scrollbar-track,
.emoji-grid::-webkit-scrollbar-track,
.sidebar-chat::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.chat-messages::-webkit-scrollbar-thumb,
.emoji-grid::-webkit-scrollbar-thumb,
.sidebar-chat::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.chat-messages::-webkit-scrollbar-thumb:hover,
.emoji-grid::-webkit-scrollbar-thumb:hover,
.sidebar-chat::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Modal de Imagem */
.image-modal-content {
    background: rgba(0, 0, 0, 0.9) !important;
    border: none;
}

/* Utilitários */
.cursor-pointer {
    cursor: pointer;
}

.opacity-50 {
    opacity: 0.5;
}

.opacity-75 {
    opacity: 0.75;
}

/* Responsivo */
@media (max-width: 768px) {
    .message-wrapper {
        max-width: 85%;
    }
    
    .chat-container {
        height: calc(100vh - 60px);
    }
    
    .chat-messages {
        height: calc(100vh - 200px);
    }
}
</style>

<script>
'use strict';

const ChatApp = {
    grupoId: {{ $grupo->id }},
    userId: {{ auth()->id() }},
    ultimaMensagemId: {{ $mensagens->last()->id ?? 0 }},
    grupoCor: '{{ $grupo->cor }}',
    autoScrollEnabled: true,
    pollingInterval: null,
    
    init() {
        this.attachEventListeners();
        this.scrollToBottom();
        this.startPolling();
    },
    
    attachEventListeners() {
        const textarea = document.getElementById('mensagemTexto');
        const charCount = document.getElementById('charCount');
        
        if (textarea) {
            textarea.addEventListener('input', () => {
                if (charCount) charCount.textContent = textarea.value.length;
            });
            
            textarea.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    document.getElementById('formEnviarMensagem').dispatchEvent(new Event('submit'));
                }
            });
        }
        
        const editTextarea = document.getElementById('editarConteudo');
        const editCount = document.getElementById('editCharCount');
        
        if (editTextarea && editCount) {
            editTextarea.addEventListener('input', () => {
                editCount.textContent = editTextarea.value.length;
            });
        }
        
        const chatMessages = document.getElementById('chatMessages');
        if (chatMessages) {
            chatMessages.addEventListener('scroll', () => {
                this.autoScrollEnabled = this.isAtBottom();
            });
        }
    },
    
    isAtBottom() {
        const el = document.getElementById('chatMessages');
        return el.scrollHeight - el.clientHeight <= el.scrollTop + 100;
    },
    
    scrollToBottom() {
        const el = document.getElementById('chatMessages');
        if (el) el.scrollTop = el.scrollHeight;
    },
    
    startPolling() {
        this.pollingInterval = setInterval(() => {
            carregarNovasMensagens();
        }, 3000);
    }
};

// Editar mensagem (MODAL)
function editarMensagem(id, conteudo) {
    document.getElementById('editarConteudo').value = conteudo;
    document.getElementById('editCharCount').textContent = conteudo.length;
    document.getElementById('formEditarMensagem').action = '/mensagens/' + id;
    $('#modalEditarMensagem').modal('show');
}

// Confirmar eliminação (MODAL)
function confirmarEliminacao(id, tipo, conteudo, arquivoUrl) {
    // Configurar form action
    document.getElementById('formEliminarMensagem').action = '/mensagens/' + id;
    
    // Mostrar preview
    const previewTexto = document.getElementById('previewTextoEliminar');
    const previewImagem = document.getElementById('previewImagemEliminar');
    const imagemEliminar = document.getElementById('imagemEliminar');
    
    if (tipo === 'imagem' && arquivoUrl) {
        previewImagem.classList.remove('d-none');
        imagemEliminar.src = '/storage/' + arquivoUrl;
    } else {
        previewImagem.classList.add('d-none');
    }
    
    if (conteudo) {
        previewTexto.innerHTML = conteudo.replace(/\n/g, '<br>');
    } else {
        previewTexto.innerHTML = '<em class="text-muted">Sem texto</em>';
    }
    
    // Abrir modal
    $('#modalEliminarMensagem').modal('show');
}

function mostrarPreviewImagem(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!allowedTypes.includes(file.type)) {
        alert('Tipo de imagem inválido. Use JPEG, PNG, GIF ou WebP.');
        document.getElementById('imagemInput').value = '';
        return;
    }
    
    if (file.size > 5 * 1024 * 1024) {
        alert('A imagem é muito grande. Tamanho máximo: 5MB');
        document.getElementById('imagemInput').value = '';
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('imagemPreviewImg').src = e.target.result;
        document.getElementById('imagemPreview').classList.remove('d-none');
    };
    reader.readAsDataURL(file);
}

function removerImagemPreview() {
    document.getElementById('imagemInput').value = '';
    document.getElementById('imagemPreview').classList.add('d-none');
    document.getElementById('imagemPreviewImg').src = '';
}

function toggleEmojiPicker() {
    document.getElementById('emojiPicker').classList.toggle('d-none');
}

function inserirEmoji(emoji) {
    const textarea = document.getElementById('mensagemTexto');
    if (!textarea) return;
    
    const cursorPos = textarea.selectionStart;
    const textBefore = textarea.value.substring(0, cursorPos);
    const textAfter = textarea.value.substring(cursorPos);
    
    textarea.value = textBefore + emoji + textAfter;
    
    const newPos = cursorPos + emoji.length;
    textarea.setSelectionRange(newPos, newPos);
    textarea.focus();
    
    document.getElementById('charCount').textContent = textarea.value.length;
    document.getElementById('emojiPicker').classList.add('d-none');
}

async function enviarMensagem(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const btnEnviar = document.getElementById('btnEnviar');
    const textarea = document.getElementById('mensagemTexto');
    
    if (!textarea.value.trim() && !document.getElementById('imagemInput').files[0]) {
        alert('Por favor, digite uma mensagem ou selecione uma imagem.');
        return;
    }
    
    btnEnviar.disabled = true;
    btnEnviar.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    try {
        const response = await fetch('{{ route('mensagens.store', $grupo) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            textarea.value = '';
            document.getElementById('charCount').textContent = '0';
            removerImagemPreview();
            carregarNovasMensagens();
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro ao enviar mensagem. Por favor, tente novamente.');
    } finally {
        btnEnviar.disabled = false;
        btnEnviar.innerHTML = '<i class="fas fa-paper-plane"></i> <span class="d-none d-md-inline ml-1">Enviar</span>';
    }
}

async function carregarNovasMensagens() {
    try {
        const response = await fetch(`/mensagens/{{ $grupo->id }}/carregar-novas?ultima_mensagem_id=${ChatApp.ultimaMensagemId}`);
        const mensagens = await response.json();
        
        if (mensagens && mensagens.length > 0) {
            mensagens.forEach(msg => {
                adicionarMensagemAoChat(msg);
                ChatApp.ultimaMensagemId = msg.id;
            });
            
            if (ChatApp.autoScrollEnabled) {
                ChatApp.scrollToBottom();
            }
        }
    } catch (error) {
        console.error('Erro ao carregar mensagens:', error);
    }
}

function adicionarMensagemAoChat(mensagem) {
    const chatMessages = document.getElementById('chatMessages');
    const isOwn = mensagem.user_id === {{ auth()->id() }};
    
    if (document.getElementById(`mensagem-${mensagem.id}`)) return;
    
    const div = document.createElement('div');
    div.className = `mb-3 ${isOwn ? 'text-right' : ''}`;
    div.id = `mensagem-${mensagem.id}`;
    
    const time = new Date(mensagem.created_at).toLocaleTimeString('pt-PT', {
        hour: '2-digit',
        minute: '2-digit'
    });
    
    const escapeHtml = (text) => {
        const map = {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'};
        return text.replace(/[&<>"']/g, m => map[m]);
    };
    
    const nl2br = (text) => text.replace(/\n/g, '<br>');
    
    let imagemHtml = '';
    if (mensagem.tipo === 'imagem' && mensagem.arquivo_url) {
        const imagemUrl = `/storage/${mensagem.arquivo_url}`;
        imagemHtml = `
            <div class="mb-2">
                <img src="${imagemUrl}" 
                     class="img-fluid rounded cursor-pointer message-image" 
                     loading="lazy"
                     onclick="abrirImagemModal('${imagemUrl}', '${mensagem.arquivo_nome || 'Imagem'}')"
                     alt="Imagem">
            </div>
        `;
    }
    
    let conteudoHtml = mensagem.conteudo ? 
        `<div class="message-content">${nl2br(escapeHtml(mensagem.conteudo))}</div>` : '';
    
    if (isOwn) {
        // Menu de ações para mensagens próprias
        let menuAcoes = '';
        if (mensagem.tipo === 'texto') {
            menuAcoes = `
                <div class="dropdown position-absolute message-actions">
                    <button class="btn btn-sm btn-link text-white p-0" 
                            data-toggle="dropdown"
                            aria-label="Opções">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <button class="dropdown-item" 
                                onclick="editarMensagem(${mensagem.id}, '${mensagem.conteudo.replace(/'/g, "\\'")}')">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button type="button" 
                                class="dropdown-item text-danger"
                                onclick="confirmarEliminacao(${mensagem.id}, '${mensagem.tipo}', '${mensagem.conteudo.replace(/'/g, "\\'")}', '${mensagem.arquivo_url || ''}')">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </div>
                </div>
            `;
        } else {
            // Apenas eliminar para imagens
            menuAcoes = `
                <div class="dropdown position-absolute message-actions">
                    <button class="btn btn-sm btn-link text-white p-0" 
                            data-toggle="dropdown"
                            aria-label="Opções">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <button type="button" 
                                class="dropdown-item text-danger"
                                onclick="confirmarEliminacao(${mensagem.id}, '${mensagem.tipo}', '', '${mensagem.arquivo_url || ''}')">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </div>
                </div>
            `;
        }
        
        div.innerHTML = `
            <div class="d-inline-block message-wrapper">
                <div class="bg-primary text-white rounded p-3 position-relative message-bubble">
                    ${imagemHtml}
                    ${conteudoHtml}
                    ${menuAcoes}
                </div>
                <small class="text-muted d-block mt-1">${time}</small>
            </div>
        `;
    } else {
        const inicial = mensagem.user.name.charAt(0).toUpperCase();
        div.innerHTML = `
            <div class="d-inline-block message-wrapper">
                <div class="d-flex align-items-start">
                    <div class="avatar-small bg-light rounded-circle d-flex align-items-center justify-content-center mr-2">
                        <strong class="text-{{ $grupo->cor }}">${inicial}</strong>
                    </div>
                    <div>
                        <div class="bg-light rounded p-3 message-bubble">
                            <strong class="d-block mb-1 text-{{ $grupo->cor }}">${escapeHtml(mensagem.user.name)}</strong>
                            ${imagemHtml}
                            ${conteudoHtml}
                        </div>
                        <small class="text-muted d-block mt-1">${time}</small>
                    </div>
                </div>
            </div>
        `;
    }
    
    chatMessages.appendChild(div);
}

function abrirImagemModal(url, nome) {
    document.getElementById('imagemModalSrc').src = url;
    document.getElementById('nomeImagemModal').textContent = nome;
    $('#modalVisualizarImagem').modal('show');
}

document.addEventListener('DOMContentLoaded', function() {
    ChatApp.init();
});
</script>
@endsection