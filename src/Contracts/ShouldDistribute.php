<?php

namespace Kirbykot\DistributedEvents\Contracts;

interface ShouldDistribute
{
    public function isFromSubscription(): bool;
    public function setFromSubscription(): void;
}