@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">

                <!-- 🔲 Cabeçalho -->
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-file-invoice-dollar me-2"></i>
                            Gestão de Faturas
                        </h4>
                    </div>
                </div>

                <div class="card-body">

                    <!-- Barra de Ações -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="d-flex gap-2">
                                <a href="{{ route('faturas.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus me-1"></i>
                                    Nova Fatura
                                </a>
                                <a href="{{ route('faturas.exportPdf') }}" target="_blank" class="btn btn-danger">
                                    <i class="fas fa-file-pdf me-1"></i>
                                    Exportar PDF
                                </a>
                            </div>
                        </div>

                    <!-- ✅ Mensagens -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- 🧾 Tabela -->
                    @if($faturas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead style="background-color:rgb(42, 143, 225); color: white;">
                                    <tr>
                                        @php
                                            // Colunas com ícones e chaves de ordenação
                                            $columns = [
                                                'fornecedor' => ['label' => 'Fornecedor', 'icon' => 'building'],
                                                'nif' => ['label' => 'NIF', 'icon' => 'id-card'],
                                                'data' => ['label' => 'Data', 'icon' => 'calendar'],
                                                'valor' => ['label' => 'Valor', 'icon' => 'euro-sign'],
                                                'imagem' => ['label' => 'Imagem', 'icon' => 'image'],
                                                'acoes' => ['label' => 'Ações', 'icon' => 'cogs'],
                                            ];
                                        @endphp
                                    @foreach($columns as $key => $col)
                                        @if($key === 'fornecedor' || $key === 'data' || $key === 'valor')
                                            @php
                                                $currentSort = request('sort');
                                                $ascKey = $key . '_asc';
                                                $descKey = $key . '_desc';
                                                $dir = null;
                                                if ($currentSort === $ascKey) $dir = 'asc';
                                                elseif ($currentSort === $descKey) $dir = 'desc';
                                                $nextDir = $dir === 'asc' ? 'desc' : 'asc';
                                            @endphp
                                            <th class="text-center" style="cursor:pointer;">
                                                <a href="{{ request()->fullUrlWithQuery(['sort' => $key . '_' . $nextDir]) }}"
                                                class="text-white text-decoration-none d-flex align-items-center justify-content-center gap-1">
                                                    <i class="fas fa-{{ $col['icon'] }}"></i>
                                                    <span>{{ $col['label'] }}</span>
                                                    {{-- Setas sempre visíveis --}}
                                                    <i class="fas fa-sort-up" style="opacity: {{ $dir === 'asc' ? '1' : '0.3' }}; font-size: 0.7em;"></i>
                                                    <i class="fas fa-sort-down" style="opacity: {{ $dir === 'desc' ? '1' : '0.3' }}; font-size: 0.7em;"></i>
                                                </a>
                                            </th>
                                        @elseif($key === 'acoes')
                                            <th class="text-center">{{ $col['label'] }}</th>
                                        @else
                                            {{-- NIF e Imagem: só texto e ícone, sem link e setas --}}
                                            <th class="text-center" style="cursor: default;">
                                                <i class="fas fa-{{ $col['icon'] }}"></i>
                                                <span>{{ $col['label'] }}</span>
                                            </th>
                                        @endif
                                    @endforeach

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($faturas as $fatura)
                                        <tr>
                                            <td class="align-middle">
                                                <div class="fw-bold">{{ $fatura->fornecedor }}</div>
                                            </td>
                                            <td class="text-center align-middle">
                                                @if($fatura->nif)
                                                    <span class="badge bg-info">{{ $fatura->nif }}</span>
                                                @else
                                                    <span class="text-muted"><i class="fas fa-minus"></i></span>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge bg-secondary">
                                                    {{ \Carbon\Carbon::parse($fatura->data)->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="fw-bold text-success">
                                                    €{{ number_format($fatura->valor, 2, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="text-center align-middle">
                                                @if($fatura->imagem)
                                                    <a href="{{ asset('storage/' . $fatura->imagem) }}" target="_blank" class="d-inline-block">
                                                        <img src="{{ asset('storage/' . $fatura->imagem) }}"
                                                             alt="Imagem da Fatura"
                                                             class="img-thumbnail shadow-sm"
                                                             style="width: 100px; height: 70px; object-fit: cover;">
                                                    </a>
                                                @else
                                                    <span class="text-muted"><i class="fas fa-image-slash"></i></span>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('faturas.show', $fatura->id) }}"
                                                       class="btn btn-sm btn-outline-info"
                                                       title="Ver Detalhes">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('faturas.edit', $fatura->id) }}"
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('faturas.destroy', $fatura->id) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('⚠️ Tem certeza que deseja remover esta fatura?\n\nEsta ação não pode ser desfeita.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-danger"
                                                                title="Remover">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- 📄 Paginação -->
                        @if(method_exists($faturas, 'links'))
                            <div class="d-flex justify-content-center mt-4">
                                {{ $faturas->appends(request()->except('page'))->links() }}
                            </div>
                        @endif

                        <!-- Resumo -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                     <div class="row text-center justify-content-center">
                                        <div class="col-md-3 d-flex flex-column align-items-center justify-content-center">
                                            <h6 class="text-muted">Total de Faturas</h6>
                                            <h4 class="text-primary">{{ $faturas->count() }}</h4>
                                        </div>
                                        <div class="col-md-3 d-flex flex-column align-items-center justify-content-center">
                                            <h6 class="text-muted">Valor Total</h6>
                                            <h4 class="text-success">€{{ number_format($faturas->sum('valor'), 2, ',', '.') }}</h4>
                                        </div>
                                        <div class="col-md-3 d-flex flex-column align-items-center justify-content-center">
                                            <h6 class="text-muted">Com Imagem</h6>
                                            <h4 class="text-warning">{{ $faturas->whereNotNull('imagem')->count() }}</h4>
                                        </div>
                                    </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-file-invoice fa-5x text-muted"></i>
                            </div>
                            <h4 class="text-muted">Nenhuma fatura encontrada</h4>
                            <p class="text-muted mb-4">Comece adicionando sua primeira fatura ao sistema.</p>
                            <a href="{{ route('faturas.create') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-plus me-2"></i>
                                Adicionar Primeira Fatura
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.1);
}

.btn-group .btn {
    margin: 0 1px;
}

.img-thumbnail {
    transition: transform 0.2s;
    border-radius: 5px;
}

.img-thumbnail:hover {
    transform: scale(1.1);
}

.card {
    border: none;
    border-radius: 10px;
}

.alert {
    border-radius: 8px;
}

.badge {
    font-size: 0.85em;
}
.table thead th a {
    display: inline-flex;
    align-items: center;
    gap: 6px; /* espaço entre texto e ícone */
    justify-content: center;
}

.table thead th a .fa-sort {
    margin-left: 6px; /* separa mais a seta da palavra */
}

/* Cabeçalho - cursor e setas */
thead th a {
    cursor: pointer;
}

thead th a i {
    transition: opacity 0.3s ease;
}
/* Estilos para os cards de informação */
.bg-light.rounded-3 {
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.2s ease;
}

.bg-light.rounded-3:hover {
    border-color: rgba(13, 110, 253, 0.2);
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.1);
}

/* Estilo especial para o valor */
.text-success {
    color: #28a745 !important;
}

/* Espaçamento adicional para hover nos botões */
.btn:hover {
    margin: 0.25rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Garantir espaçamento consistente em todos os containers de botões */
.d-flex:has(.btn) {
    gap: 1rem;
}

.d-flex:has(.btn) > * {
    margin: 0.25rem;
}
</style>
@endsection 