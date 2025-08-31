@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Nova Explicação</h3>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Erro:</strong>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('explicacoes.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Disciplina *</label>
                                    <input type="text" name="disciplina" class="form-control" 
                                           value="{{ old('disciplina') }}" required 
                                           placeholder="Ex: Matemática, Português">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Data da Explicação *</label>
                                    <input type="date" name="data_explicacao" class="form-control" 
                                           value="{{ old('data_explicacao') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Hora Início *</label>
                                    <input type="time" name="hora_inicio" class="form-control" 
                                           value="{{ old('hora_inicio') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Hora Fim *</label>
                                    <input type="time" name="hora_fim" class="form-control" 
                                           value="{{ old('hora_fim') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nome do Aluno *</label>
                                    <input type="text" name="nome_aluno" class="form-control" 
                                           value="{{ old('nome_aluno') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contacto do Aluno *</label>
                                    <input type="text" name="contacto_aluno" class="form-control" 
                                           value="{{ old('contacto_aluno') }}" required 
                                           placeholder="Telefone ou email">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Local *</label>
                                    <input type="text" name="local" class="form-control" 
                                           value="{{ old('local') }}" required 
                                           placeholder="Ex: Online, Biblioteca, Casa do aluno">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Preço (€) *</label>
                                    <input type="number" step="0.01" min="0" name="preco" 
                                           class="form-control" value="{{ old('preco') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Observações</label>
                            <textarea name="observacoes" class="form-control" rows="3" 
                                      placeholder="Informações adicionais sobre a explicação">{{ old('observacoes') }}</textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Criar Explicação
                            </button>
                            <a href="{{ route('explicacoes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection