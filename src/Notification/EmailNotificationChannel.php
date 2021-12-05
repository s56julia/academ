<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
