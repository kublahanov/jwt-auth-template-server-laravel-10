<?php

namespace App\Notifications;

use App\Notifications\Messages\VerifyEmailMessage;
use App\Services\AuthService;
use Closure;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

/**
 * Verification email maker.
 */
class VerifyEmail extends Notification
{
    /**
     * The callback that should be used to create the verify email URL.
     *
     * @var Closure|null
     */
    public static ?Closure $createUrlCallback = null;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var Closure|null
     */
    public static ?Closure $toMailCallback = null;

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
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        return $this->buildMailMessage($verificationUrl);
    }

    /**
     * Get the verify email notification mail message for the given URL.
     *
     * @param string $url
     * @return MailMessage
     */
    protected function buildMailMessage(string $url): MailMessage
    {
        return (new VerifyEmailMessage())
            // ->mailer('mailpit') // TODO: For test cases!
            ->subject(AuthService::VERIFICATION_EMAIL_SUBJECT)
            ->action('Подтвердить', $url);
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function verificationUrl(mixed $notifiable): string
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable);
        }

        return URL::temporarySignedRoute(
            AuthService::AUTH_ROUTES_NAMES['verify-email'],
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ],
        );
    }

    /**
     * Set a callback that should be used when creating the email verification URL.
     *
     * @param Closure $callback
     * @return void
     */
    public static function createUrlUsing(Closure $callback): void
    {
        static::$createUrlCallback = $callback;
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param Closure $callback
     * @return void
     */
    public static function toMailUsing(Closure $callback): void
    {
        static::$toMailCallback = $callback;
    }
}
