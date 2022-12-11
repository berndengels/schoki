<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Entities\EventEntity;
use Illuminate\Http\Resources\Json\JsonResource;

class EventDescriptionResource extends JsonResource
{

    /**
     * Transform the resource into an array.
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
			'description'   => $this->getDescriptionSanitized(),
		];
    }
}
