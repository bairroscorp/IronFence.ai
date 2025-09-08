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
        Schema::create('tarefas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->date('data_vencimento')->nullable();
            $table->enum('status', ['todo', 'doing', 'done'])->default('todo');
            $table->enum('prioridade', ['baixa', 'media', 'alta'])->default('baixa');
            $table->string('usuario_responsavel');
            $table->integer('associacao_usuario_criador_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarefas');
    }
};
