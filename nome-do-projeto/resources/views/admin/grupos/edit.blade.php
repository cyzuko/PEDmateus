@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-edit"></i>
                        Editar Grupo: {{ $grupo->nome }}
                    </h4>
                </div>

                <form action="{{ route('admin.grupos.update', $grupo) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <!-- Nome do Grupo -->
                        <div class="form-group">
                            <label for="nome">
                                <strong>Nome do Grupo <span class="text-danger">*</span></strong>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nome') is-invalid @enderror" 
                                   id="nome" 
                                   name="nome" 
                                   value="{{ old('nome', $grupo->nome) }}" 
                                   required 
                                   maxlength="255"
                                   placeholder="Ex: Professores de Matemática">
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Descrição -->
                        <div class="form-group">
                            <label for="descricao">
                                <strong>Descrição</strong>
                            </label>
                            <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                      id="descricao" 
                                      name="descricao" 
                                      rows="3" 
                                      maxlength="1000"
                                      placeholder="Descrição opcional do grupo">{{ old('descricao', $grupo->descricao) }}</textarea>
                            @error('descricao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Ícone -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="icone">
                                        <strong>Ícone <span class="text-danger">*</span></strong>
                                    </label>
                                    <select class="form-control @error('icone') is-invalid @enderror" 
                                            id="icone" 
                                            name="icone" 
                                            required>
                                        <option value="users" {{ old('icone', $grupo->icone) == 'users' ? 'selected' : '' }}>
                                            👥 Grupo de Pessoas
                                        </option>
                                        <option value="graduation-cap" {{ old('icone', $grupo->icone) == 'graduation-cap' ? 'selected' : '' }}>
                                            🎓 Educação
                                        </option>
                                        <option value="book" {{ old('icone', $grupo->icone) == 'book' ? 'selected' : '' }}>
                                            📚 Livros
                                        </option>
                                        <option value="lightbulb" {{ old('icone', $grupo->icone) == 'lightbulb' ? 'selected' : '' }}>
                                            💡 Ideias
                                        </option>
                                        <option value="comments" {{ old('icone', $grupo->icone) == 'comments' ? 'selected' : '' }}>
                                            💬 Conversa
                                        </option>
                                        <option value="chalkboard-teacher" {{ old('icone', $grupo->icone) == 'chalkboard-teacher' ? 'selected' : '' }}>
                                            👨‍🏫 Professor
                                        </option>
                                        <option value="user-graduate" {{ old('icone', $grupo->icone) == 'user-graduate' ? 'selected' : '' }}>
                                            👨‍🎓 Aluno
                                        </option>
                                        <option value="project-diagram" {{ old('icone', $grupo->icone) == 'project-diagram' ? 'selected' : '' }}>
                                            🗂️ Projeto
                                        </option>
                                    </select>
                                    @error('icone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Cor -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cor">
                                        <strong>Cor <span class="text-danger">*</span></strong>
                                    </label>
                                    <select class="form-control @error('cor') is-invalid @enderror" 
                                            id="cor" 
                                            name="cor" 
                                            required>
                                        <option value="primary" {{ old('cor', $grupo->cor) == 'primary' ? 'selected' : '' }}>
                                            🔵 Azul
                                        </option>
                                        <option value="success" {{ old('cor', $grupo->cor) == 'success' ? 'selected' : '' }}>
                                            🟢 Verde
                                        </option>
                                        <option value="danger" {{ old('cor', $grupo->cor) == 'danger' ? 'selected' : '' }}>
                                            🔴 Vermelho
                                        </option>
                                        <option value="warning" {{ old('cor', $grupo->cor) == 'warning' ? 'selected' : '' }}>
                                            🟡 Amarelo
                                        </option>
                                        <option value="info" {{ old('cor', $grupo->cor) == 'info' ? 'selected' : '' }}>
                                            🔷 Ciano
                                        </option>
                                        <option value="secondary" {{ old('cor', $grupo->cor) == 'secondary' ? 'selected' : '' }}>
                                            ⚫ Cinza
                                        </option>
                                        <option value="dark" {{ old('cor', $grupo->cor) == 'dark' ? 'selected' : '' }}>
                                            ⚫ Preto
                                        </option>
                                    </select>
                                    @error('cor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Ativo/Inativo -->
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="ativo" 
                                       name="ativo" 
                                       value="1"
                                       {{ old('ativo', $grupo->ativo) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="ativo">
                                    <strong>Grupo Ativo</strong>
                                    <small class="d-block text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        Grupos inativos não podem receber novas mensagens
                                    </small>
                                </label>
                            </div>
                        </div>

                        <hr>

                        <!-- Informações do Grupo -->
                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong><i class="fas fa-user"></i> Criado por:</strong><br>
                                    <small>{{ $grupo->criador->name }}</small>
                                </div>
                                <div class="col-md-4">
                                    <strong><i class="fas fa-calendar"></i> Criado em:</strong><br>
                                    <small>{{ $grupo->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <div class="col-md-4">
                                    <strong><i class="fas fa-comments"></i> Total de mensagens:</strong><br>
                                    <small>{{ $grupo->mensagens->count() }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Seleção de Membros -->
                        <div class="form-group">
                            <label>
                                <strong>Membros do Grupo <span class="text-danger">*</span></strong>
                                <small class="text-muted">(Selecione pelo menos 2 utilizadores)</small>
                            </label>
                            
                            <div class="input-group mb-3">
                                <input type="text" 
                                       class="form-control" 
                                       id="searchUsuarios" 
                                       placeholder="🔍 Pesquisar utilizadores...">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="selectAll">
                                        Selecionar Todos
                                    </button>
                                    <button class="btn btn-outline-secondary" type="button" id="deselectAll">
                                        Limpar Seleção
                                    </button>
                                </div>
                            </div>

                            <div class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                                @foreach($usuarios as $usuario)
                                    <div class="custom-control custom-checkbox usuario-item mb-2">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="usuario-{{ $usuario->id }}" 
                                               name="membros[]" 
                                               value="{{ $usuario->id }}"
                                               {{ in_array($usuario->id, old('membros', $grupo->membros->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <label class="custom-control-label w-100" for="usuario-{{ $usuario->id }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $usuario->name }}</strong>
                                                    @if($usuario->id == auth()->id())
                                                        <span class="badge badge-success ml-1">Você</span>
                                                    @endif
                                                    @if($usuario->id == $grupo->criado_por)
                                                        <span class="badge badge-warning ml-1">
                                                            <i class="fas fa-crown"></i> Criador
                                                        </span>
                                                    @endif
                                                    <br>
                                                    <small class="text-muted">{{ $usuario->email }}</small>
                                                </div>
                                                <span class="badge badge-{{ $usuario->role === 'admin' ? 'danger' : ($usuario->role === 'professor' ? 'primary' : 'info') }}">
                                                    {{ $usuario->role === 'admin' ? 'Admin' : ($usuario->role === 'professor' ? 'Professor' : 'Aluno') }}
                                                </span>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle"></i>
                                <span id="selectedCount">0</span> utilizadores selecionados
                            </small>
                            
                            @error('membros')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Aviso sobre remoção de membros -->
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Atenção:</strong> Se remover membros que já participaram do grupo, eles perderão acesso às mensagens anteriores.
                        </div>
                    </div>

                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.grupos.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Atualizar Grupo
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchUsuarios');
    const usuarioItems = document.querySelectorAll('.usuario-item');
    const checkboxes = document.querySelectorAll('input[name="membros[]"]');
    const selectedCount = document.getElementById('selectedCount');
    const selectAllBtn = document.getElementById('selectAll');
    const deselectAllBtn = document.getElementById('deselectAll');

    // Atualizar contagem inicial
    updateCount();

    // Pesquisa de utilizadores
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        usuarioItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Selecionar todos
    selectAllBtn.addEventListener('click', function() {
        checkboxes.forEach(cb => {
            if (cb.closest('.usuario-item').style.display !== 'none') {
                cb.checked = true;
            }
        });
        updateCount();
    });

    // Limpar seleção
    deselectAllBtn.addEventListener('click', function() {
        checkboxes.forEach(cb => cb.checked = false);
        updateCount();
    });

    // Atualizar contagem ao mudar seleção
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateCount);
    });

    function updateCount() {
        const count = document.querySelectorAll('input[name="membros[]"]:checked').length;
        selectedCount.textContent = count;
        
        if (count < 2) {
            selectedCount.classList.add('text-danger');
            selectedCount.classList.remove('text-success');
        } else {
            selectedCount.classList.remove('text-danger');
            selectedCount.classList.add('text-success');
        }
    }

    // Confirmação antes de remover membros ativos
    const form = document.querySelector('form');
    const membrosAtuais = @json($grupo->membros->pluck('id')->toArray());
    
    form.addEventListener('submit', function(e) {
        const membrosSelecionados = Array.from(document.querySelectorAll('input[name="membros[]"]:checked'))
            .map(cb => parseInt(cb.value));
        
        const membrosRemovidos = membrosAtuais.filter(id => !membrosSelecionados.includes(id));
        
        if (membrosRemovidos.length > 0) {
            const confirmar = confirm(
                `Atenção! Você está prestes a remover ${membrosRemovidos.length} membro(s) do grupo.\n\n` +
                `Eles perderão acesso às mensagens e não poderão mais participar da conversa.\n\n` +
                `Deseja continuar?`
            );
            
            if (!confirmar) {
                e.preventDefault();
                return false;
            }
        }
        
        // Validar mínimo de 2 membros
        if (membrosSelecionados.length < 2) {
            e.preventDefault();
            alert('O grupo deve ter pelo menos 2 membros selecionados!');
            return false;
        }
    });
});
</script>

<style>
.usuario-item {
    transition: background-color 0.2s ease;
}

.usuario-item:hover {
    background-color: #f8f9fa;
}

.custom-control-label {
    cursor: pointer;
}

.alert-warning {
    border-left: 4px solid #ffc107;
}

.alert-info {
    border-left: 4px solid #17a2b8;
}
</style>
@endsection