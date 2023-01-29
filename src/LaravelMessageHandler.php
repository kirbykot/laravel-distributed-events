<?php

namespace Kirbykot\DistributedEvents;

use Illuminate\Contracts\Events\Dispatcher;
use Kirbykot\DistributedEventsBridge\ShouldDistribute;
use Kirbykot\PhpDistributedMessages\Contracts\SubscriptionHandler;
use Kirbykot\PhpDistributedMessages\Contracts\SubscriptionContextInterface;

class LaravelMessageHandler implements SubscriptionHandler
{
    private Dispatcher $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function handle(SubscriptionContextInterface $context): bool
    {
        if(($event = $context->getPayload()) instanceof ShouldDistribute){
            $event->setFromSubscription();
        }
        $this->dispatcher->dispatch($event);
        return true;
    }
}