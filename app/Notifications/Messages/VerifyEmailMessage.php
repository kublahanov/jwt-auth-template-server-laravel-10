<?php

namespace App\Notifications\Messages;

use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailMessage extends MailMessage
{
    public $markdown = 'notifications.verify-email';
}
