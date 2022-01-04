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

use Psr\Log\LoggerInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

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
        $message = 'To: '.$recipient.'>> '.$message;
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
