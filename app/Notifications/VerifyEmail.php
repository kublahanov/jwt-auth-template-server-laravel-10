<?php

namespace App\Notifications;

use App\Notifications\Messages\VerifyEmailMessage;
use App\Services\AuthService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Verification email maker.
 * @property string $verificationUrl
 */
class VerifyEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected string $verificationUrl)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): VerifyEmailMessage
    {
        return (new VerifyEmailMessage)
            ->subject(AuthService::VERIFICATION_EMAIL_SUBJECT)
            ->action('Подтвердить', url($this->verificationUrl));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
