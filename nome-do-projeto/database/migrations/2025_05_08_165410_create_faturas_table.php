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
        Schema::create('faturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('fornecedor');
            $table->string('nif')->nullable(); // Adicionado campo NIF
            $table->date('data');
            $table->decimal('valor', 10, 2);
            $table->string('imagem')->nullable();
    
            // Timestamps personalizados:
            $table->timestamp('criado_em')->nullable();
            $table->timestamp('atualizado_em')->nullable();
    
            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturas');
    }
};