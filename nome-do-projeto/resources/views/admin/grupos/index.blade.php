@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">
            <i class="fas fa-users"></i>
            Gestão de Grupos de Mensagens
        </h1>
        <a href="{{ route('admin.grupos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Criar Novo Grupo
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">
        @forelse($grupos as $grupo)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-{{ $grupo->cor }} text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-{{ $grupo->icone }}"></i>
                                {{ $grupo->nome }}
                            </h5>
                            <span class="badge badge-light">
                                {{ $grupo->membros_count }} membros
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">
                            {{ $grupo->descricao ?? 'Sem descrição' }}
                        </p>
                        
                        <div class="mb-3">
                            <strong class="d-block mb-2">Status:</strong>
                            <span class="badge badge-{{ $grupo->ativo ? 'success' : 'secondary' }}">
                                {{ $grupo->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <strong class="d-block mb-2">Criado por:</strong>
                            <small class="text-muted">{{ $grupo->criador->name }}</small>
                        </div>

                        @if($grupo->ultimaMensagem)
                            <div class="mb-3">
                                <strong class="d-block mb-2">Última mensagem:</strong>
                                <small class="text-muted">
                                    {{ $grupo->ultimaMensagem->user->name }}:
                                    {{ Str::limit($grupo->ultimaMensagem->conteudo, 50) }}
                                </small>
                                <br>
                                <small class="text-muted">
                                    {{ $grupo->ultimaMensagem->created_at->diffForHumans() }}
                                </small>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-light">
                        <div class="btn-group btn-group-sm w-100">
                            <a href="{{ route('admin.grupos.edit', $grupo) }}" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <form action="{{ route('admin.grupos.toggle-ativo', $grupo) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="btn btn-outline-{{ $grupo->ativo ? 'warning' : 'success' }}">
                                    <i class="fas fa-{{ $grupo->ativo ? 'pause' : 'play' }}"></i>
                                    {{ $grupo->ativo ? 'Desativar' : 'Ativar' }}
                                </button>
                            </form>
                            <button type="button" 
                                    class="btn btn-outline-danger"
                                    onclick="confirmarDelete({{ $grupo->id }})">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form de delete oculto -->
            <form id="delete-form-{{ $grupo->id }}" 
                  action="{{ route('admin.grupos.destroy', $grupo) }}" 
                  method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <h5>Nenhum grupo criado ainda</h5>
                    <p>Comece criando o primeiro grupo de mensagens!</p>
                    <a href="{{ route('admin.grupos.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Criar Primeiro Grupo
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center">
        {{ $grupos->links() }}
    </div>
</div>

<script>
function confirmarDelete(grupoId) {
    if (confirm('Tem certeza que deseja eliminar este grupo? Todas as mensagens serão perdidas!')) {
        document.getElementById('delete-form-' + grupoId).submit();
    }
}
</script>
@endsection