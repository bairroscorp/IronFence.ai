<?php

namespace App\Mail;

use App\Models\Tarefa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TarefaAtribuidaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Tarefa $tarefa) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nova Tarefa Atribuída',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tarefa_atribuida',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
