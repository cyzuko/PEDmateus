<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up()
    {
        // Primeiro, vamos garantir que a coluna 'role' existe na tabela users
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['user', 'admin', 'professor'])
                      ->default('user')
                      ->after('email');
            });
        }

        // Criar o utilizador admin
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@faturas.com',
            'role' => 'admin',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        echo "✅ Utilizador admin criado com sucesso!\n";
        echo "📧 Email: admin@faturas.com\n";
        echo "🔑 Password: admin123\n";
        echo "⚠️  IMPORTANTE: Altere a password após o primeiro login!\n";
    }

    public function down()
    {
        // Remover o utilizador admin
        DB::table('users')->where('email', 'admin@faturas.com')->delete();
        
        // Opcional: Remover a coluna role (descomente se necessário)
        // Schema::table('users', function (Blueprint $table) {
        //     $table->dropColumn('role');
        // });
    }
};