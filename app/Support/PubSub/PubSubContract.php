<?php

namespace App\Support\PubSub;

interface PubSubContract {

    public function publish(array $routingKey, array $data);

    public function consume(string $queue, array $routingKey, object $action);
}
