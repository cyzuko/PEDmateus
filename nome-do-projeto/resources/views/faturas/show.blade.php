@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">

                <!-- Cabeçalho -->
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <h4 class="mb-0">Detalhes da Fatura</h4>
                    <div>
                        <a href="{{ route('faturas.edit', $fatura->id) }}" class="btn btn-sm btn-light me-2">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('faturas.index') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>

                <!-- Corpo -->
                <div class="card-body">

                    <!-- Mensagem de sucesso -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Informações Básicas -->
                        <div class="col-md-6 mb-4">
                            <h5>Informações Básicas</h5>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Fornecedor:</th>
                                        <td>{{ $fatura->fornecedor }}</td>
                                    </tr>
                                    <tr>
                                        <th>NIF:</th>
                                        <td>{{ $fatura->nif ?? 'N/A' }}</td>
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
                                        <td>
                                            {{ isset($fatura->criado_em) 
                                                ? \Carbon\Carbon::parse($fatura->criado_em)->format('d/m/Y H:i') 
                                                : 'N/A' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Imagem -->
                        <div class="col-md-6 mb-4">
                            <h5>Imagem da Fatura</h5>
                            <div class="border p-3 text-center rounded bg-light">
                                @if($fatura->imagem)
                                    <img src="{{ asset('storage/' . $fatura->imagem) }}" 
                                    alt="Imagem da Fatura" 
                                    class="rounded shadow-sm"
                                    style="width: 100%; max-width: 400px; height: auto; object-fit: contain;">

                                    <div class="mt-3">
                                        <a href="{{ asset('storage/' . $fatura->imagem) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-expand"></i> Ver em tamanho completo
                                        </a>
                                    </div>
                                @else
                                    <div class="alert alert-secondary mb-0">
                                        Nenhuma imagem disponível para esta fatura.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Botão remover -->
                    <div class="text-center mt-4">
                        <form action="{{ route('faturas.destroy', $fatura->id) }}" method="POST" 
                              onsubmit="return confirm('⚠️ Tem certeza que deseja remover esta fatura? Esta ação não pode ser desfeita.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="fas fa-trash-alt me-2"></i> Remover esta Fatura
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
