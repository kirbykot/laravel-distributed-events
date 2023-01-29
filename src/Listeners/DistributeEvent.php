<?php

namespace Kirbykot\DistributedEvents\Listeners;

use Kirbykot\DistributedEventsBridge\ShouldDistribute;
use Kirbykot\PhpDistributedMessages\Distributor;

class DistributeEvent
{
    private Distributor $distributor;

    public function __construct(Distributor $distributor)
    {
        $this->distributor = $distributor;
    }

    public function handle(ShouldDistribute $event)
    {
        if($event->isFromSubscription()){
            return;
        }
        $this->distributor->distribute($event);
    }
}