<?php

namespace App\Notifications\Messages;

use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordMessage extends MailMessage
{
    public $markdown = 'notifications.reset-password';
}
