<?php

namespace App\Http\Resources;

use App\Entities\AudioEntity;
use App\Entities\ImageEntity;
use App\Models\Audios;
use App\Models\Images;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpaPageResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
		$audios		= $this->audios;
		$audioEntities	= [];

		if($audios && $audios->count()) {
			/**
			 * @var $audios Audios
			 */
			foreach($audios as $audio) {
				$item = new AudioEntity();
                $item
                    ->setId($audio->id)
					->setInternalName($audio->internal_filename)
					->setExternalName($audio->external_filename)
					->setDuration($audio->duration)
					->setBitrate($audio->bitrate)
				;
                $audioEntities[] = $item->toObject();
			}
		}
        return [
			'id'            => $this->id,
			'title'         => $this->title,
            'body'          => $this->body,
			'audios'        => $audioEntities,
		];
    }
}
