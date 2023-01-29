<?php

namespace Kirbykot\DistributedEvents\Console;

use Illuminate\Console\Command;
use Kirbykot\DistributedEvents\Subscriber;

class StartSubscription extends Command
{
    protected $signature = 'lde:subscribe {--message=}';

    protected $description = 'Start subscriber';

    public function handle(Subscriber $subscriber): int
    {
        if($message = $this->option('message')){
            $message = [$message];
        }
        $subscriber->subscribe($message, fn($type, $line) => $this->writeOutput($type, $line));
        return 0;
    }

    private function writeOutput(string $type, string $line)
    {
        $this->output->writeln("[".now()->toISOString()."][{$type}] " . $line);
    }
}
