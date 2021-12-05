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

class NotificationChannelRegistry
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
