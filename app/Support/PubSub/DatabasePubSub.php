<?php

namespace App\Support\PubSub;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class DatabasePubSub implements PubSubContract
{
    public function publish(array $routingKey, array $data)
    {
        foreach ($routingKey as $route) {
            DB::table('pubsub')->insert([
                'queue' => config('app.name'),
                'routing' => $route,
                'data' => json_encode($data),
            ]);
        }
    }

    public function consume(string $queue, array $routingKey, object $action)
    {
        foreach ($routingKey as $route) {
            $result = DB::table('pubsub')->select()
                ->where('queue', $queue)
                ->where('routing', $route)
                ->where('status', 'pending')
                ->get();

            foreach ($result as $rs) {
                $data = json_decode($rs->data, true);

                if (app()->environment('local')) {
                    Log::channel('pubsub')->info($route);
                    Log::channel('pubsub')->info($data);
                }

                try {
                    $action($data);
                    DB::table('pubsub')->select()->where('id', $rs->id)->delete();
                } catch (Exception $e) {
                    DB::table('pubsub')->select()->where('id', $rs->id)->update([
                        'status' => 'reject',
                    ]);
                    throw $e;
                }
            }
        }
    }
}
