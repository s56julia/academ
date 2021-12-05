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
