@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detalhes da Fatura</span>
                    <div>
                        <a href="{{ route('faturas.edit', $fatura->id) }}" class="btn btn-sm btn-primary">Editar</a>
                        <a href="{{ route('faturas.index') }}" class="btn btn-sm btn-secondary">Voltar</a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informações Básicas</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Fornecedor:</th>
                                    <td>{{ $fatura->fornecedor }}</td>
                                </tr>
                                <tr>
                                    <th>Data:</th>
                                    <td>{{ \Carbon\Carbon::parse($fatura->data)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Valor:</th>
                                    <td>€{{ number_format($fatura->valor, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Registrada em:</th>
                                    <td>{{ isset($fatura->criado_em) ? \Carbon\Carbon::parse($fatura->criado_em)->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Imagem da Fatura</h5>
                            <div class="border p-3 text-center">
                                @if($fatura->imagem)
                                    <img src="{{ asset('storage/' . $fatura->imagem) }}" alt="Imagem da Fatura" class="img-fluid">
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $fatura->imagem) }}" class="btn btn-sm btn-info" target="_blank">
                                            Ver em tamanho completo
                                        </a>
                                    </div>
                                @else
                                    <div class="alert alert-secondary">
                                        Nenhuma imagem disponível para esta fatura.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <form action="{{ route('faturas.destroy', $fatura->id) }}" method="POST" 
                              onsubmit="return confirm('Tem certeza que deseja remover esta fatura? Esta ação não pode ser desfeita.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Remover esta Fatura</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection