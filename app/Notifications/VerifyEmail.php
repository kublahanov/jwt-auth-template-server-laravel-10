<?php

namespace App\Notifications;

use App\Services\AuthService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(AuthService::VERIFICATION_EMAIL_SUBJECT)
            ->line('Для завершения регистрации необходимо подтвердить Ваш е-мейл.')
            ->action('Подтвердить', url('/'))
            ->line('Если это ошибка, и вы не регистрировались на нашем сайте,' .
                ' то никаких дальнейших действий не требуется.');
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
