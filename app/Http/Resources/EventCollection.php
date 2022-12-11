<?php

namespace App\Http\Resources;

use App\Entities\EventEntity;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EventCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /**
         * @var $this EventEntity
         */
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'subtitle'      => $this->subtitle,
            'description'   => $this->description,
            'event_date'    => (string) $this->event_date,
            'event_time'    => (string) $this->event_time,
            'created_at'    => (string) $this->created_at,
            'updated_at'    => (string) $this->updated_at,
        ];
    }
}
