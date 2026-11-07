<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CorreoMasivo extends Mailable
{
    use Queueable, SerializesModels;

    public $asunto;
    public $nombre;
    public $mensaje;

    public function __construct($asunto, $nombre, $mensaje)
    {
        $this->asunto = $asunto;
        $this->nombre = $nombre;
        $this->mensaje = $mensaje;
    }

    public function build()
    {
        return $this->view('emails.correo-masivo')
                    ->subject($this->asunto);
    }
}