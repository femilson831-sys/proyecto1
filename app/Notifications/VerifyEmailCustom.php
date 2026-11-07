<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailCustom extends VerifyEmail
{
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        
        return (new MailMessage)
            ->subject('Verifica tu cuenta - JACHA TOURS')
            ->greeting('¡Bienvenido a JACHA TOURS!')
            ->line('Gracias por registrarte. Por favor verifica tu dirección de correo electrónico haciendo clic en el botón de abajo.')
            ->action('Verificar Correo', $verificationUrl)
            ->line('Si no creaste esta cuenta, ignora este mensaje.')
            ->salutation('¡Gracias por confiar en JACHA TOURS!');
    }
}