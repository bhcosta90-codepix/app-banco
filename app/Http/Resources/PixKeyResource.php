<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PixKeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $ret = [
            'id' => $this->uuid,
        ];

        if (empty($request->key)) {
            $ret += [
                'key' => $this->key
            ];
        }

        return $ret;
    }
}
