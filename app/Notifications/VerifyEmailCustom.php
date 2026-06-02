<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailCustom extends VerifyEmail
{
    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifica tu cuenta | JACHA TOURS')

            ->greeting('¡Bienvenido a JACHA TOURS!')

            ->line('Gracias por registrarte en nuestra plataforma.')

            ->line('Explora los Cholets más icónicos de El Alto y vive una experiencia única.')

            ->action('Verificar Cuenta', $verificationUrl)

            ->line('Si no creaste esta cuenta, puedes ignorar este mensaje.')

            ->salutation('JACHA TOURS · El Alto Bolivia');
    }
}