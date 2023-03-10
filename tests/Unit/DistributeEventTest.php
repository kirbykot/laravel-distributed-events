<?php

namespace Tests\Unit;

use Kirbykot\DistributedEventsBridge\ShouldDistribute;
use Kirbykot\DistributedEventsBridge\ShouldDistributeTrait;
use Kirbykot\PhpDistributedMessages\Distributor;
use Tests\TestCase;

class DistributeEventTest extends TestCase
{
    private \Mockery\MockInterface $mock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mock = $this->mock(Distributor::class);
    }

    public function testCanListenToInterface()
    {
        $event = $this->makeEvent();
        $this->mock->expects('distribute')->once()->with($event);
        $this->app['events']->dispatch($event);
    }

    public function testDontDistributeEventFromSubscription()
    {
        $event = $this->makeEvent();
        $event->setFromSubscription();
        $this->mock->expects('distribute')->never();
        $this->app['events']->dispatch($event);
    }

    private function makeEvent(): ShouldDistribute
    {
        return new class implements ShouldDistribute{
            use ShouldDistributeTrait;
            public string $test = 'test';
        };
    }
}