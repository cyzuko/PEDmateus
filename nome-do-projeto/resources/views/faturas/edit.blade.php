@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Editar Fatura</div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('faturas.update', $fatura->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group row mb-3">
                            <label for="fornecedor" class="col-md-4 col-form-label text-md-right">Fornecedor</label>

                            <div class="col-md-6">
                                <input id="fornecedor" type="text" class="form-control @error('fornecedor') is-invalid @enderror" 
                                       name="fornecedor" value="{{ old('fornecedor', $fatura->fornecedor) }}" required>

                                @error('fornecedor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="nif" class="col-md-4 col-form-label text-md-right">NIF</label>

                            <div class="col-md-6">
                                <input id="nif" type="text" class="form-control @error('nif') is-invalid @enderror" 
                                       name="nif" value="{{ old('nif', $fatura->nif) }}" maxlength="9" pattern="[0-9]{9}">

                                @error('nif')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="data" class="col-md-4 col-form-label text-md-right">Data</label>

                            <div class="col-md-6">
                                <input id="data" type="date" class="form-control @error('data') is-invalid @enderror" 
                                       name="data" value="{{ old('data', $fatura->data->format('Y-m-d')) }}" required>

                                @error('data')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="valor" class="col-md-4 col-form-label text-md-right">Valor</label>

                            <div class="col-md-6">
                                <input id="valor" type="number" step="0.01" class="form-control @error('valor') is-invalid @enderror" 
                                       name="valor" value="{{ old('valor', $fatura->valor) }}" required>

                                @error('valor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="imagem" class="col-md-4 col-form-label text-md-right">Imagem da Fatura</label>

                            <div class="col-md-6">
                                <input id="imagem" type="file" class="form-control @error('imagem') is-invalid @enderror" name="imagem">

                                @error('imagem')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                @if($fatura->imagem)
                                    <div class="mt-2">
                                        <p>Imagem atual:</p>
                                        <img src="{{ asset('storage/' . $fatura->imagem) }}" alt="Imagem atual" class="img-thumbnail" style="max-height: 150px;">
                                        <p class="text-muted small">Faça upload de uma nova imagem para substituir a atual.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Atualizar Fatura
                                </button>
                                <a href="{{ route('faturas.show', $fatura->id) }}" class="btn btn-secondary">
                                    Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection