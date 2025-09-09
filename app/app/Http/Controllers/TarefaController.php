<?php

namespace App\Http\Controllers;

use App\Events\TarefaAtribuida;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TarefaAtribuidaMail;
use App\Services\FeriadoService;


class TarefaController extends Controller
{
    public function index()
    {
        return response()->json(Tarefa::all());
    }

    public function updateStatus(Request $request, Tarefa $tarefa)
    {
        $tarefa->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    public function store(Request $request, FeriadoService $feriados)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_vencimento' => 'required|string',
            'status' => 'required|in:todo,doing,done',
            'prioridade' => 'required|in:baixa,media,alta',
            'usuario_responsavel' => 'nullable|string',
        ]);

        $tarefa = Tarefa::create(
            [
                'titulo' => $data['titulo'],
                'descricao' => $data['descricao'],
                'data_vencimento' => $data['data_vencimento'],
                'status' => $data['status'],
                'prioridade' => $data['prioridade'],
                'usuario_responsavel' => $data['usuario_responsavel'] ?? Auth::user()->email,
                'associacao_usuario_criador_id' => Auth::user()->id
            ]
        );

        if (!is_null($tarefa->usuario_responsavel)) {

            // Dispara evento WebSocket
            event(new TarefaAtribuida($tarefa));

            // Dispara email
            Mail::to(is_null($tarefa->usuario_responsavel))->queue(new TarefaAtribuidaMail($tarefa));
        }
     
        if ($feriados->isFeriado($request->data_vencimento)) {
            return response()->json([
                'warning' => 'A data escolhida é um feriado!',
            ], 200);
        }

        return response()->json($tarefa);
    }

    public function getOptionUsers()
    {
        $users = User::orderBy('name')
            ->select(['email as value', 'name as text'])
            ->get();

        // adiciona no começo do array
        $options = collect([
            ['value' => null, 'text' => 'Selecione']
        ])->merge($users);

        return response()->json($options);
    }

    public function deletar($id)
    {
        Tarefa::where('id', $id)->delete();
        return response()->json(['status' => true, 'mensagem' => 'Deletado com sucesso']);
    }
}
