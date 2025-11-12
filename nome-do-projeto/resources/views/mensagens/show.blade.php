@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
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
                <!-- Cabeçalho -->
                <div class="card-header bg-{{ $grupo->cor }} text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                <i class="fas fa-{{ $grupo->icone }}"></i>
                                {{ $grupo->nome }}
                            </h5>
                            <small>{{ $grupo->membros->count() }} membros</small>
                        </div>
                        <button class="btn btn-sm btn-light" data-toggle="modal" data-target="#infoGrupo">
                            <i class="fas fa-info-circle"></i>
                        </button>
                    </div>
                </div>

                <!-- Mensagens -->
                <div class="card-body p-3" id="chatMessages" style="height: calc(100vh - 280px); overflow-y: auto;">
                    @forelse($mensagens as $mensagem)
                        <div class="mb-3 {{ $mensagem->user_id == auth()->id() ? 'text-right' : '' }}" 
                             id="mensagem-{{ $mensagem->id }}">
                            
                            @if($mensagem->user_id == auth()->id())
                                <!-- Mensagem do usuário atual -->
                                <div class="d-inline-block" style="max-width: 70%;">
                                    <div class="bg-primary text-white rounded p-3 position-relative">
                                        @if($mensagem->tipo === 'imagem')
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $mensagem->arquivo_url) }}" 
                                                     class="img-fluid rounded cursor-pointer mensagem-imagem" 
                                                     style="max-height: 300px;"
                                                     onclick="abrirImagemModal('{{ asset('storage/' . $mensagem->arquivo_url) }}', '{{ $mensagem->arquivo_nome }}')"
                                                     alt="Imagem">
                                            </div>
                                        @endif
                                        
                                        @if($mensagem->conteudo)
                                            <div class="message-content">
                                                {{ $mensagem->conteudo }}
                                            </div>
                                        @endif
                                        
                                        @if($mensagem->editada)
                                            <small class="d-block mt-1 opacity-75">
                                                <i class="fas fa-edit"></i> Editada
                                            </small>
                                        @endif
                                        
                                        <!-- Menu de ações -->
                                        <div class="dropdown position-absolute" style="top: 5px; right: 5px;">
                                            <button class="btn btn-sm btn-link text-white p-0" data-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if($mensagem->tipo === 'texto')
                                                    <button class="dropdown-item" 
                                                            onclick="editarMensagem({{ $mensagem->id }}, '{{ addslashes($mensagem->conteudo) }}')">
                                                        <i class="fas fa-edit"></i> Editar
                                                    </button>
                                                @endif
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
                                <!-- Mensagem de outro usuário -->
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
                                                
                                                @if($mensagem->tipo === 'imagem')
                                                    <div class="mb-2">
                                                        <img src="{{ asset('storage/' . $mensagem->arquivo_url) }}" 
                                                             class="img-fluid rounded cursor-pointer mensagem-imagem" 
                                                             style="max-height: 300px;"
                                                             onclick="abrirImagemModal('{{ asset('storage/' . $mensagem->arquivo_url) }}', '{{ $mensagem->arquivo_nome }}')"
                                                             alt="Imagem">
                                                    </div>
                                                @endif
                                                
                                                @if($mensagem->conteudo)
                                                    <div class="message-content">
                                                        {{ $mensagem->conteudo }}
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

                <!-- Caixa de Envio -->
                <div class="card-footer bg-light">
                    <!-- Preview da imagem -->
                    <div id="imagemPreview" class="mb-2" style="display: none;">
                        <div class="position-relative d-inline-block">
                            <img id="imagemPreviewImg" src="" class="img-thumbnail" style="max-height: 100px;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute" 
                                    style="top: -10px; right: -10px; border-radius: 50%; width: 30px; height: 30px; padding: 0;"
                                    onclick="removerImagemPreview()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <form id="formEnviarMensagem" onsubmit="enviarMensagem(event)" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <!-- Botão + com menu dropdown -->
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary dropdown-toggle" 
                                        type="button" 
                                        data-toggle="dropdown" 
                                        aria-haspopup="true" 
                                        aria-expanded="false"
                                        style="border-radius: 50%; width: 42px; height: 42px; padding: 0;">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" type="button" onclick="document.getElementById('imagemInput').click()">
                                        <i class="fas fa-image text-primary"></i> Enviar Imagem
                                    </button>
                                    <button class="dropdown-item" type="button" onclick="document.getElementById('emojiPicker').classList.toggle('d-none')">
                                        <i class="fas fa-smile text-warning"></i> Emoji
                                    </button>
                                </div>
                                <input type="file" 
                                       id="imagemInput" 
                                       name="imagem" 
                                       accept="image/*" 
                                       style="display: none;"
                                       onchange="mostrarPreviewImagem(event)">
                            </div>
                            
                            <textarea class="form-control" 
                                      id="mensagemTexto" 
                                      name="conteudo" 
                                      rows="2" 
                                      placeholder="Digite sua mensagem..." 
                                      maxlength="5000"
                                      style="resize: none;"></textarea>
                            
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-{{ $grupo->cor }}" id="btnEnviar">
                                    <i class="fas fa-paper-plane"></i>
                                    <span class="d-none d-md-inline ml-1">Enviar</span>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Picker de Emojis simples -->
                        <div id="emojiPicker" class="d-none mt-2 p-2 bg-white border rounded shadow-sm">
                            <div class="emoji-grid">
                                <span class="emoji-item" onclick="inserirEmoji('😀')">😀</span>
                                <span class="emoji-item" onclick="inserirEmoji('😃')">😃</span>
                                <span class="emoji-item" onclick="inserirEmoji('😄')">😄</span>
                                <span class="emoji-item" onclick="inserirEmoji('😁')">😁</span>
                                <span class="emoji-item" onclick="inserirEmoji('😅')">😅</span>
                                <span class="emoji-item" onclick="inserirEmoji('😂')">😂</span>
                                <span class="emoji-item" onclick="inserirEmoji('🤣')">🤣</span>
                                <span class="emoji-item" onclick="inserirEmoji('😊')">😊</span>
                                <span class="emoji-item" onclick="inserirEmoji('😇')">😇</span>
                                <span class="emoji-item" onclick="inserirEmoji('🙂')">🙂</span>
                                <span class="emoji-item" onclick="inserirEmoji('😉')">😉</span>
                                <span class="emoji-item" onclick="inserirEmoji('😌')">😌</span>
                                <span class="emoji-item" onclick="inserirEmoji('😍')">😍</span>
                                <span class="emoji-item" onclick="inserirEmoji('🥰')">🥰</span>
                                <span class="emoji-item" onclick="inserirEmoji('😘')">😘</span>
                                <span class="emoji-item" onclick="inserirEmoji('😗')">😗</span>
                                <span class="emoji-item" onclick="inserirEmoji('😙')">😙</span>
                                <span class="emoji-item" onclick="inserirEmoji('😚')">😚</span>
                                <span class="emoji-item" onclick="inserirEmoji('😋')">😋</span>
                                <span class="emoji-item" onclick="inserirEmoji('😛')">😛</span>
                                <span class="emoji-item" onclick="inserirEmoji('😝')">😝</span>
                                <span class="emoji-item" onclick="inserirEmoji('😜')">😜</span>
                                <span class="emoji-item" onclick="inserirEmoji('🤪')">🤪</span>
                                <span class="emoji-item" onclick="inserirEmoji('🤨')">🤨</span>
                                <span class="emoji-item" onclick="inserirEmoji('🧐')">🧐</span>
                                <span class="emoji-item" onclick="inserirEmoji('🤓')">🤓</span>
                                <span class="emoji-item" onclick="inserirEmoji('😎')">😎</span>
                                <span class="emoji-item" onclick="inserirEmoji('🤩')">🤩</span>
                                <span class="emoji-item" onclick="inserirEmoji('🥳')">🥳</span>
                                <span class="emoji-item" onclick="inserirEmoji('😏')">😏</span>
                                <span class="emoji-item" onclick="inserirEmoji('😒')">😒</span>
                                <span class="emoji-item" onclick="inserirEmoji('😞')">😞</span>
                                <span class="emoji-item" onclick="inserirEmoji('😔')">😔</span>
                                <span class="emoji-item" onclick="inserirEmoji('😟')">😟</span>
                                <span class="emoji-item" onclick="inserirEmoji('😕')">😕</span>
                                <span class="emoji-item" onclick="inserirEmoji('😢')">😢</span>
                                <span class="emoji-item" onclick="inserirEmoji('😭')">😭</span>
                                <span class="emoji-item" onclick="inserirEmoji('😤')">😤</span>
                                <span class="emoji-item" onclick="inserirEmoji('😠')">😠</span>
                                <span class="emoji-item" onclick="inserirEmoji('😡')">😡</span>
                                <span class="emoji-item" onclick="inserirEmoji('🤬')">🤬</span>
                                <span class="emoji-item" onclick="inserirEmoji('😱')">😱</span>
                                <span class="emoji-item" onclick="inserirEmoji('😨')">😨</span>
                                <span class="emoji-item" onclick="inserirEmoji('😰')">😰</span>
                                <span class="emoji-item" onclick="inserirEmoji('😥')">😥</span>
                                <span class="emoji-item" onclick="inserirEmoji('😓')">😓</span>
                                <span class="emoji-item" onclick="inserirEmoji('🤗')">🤗</span>
                                <span class="emoji-item" onclick="inserirEmoji('🤔')">🤔</span>
                                <span class="emoji-item" onclick="inserirEmoji('🤭')">🤭</span>
                                <span class="emoji-item" onclick="inserirEmoji('🤫')">🤫</span>
                                <span class="emoji-item" onclick="inserirEmoji('👍')">👍</span>
                                <span class="emoji-item" onclick="inserirEmoji('👎')">👎</span>
                                <span class="emoji-item" onclick="inserirEmoji('👏')">👏</span>
                                <span class="emoji-item" onclick="inserirEmoji('🙌')">🙌</span>
                                <span class="emoji-item" onclick="inserirEmoji('👐')">👐</span>
                                <span class="emoji-item" onclick="inserirEmoji('🤝')">🤝</span>
                                <span class="emoji-item" onclick="inserirEmoji('🙏')">🙏</span>
                                <span class="emoji-item" onclick="inserirEmoji('❤️')">❤️</span>
                                <span class="emoji-item" onclick="inserirEmoji('💙')">💙</span>
                                <span class="emoji-item" onclick="inserirEmoji('💚')">💚</span>
                                <span class="emoji-item" onclick="inserirEmoji('💛')">💛</span>
                                <span class="emoji-item" onclick="inserirEmoji('🧡')">🧡</span>
                                <span class="emoji-item" onclick="inserirEmoji('💜')">💜</span>
                                <span class="emoji-item" onclick="inserirEmoji('🖤')">🖤</span>
                                <span class="emoji-item" onclick="inserirEmoji('💔')">💔</span>
                                <span class="emoji-item" onclick="inserirEmoji('✨')">✨</span>
                                <span class="emoji-item" onclick="inserirEmoji('💯')">💯</span>
                                <span class="emoji-item" onclick="inserirEmoji('🔥')">🔥</span>
                                <span class="emoji-item" onclick="inserirEmoji('⭐')">⭐</span>
                                <span class="emoji-item" onclick="inserirEmoji('🎉')">🎉</span>
                                <span class="emoji-item" onclick="inserirEmoji('🎊')">🎊</span>
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

