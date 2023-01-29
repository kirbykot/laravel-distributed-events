<?php

namespace Kirbykot\DistributedEvents;

use Kirbykot\DistributedEvents\Exceptions\DistributedEventsException;
use Kirbykot\PhpDistributedMessages\Contracts\SubscriptionHandler;
use Kirbykot\PhpDistributedMessages\Distributor;

class Subscriber
{
    private Distributor $distributor;
    private SubscriptionHandler $handler;
    private array $config;

    public function __construct(Distributor $distributor, SubscriptionHandler $handler, array $config)
    {
        $this->distributor = $distributor;
        $this->config = $config;
        $this->handler = $handler;
    }

    public function subscribe(?array $messages = null, \Closure $outputHandler = null)
    {
        $messages = $messages ?? $this->config['subscribe_to'];
        if(! $queue = $this->config['main_queue_name']){
            throw new DistributedEventsException('LDE_MAIN_QUEUE_NAME must be set');
        }
        $this->distributor->setOutputHandler($outputHandler);
        $this->distributor->subscribe($queue, $messages, $this->handler);
    }
}