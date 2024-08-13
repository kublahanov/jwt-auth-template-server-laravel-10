<?php

namespace App\Notifications;

use App\Notifications\Messages\ResetPasswordMessage;
use App\Services\AuthService;
use Closure;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public string $token;

    /**
     * The callback that should be used to create the reset password URL.
     *
     * @var (Closure(mixed, string): string)|null
     */
    public static ?Closure $createUrlCallback = null;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var (Closure(mixed, string): MailMessage|Mailable)|null
     */
    public static Mailable|Closure|null $toMailCallback = null;

    /**
     * Create a notification instance.
     *
     * @param string $token
     * @return void
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     * @return array|string
     */
    public function via(mixed $notifiable): array|string
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return $this->buildMailMessage($this->resetUrl($notifiable));
    }

    /**
     * Get the reset password notification mail message for the given URL.
     *
     * @param string $url
     * @return MailMessage
     */
    protected function buildMailMessage(string $url): MailMessage
    {
        return (new ResetPasswordMessage)
            // ->mailer('mailpit') // TODO: For test cases!
            ->subject(AuthService::RESET_PASSWORD_EMAIL_SUBJECT)
            ->action('Подтвердить', $url);
    }

    /**
     * Get the reset URL for the given notifiable.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function resetUrl(mixed $notifiable): string
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        }

        return url(route(AuthService::AUTH_ROUTES_NAMES['reset-password'], [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }

    /**
     * Set a callback that should be used when creating the reset password button URL.
     *
     * @param Closure(mixed, string): string $callback
     * @return void
     */
    public static function createUrlUsing(Closure $callback): void
    {
        static::$createUrlCallback = $callback;
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param Closure(mixed, string): (MailMessage|Mailable) $callback
     * @return void
     */
    public static function toMailUsing(Closure $callback): void
    {
        static::$toMailCallback = $callback;
    }
}
