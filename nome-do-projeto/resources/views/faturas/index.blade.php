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
                    @if($faturas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Fornecedor</th>
                                        <th>Data</th>
                                        <th>Valor</th>
                                        <th>Imagem</th>
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
                                                    <a href="{{ asset('storage/' . $fatura->imagem) }}" target="_blank">Ver imagem</a>
                                                @else
                                                    <span class="text-muted">Sem imagem</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $faturas->links() }}
                        </div>
                    @else
                        <p>Você ainda não tem faturas registradas.</p>
                        <a href="{{ route('faturas.create') }}" class="btn btn-success">Adicionar sua primeira fatura</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection