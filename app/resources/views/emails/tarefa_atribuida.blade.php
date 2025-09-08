<p>Olá {{ $tarefa->responsavel->name }},</p>
<p>Você foi atribuído para a tarefa: <strong>{{ $tarefa->titulo }}</strong></p>
<p>Data de vencimento: {{ $tarefa->data_vencimento }}</p>