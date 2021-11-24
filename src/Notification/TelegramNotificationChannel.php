<?php

namespace App\Notification;

use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Psr\Log\LoggerInterface;

class TelegramNotificationChannel implements NotificationChannelInterface
{
    private NotifierInterface $notifier;
    private LoggerInterface $logger;

    public function __construct(NotifierInterface $notifier, LoggerInterface $logger)
    {
        $this->notifier = $notifier;
        $this->logger = $logger;
    }

    public function send(string $recipient, string $message): bool
    {
        $message = 'To: ' . $recipient . '>> ' . $message;
        $notification = new Notification($message, ['chat/telegram']);
        $recipient = new Recipient($recipient, $recipient);

        try {
            $this->notifier->send($notification, $recipient);

            return true;
        } catch (\Exception $e) {
            $this->logger->error('Message is not send', ['exception' => $e]);

            return false;
        }
    }
}
