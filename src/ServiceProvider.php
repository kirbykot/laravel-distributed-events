<?php

namespace Kirbykot\DistributedEvents;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Kirbykot\DistributedEvents\Console\StartSubscription;
use Kirbykot\DistributedEvents\Contracts\ShouldDistribute;
use Kirbykot\DistributedEvents\Listeners\DistributeEvent;
use Kirbykot\PhpDistributedMessages\MessageFactory;
use Kirbykot\PhpDistributedMessages\Distributor;
use Kirbykot\PhpDistributedMessages\Helpers;
use Kirbykot\PhpDistributedMessages\Transport\RabbitMQ\RabbitMQConnectionManager;
use Kirbykot\PhpDistributedMessages\Transport\RabbitMQ\RabbitMQTransport;
use Kirbykot\PhpDistributedMessages\Transport\RabbitMQ\RabbitMQTransportConfig;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/distributed-events.php' => config_path('distributed-events.php'),
        ], 'config');

        $this->app['events']->listen(
            ShouldDistribute::class,
            DistributeEvent::class
        );
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/distributed-events.php',
            'distributed-events'
        );
        $this->commands([
            StartSubscription::class
        ]);

        $this->app->bind('lde.distributor.serializer', function (){
            return Helpers::makeSerializer();
        });
        //TODO TransportManager
        $this->app->bind('lde.transport.rabbitmq', function ($app){
            $connectionConfig = $app['config']['distributed-events.connections.rabbitmq'];
            $transportConfig = $app['config']['distributed-events.transport_config'];
            $connectionConfig = new RabbitMQTransportConfig($connectionConfig);
            $connection = new RabbitMQConnectionManager($connectionConfig);
            return new RabbitMQTransport($transportConfig, $connection);
        });

        $this->app->bind('lde.error_reporter', function ($app){
            $app->make(LaravelErrorReporter::class);
        });


        $this->app->bind(Distributor::class, function($app){
            return new Distributor(
                //TODO TransportManager
                $app['lde.transport.rabbitmq'],
                new MessageFactory(
                    $app['lde.distributor.serializer'],
                ),
                $app->get('lde.error_reporter')
            );
        });

        $this->app->bind(Subscriber::class, function ($app){
            return new Subscriber(
                $app->make(Distributor::class),
                $app->make(LaravelMessageHandler::class),
                $app['config']['distributed-events.subscriber_config']
            );
        });
    }
}