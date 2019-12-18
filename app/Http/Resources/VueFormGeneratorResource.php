<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VueFormGeneratorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'model'         => $this->model,
            'schema'        => $this->schema,
            'formOptions'   => $this->formOptions,
        ];
    }
}
