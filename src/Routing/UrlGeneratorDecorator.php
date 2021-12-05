<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Routing;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;

class UrlGeneratorDecorator implements UrlGeneratorInterface
{
    private UrlGeneratorInterface $decorated;

    public function __construct(UrlGeneratorInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function generate(string $name, array $parameters = [], int $referenceType = self::ABSOLUTE_PATH)
    {
        return urldecode($this->decorated->generate($name, $parameters, $referenceType));
    }

    public function setContext(RequestContext $context)
    {
        $this->decorated->setContext($context);
    }

    public function getContext()
    {
        return $this->decorated->getContext();
    }
}
