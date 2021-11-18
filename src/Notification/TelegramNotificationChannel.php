<?php

namespace App\Notification;

use Symfony\Component\Notifier\NotifierInterface;

class TelegramNotificationChannel implements NotificationChannelInterface
{
    private NotifierInterface $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    public function send(string $recipient, string $message): bool
    {
        // TODO: Implement send() method.
        return true;
    }

}
