
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">

                <!-- 🔲 Cabeçalho -->
                <div class="card-header d-flex justify-content-center">
                    <h5 class="mb-0">As suas Faturas</h5>
                </div>

                <div class="card-body">

                    <!--  Botão Nova Fatura -->
                    <div class="d-flex justify-content-end mb-3 flex-wrap gap-2">
                        <!-- Botão Nova Fatura -->
                        <a href="{{ route('faturas.create') }}" class="btn btn-success">Nova Fatura</a>

                        <!-- Filtro -->
                        <form method="GET" class="d-flex">
                            <div class="input-group">
                                <label class="input-group-text" for="sort">Ordenar por</label>
                                <select name="sort" id="sort" class="form-select" onchange="this.form.submit()">
                                    <option value="">Padrão</option>
                                    <option value="fornecedor_asc" {{ request('sort') == 'fornecedor_asc' ? 'selected' : '' }}>Fornecedor A-Z</option>
                                    <option value="fornecedor_desc" {{ request('sort') == 'fornecedor_desc' ? 'selected' : '' }}>Fornecedor Z-A</option>
                                    <option value="data_asc" {{ request('sort') == 'data_asc' ? 'selected' : '' }}>Data ↑</option>
                                    <option value="data_desc" {{ request('sort') == 'data_desc' ? 'selected' : '' }}>Data ↓</option>
                                    <option value="valor_asc" {{ request('sort') == 'valor_asc' ? 'selected' : '' }}>Valor ↑</option>
                                    <option value="valor_desc" {{ request('sort') == 'valor_desc' ? 'selected' : '' }}>Valor ↓</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    <!-- ✅ Mensagens -->
                    @if(session('success'))
                        <div class="alert alert-success text-center">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger text-center">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- 🧾 Tabela -->
                    @if($faturas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-center">
                                <thead>
                                    <tr>
                                        <th>
                                            Fornecedor
                                            <div class="mt-1">
                                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'fornecedor_asc']) }}" 
                                                   class="btn btn-sm {{ request('sort') == 'fornecedor_asc' ? 'btn-primary' : 'btn-outline-secondary' }}">
                                                   A-Z
                                                </a>
                                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'fornecedor_desc']) }}" 
                                                   class="btn btn-sm {{ request('sort') == 'fornecedor_desc' ? 'btn-primary' : 'btn-outline-secondary' }}">
                                                   Z-A
                                                </a>
                                            </div>
                                        </th>
                                        <th>NIF</th>
                                        <th>
                                            Data
                                            <div class="mt-1">
                                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'data_asc']) }}" 
                                                   class="btn btn-sm {{ request('sort') == 'data_asc' ? 'btn-primary' : 'btn-outline-secondary' }}">
                                                   ↑
                                                </a>
                                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'data_desc']) }}" 
                                                   class="btn btn-sm {{ request('sort') == 'data_desc' ? 'btn-primary' : 'btn-outline-secondary' }}">
                                                   ↓
                                                </a>
                                            </div>
                                        </th>
                                        <th>
                                            Valor
                                            <div class="mt-1">
                                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'valor_asc']) }}" 
                                                   class="btn btn-sm {{ request('sort') == 'valor_asc' ? 'btn-primary' : 'btn-outline-secondary' }}">
                                                   ↑
                                                </a>
                                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'valor_desc']) }}" 
                                                   class="btn btn-sm {{ request('sort') == 'valor_desc' ? 'btn-primary' : 'btn-outline-secondary' }}">
                                                   ↓
                                                </a>
                                            </div>
                                        </th>
                                        <th>Imagem</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($faturas as $fatura)
                                        <tr>
                                            <td>{{ $fatura->fornecedor }}</td>
                                            <td>{{ $fatura->nif ?? 'Não informado' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($fatura->data)->format('d/m/Y') }}</td>
                                            <td>€{{ number_format($fatura->valor, 2, ',', '.') }}</td>
                                            <td>
                                                @if($fatura->imagem)
                                                    <a href="{{ asset('storage/' . $fatura->imagem) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $fatura->imagem) }}" alt="Imagem da Fatura" width="80" class="img-thumbnail">
                                                    </a>
                                                @else
                                                    <span class="text-muted">Sem imagem</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('faturas.show', $fatura->id) }}" class="btn btn-sm btn-info">Ver</a>
                                                    <a href="{{ route('faturas.edit', $fatura->id) }}" class="btn btn-sm btn-primary">Editar</a>
                                                    <form action="{{ route('faturas.destroy', $fatura->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja remover esta fatura?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Remover</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- 📄 Paginação -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $faturas->appends(request()->except('page'))->links('vendor.pagination.adminlte') }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <p>Você ainda não tem faturas registradas.</p>
                            <a href="{{ route('faturas.create') }}" class="btn btn-success mt-2">Adicionar sua primeira fatura</a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection