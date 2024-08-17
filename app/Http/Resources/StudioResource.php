<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'studio_name' => $this->studio_name,
            'street_address' => $this->street_address,
            'local_government' => $this->local_government,
            'state' => $this->state,
            'description' => $this->description,
            'days_available' => json_decode($this->days_available),
            'time_available' => json_decode($this->time_available),
            'max_people' => $this->max_people,
            'studio_equipment' => $this->studio_equipment,
            'studio_fee' => $this->studio_fee,
            'dedicated_producer' => $this->dedicated_producer,
            'studio_rule' => json_decode($this->studio_rule),
            'images' => json_decode($this->images),
        ];
    }
}
