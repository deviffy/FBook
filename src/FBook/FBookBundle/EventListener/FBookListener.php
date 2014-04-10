<?php

namespace FBook\FBookBundle\EventListener;

use FBook\Facebook;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * FBookListener
 *
 * @author Catalin Dumitrescu
 */
class FBookListener
{
    public function __construct(Facebook $facebook)
    {
        $this->facebook = $facebook;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();
        $this->facebook->setRequest($request);
    }
}