<!-- Modal de Informações -->
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

<!-- Modal de Edição -->
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
                        <textarea class="form-control" id="editarConteudo" name="conteudo" rows="5" required maxlength="5000"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para visualizar imagem em tamanho real -->
<div class="modal fade" id="modalVisualizarImagem" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="nomeImagemModal"></h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="imagemModalSrc" src="" class="img-fluid rounded" alt="Imagem">
            </div>
        </div>
    </div>
</div>

<script>
const grupoId = {{ $grupo->id }};
let ultimaMensagemId = {{ $mensagens->last()->id ?? 0 }};
let autoScrollEnabled = true;

// Contador de caracteres
document.getElementById('mensagemTexto').addEventListener('input', function() {
    document.getElementById('charCount').textContent = this.value.length;
});

// Preview da imagem antes de enviar
function mostrarPreviewImagem(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagemPreviewImg').src = e.target.result;
            document.getElementById('imagemPreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function removerImagemPreview() {
    document.getElementById('imagemInput').value = '';
    document.getElementById('imagemPreview').style.display = 'none';
}

// Inserir emoji no textarea
function inserirEmoji(emoji) {
    const textarea = document.getElementById('mensagemTexto');
    const cursorPos = textarea.selectionStart;
    const textBefore = textarea.value.substring(0, cursorPos);
    const textAfter = textarea.value.substring(cursorPos);
    
    textarea.value = textBefore + emoji + textAfter;
    textarea.focus();
    
    // Posicionar cursor após o emoji
    const newPos = cursorPos + emoji.length;
    textarea.setSelectionRange(newPos, newPos);
    
    // Atualizar contador
    document.getElementById('charCount').textContent = textarea.value.length;
}

// Enviar mensagem
function enviarMensagem(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const btnEnviar = document.getElementById('btnEnviar');
    const textarea = document.getElementById('mensagemTexto');
    
    // Validar se tem conteúdo ou imagem
    if (!textarea.value.trim() && !document.getElementById('imagemInput').files[0]) {
        alert('Por favor, digite uma mensagem ou selecione uma imagem.');
        return;
    }
    
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
            removerImagemPreview();
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
        .catch(error => console.error('Erro:', error));
}

// Adicionar mensagem ao chat
function adicionarMensagemAoChat(mensagem) {
    const chatMessages = document.getElementById('chatMessages');
    const isOwn = mensagem.user_id == {{ auth()->id() }};
    const div = document.createElement('div');
    div.className = `mb-3 ${isOwn ? 'text-right' : ''}`;
    div.id = `mensagem-${mensagem.id}`;
    
    const time = new Date(mensagem.created_at).toLocaleTimeString('pt-PT', {hour: '2-digit', minute: '2-digit'});
    
    let imagemHtml = '';
    if (mensagem.tipo === 'imagem' && mensagem.arquivo_url) {
        const imagemUrl = `/storage/${mensagem.arquivo_url}`;
        imagemHtml = `
            <div class="mb-2">
                <img src="${imagemUrl}" 
                     class="img-fluid rounded cursor-pointer mensagem-imagem" 
                     style="max-height: 300px;"
                     onclick="abrirImagemModal('${imagemUrl}', '${mensagem.arquivo_nome || 'Imagem'}')"
                     alt="Imagem">
            </div>
        `;
    }
    
    let conteudoHtml = mensagem.conteudo ? `<div class="message-content">${mensagem.conteudo}</div>` : '';
    
    if (isOwn) {
        div.innerHTML = `
            <div class="d-inline-block" style="max-width: 70%;">
                <div class="bg-primary text-white rounded p-3">
                    ${imagemHtml}
                    ${conteudoHtml}
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

// Abrir modal com imagem em tamanho real
function abrirImagemModal(url, nome) {
    document.getElementById('imagemModalSrc').src = url;
    document.getElementById('nomeImagemModal').textContent = nome;
    $('#modalVisualizarImagem').modal('show');
}

// Editar mensagem
function editarMensagem(id, conteudo) {
    document.getElementById('editarConteudo').value = conteudo;
    document.getElementById('formEditarMensagem').action = `/mensagens/${id}`;
    $('#modalEditarMensagem').modal('show');
}

// Scroll
function scrollToBottom() {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

document.getElementById('chatMessages').addEventListener('scroll', function() {
    const isAtBottom = this.scrollHeight - this.clientHeight <= this.scrollTop + 100;
    autoScrollEnabled = isAtBottom;
});

// Inicialização
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
    setInterval(carregarNovasMensagens, 3000);
    
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

.cursor-pointer {
    cursor: pointer;
}

.mensagem-imagem {
    transition: transform 0.2s;
}

.mensagem-imagem:hover {
    transform: scale(1.05);
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
    transition: background-color 0.2s;
}

.emoji-item:hover {
    background-color: #e9ecef;
    transform: scale(1.2);
}

#chatMessages::-webkit-scrollbar,
.emoji-grid::-webkit-scrollbar {
    width: 8px;
}

#chatMessages::-webkit-scrollbar-track,
.emoji-grid::-webkit-scrollbar-track {
    background: #f1f1f1;
}

#chatMessages::-webkit-scrollbar-thumb,
.emoji-grid::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

#chatMessages::-webkit-scrollbar-thumb:hover,
.emoji-grid::-webkit-scrollbar-thumb:hover {
    background: #555;
}

#modalVisualizarImagem .modal-content {
    background: rgba(0,0,0,0.9) !important;
}
/* Fix para o botão circular + */
.input-group-prepend .btn {
    border-radius: 50%;
    width: 42px;
    height: 42px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #ced4da;
    transition: all 0.2s;
    margin-top: 10px;
}

.input-group-prepend .btn:hover {
    background-color: #f8f9fa;
    border-color: #adb5bd;
}

.input-group-prepend .btn:focus {
    box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25);
    outline: none;
}

.input-group-prepend .btn i {
    font-size: 18px;
    line-height: 1;
}

.input-group {
    align-items: flex-start;
}

.input-group-prepend {
    margin-right: 8px;
}
.input-group-append {
    margin-left: 8px;
}

.input-group-append .btn {
    margin-top: 10px;
}
</style>
@endsection