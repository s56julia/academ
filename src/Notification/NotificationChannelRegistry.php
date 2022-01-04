<?php

namespace App\Notification;

use Symfony\Component\DependencyInjection\ServiceLocator;

class NotificationChannelRegistry
{
    private ServiceLocator $channelsLocator;

    public function __construct(ServiceLocator $channelsLocator)
    {
        $this->channelsLocator = $channelsLocator;
    }

    public function getNotificationTransportByChannel(string $channel): NotificationChannelInterface
    {
        if (!$this->channelsLocator->has($channel)) {
            throw new UnknownChannelException('Unsupported channel requested');
        }

        return $this->channelsLocator->get($channel);
    }
}
