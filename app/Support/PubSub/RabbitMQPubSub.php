<?php

namespace App\Support\PubSub;

use Bschmitt\Amqp\Facades\Amqp;
use Exception;
use Illuminate\Support\Facades\Log;

final class RabbitMQPubSub implements PubSubContract
{
    public function publish(array $routingKey, array $data)
    {
        foreach ($routingKey as $route) {
            Amqp::publish($route, json_encode($data));
            if (app()->environment('local')) {
                Log::info($route);
                Log::info($data);
            }
        }
    }

    public function consume(string $queue, array $routingKey, object $action)
    {
        throw new Exception('do not implemented');
    }
}
