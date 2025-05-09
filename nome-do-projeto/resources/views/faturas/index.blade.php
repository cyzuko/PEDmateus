@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Minhas Faturas</span>
                    <a href="{{ route('faturas.create') }}" class="btn btn-sm btn-success">Nova Fatura</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if($faturas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Fornecedor</th>
                                        <th>Data</th>
                                        <th>Valor</th>
                                        <th>Imagem</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($faturas as $fatura)
                                        <tr>
                                            <td>{{ $fatura->fornecedor }}</td>
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
                                                    <a href="{{ route('faturas.show', $fatura->id) }}" 
                                                       class="btn btn-sm btn-info">Ver</a>
                                                    <a href="{{ route('faturas.edit', $fatura->id) }}" 
                                                       class="btn btn-sm btn-primary">Editar</a>
                                                    <form action="{{ route('faturas.destroy', $fatura->id) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Tem certeza que deseja remover esta fatura?');">
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

                        <!-- Paginação do AdminLTE -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $faturas->links('vendor.pagination.adminlte') }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <p>Você ainda não tem faturas registradas.</p>
                            <a href="{{ route('faturas.create') }}" class="btn btn-success">Adicionar sua primeira fatura</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
