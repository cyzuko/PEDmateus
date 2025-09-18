<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('explicacoes', function (Blueprint $table) {
            // Adicionar coluna de aprovação se não existir
            if (!Schema::hasColumn('explicacoes', 'aprovacao_admin')) {
                $table->enum('aprovacao_admin', ['pendente', 'aprovada', 'rejeitada'])
                      ->default('pendente')
                      ->after('user_id');
            }
            
            // Adicionar coluna de data de aprovação se não existir
            if (!Schema::hasColumn('explicacoes', 'data_aprovacao')) {
                $table->timestamp('data_aprovacao')
                      ->nullable()
                      ->after('aprovacao_admin');
            }
            
            // Adicionar coluna para quem aprovou se não existir
            if (!Schema::hasColumn('explicacoes', 'aprovada_por')) {
                $table->unsignedBigInteger('aprovada_por')
                      ->nullable()
                      ->after('data_aprovacao');
                      
                // Criar foreign key para a tabela users
                $table->foreign('aprovada_por')->references('id')->on('users');
            }
            
            // Adicionar coluna para motivo de rejeição se não existir
            if (!Schema::hasColumn('explicacoes', 'motivo_rejeicao')) {
                $table->text('motivo_rejeicao')
                      ->nullable()
                      ->after('aprovada_por');
            }
        });
    }

    public function down()
    {
        Schema::table('explicacoes', function (Blueprint $table) {
            // Remover foreign key primeiro
            $table->dropForeign(['aprovada_por']);
            // Remover colunas
            $table->dropColumn(['aprovacao_admin', 'data_aprovacao', 'aprovada_por', 'motivo_rejeicao']);
        });
    }
};