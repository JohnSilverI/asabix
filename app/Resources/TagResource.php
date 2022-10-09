<?php

namespace App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     * @throws \Exception
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created' => (new \DateTime($this->created_at))->format('Y-m-d H:i:s'),
            'updated' => (new \DateTime($this->updated_at))->format('Y-m-d H:i:s'),
        ];
    }
}
