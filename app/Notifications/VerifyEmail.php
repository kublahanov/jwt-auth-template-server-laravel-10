<?php

namespace App\Notifications;

use App\Services\AuthService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
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
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(AuthService::VERIFICATION_EMAIL_SUBJECT)
            ->greeting('Здравствуйте!')
            ->line('Для завершения регистрации на сайте, необходимо подтвердить Ваш е-мейл.')
            ->action('Подтвердить', url($this->verificationUrl))
            ->line('Если это ошибка, и Вы не регистрировались на нашем сайте,' .
                ' то никаких дальнейших действий не требуется.')
            ->salutation('команда проекта «Вершки и корешки»!');
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
