<?php

namespace App\Events;

use App\Models\Tarefa;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class TarefaAtribuida implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $tarefa;

    public function __construct(Tarefa $tarefa)
    {
        $this->tarefa = $tarefa;
    }

    public function broadcastOn()
    {
        // Cada usuário tem um canal privado
        return new PrivateChannel('usuario.' . $this->tarefa->usuario_responsavel);
    }

    public function broadcastAs()
    {
        return 'TarefaAtribuida';
    }
}
