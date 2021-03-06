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
                Log::channel('pubsub')->info($route);
                Log::channel('pubsub')->info($data);
            }
        }
    }

    public function consume(string $queue, array $routingKey, object $action)
    {
        Amqp::consume($queue, function ($message, $resolver) use ($action) {
            $data = json_decode($message->body, true);

            try {
                if (app()->environment('local')) {
                    Log::channel('pubsub')->info($message->getRoutingKey());
                    Log::channel('pubsub')->info($data);
                }

                $action($data);
                $resolver->acknowledge($message);
            } catch (Exception $e) {
                $resolver->reject($message);

                throw $e;

                Log::critical([
                    'class' => get_class($e),
                    'data' => $data,
                    'error' => $e->getMessage(),
                ]);
            }
        }, [
            'routing' => $routingKey,
            'exchange' => 'amq.topic',
            'exchange_type' => 'topic',
            'persistent' => true // required if you want to listen forever
        ]);
    }
}
