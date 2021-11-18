<?php

namespace App\Notification;

class  NotificationChannelRegistry
{
    private array $channels;

    public function __construct(iterable $channels)
    {
        $this->channels = $channels instanceof \Traversable ? iterator_to_array($channels) : $channels;
    }

    public function getNotificationTransportByChannel(string $channel): NotificationChannelInterface
    {
        if (!isset($this->channels[$channel])) {
            throw new UnknownChannelException('Unsupported channel requested');
        }

        return $this->channels[$channel];
    }
}
