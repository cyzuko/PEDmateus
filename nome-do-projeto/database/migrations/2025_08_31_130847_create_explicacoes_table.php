<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('explicacoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('disciplina');
            $table->date('data_explicacao');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->string('local');
            $table->decimal('preco', 10, 2);
            $table->text('observacoes')->nullable();
            $table->string('nome_aluno');
            $table->string('contacto_aluno');
            $table->enum('status', ['agendada', 'confirmada', 'concluida', 'cancelada'])->default('agendada');
            $table->timestamps();

            // Índices
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'data_explicacao']);
            $table->index(['data_explicacao', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('explicacoes');
    }
};