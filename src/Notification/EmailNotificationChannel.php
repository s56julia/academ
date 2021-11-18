<?php

namespace App\Notification;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailNotificationChannel implements NotificationChannelInterface
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(string $recipient, string $message): bool
    {
        $email = (new Email())
            ->from('email.channel@example.com')
            ->to($recipient)
            ->subject('Notification from channel!')
            ->text($message);
        $this->mailer->send($email);

        return true;
    }
}
