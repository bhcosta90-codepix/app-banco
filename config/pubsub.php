<?php

return [
    'type' => env('PUBLISH_MESSAGE', App\Support\PubSub\DatabasePubSub::class),
];
