<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('explicacoes', function (Blueprint $table) {
            $table->enum('aprovacao_admin', ['pendente', 'aprovado', 'rejeitado'])
                  ->default('pendente')
                  ->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('explicacoes', function (Blueprint $table) {
            $table->dropColumn('aprovacao_admin');
        });
    }
};