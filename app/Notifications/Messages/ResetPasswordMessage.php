<?php

namespace App\Notifications\Messages;

use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordMessage extends MailMessage
{
    public $markdown = 'notifications.reset-password';

    public function __construct()
    {
        /**
         * Reset password link lifetime.
         */
        $this->viewData['count'] = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');
    }
}
