<?php

namespace App\Notification;

interface NotificationChannelInterface
{
    public function send(string $recipient, string $message): bool;
}
