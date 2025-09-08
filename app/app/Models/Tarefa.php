<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Tarefa extends Model
{
    protected $fillable = ['id','titulo', 'descricao', 'data_vencimento', 'status', 'prioridade', 'usuario_responsavel', 'associacao_usuario_criador_id'];

    protected $casts = [
        'data_vencimento' => 'date',
    ];

    protected function dataVencimento(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : null,
        );
    }
}