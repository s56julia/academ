<?php

namespace App\Notification;

use Psr\Log\LoggerInterface;

class FileLoggerNotificationChannel implements NotificationChannelInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function send(string $recipient, string $message): bool
    {
        $this->logger->info($message, ['recipient' => $recipient]);

        return true;
    }
}