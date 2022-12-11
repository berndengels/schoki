<?php

namespace App\Http\Resources;

use App\Entities\EventEntity;
use App\Entities\ImageEntity;
use App\Models\Images;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
		$category 	= $this->getCategory();
		$theme		= $this->getTheme();
		$links		= $this->getLinks() ? preg_split("/[\n\r]+/", $this->getLinks()) : null;
		$images		= $this->getImages();
		$imageEntities	= [];

		if($images && $images->count()) {
			/**
			 * @var $image Images
			 */
			foreach($images as $image) {
				$img = new ImageEntity();
				$img
					->setInternalName($image->internal_filename)
					->setExternalName($image->external_filename)
					->setWidth($image->width)
					->setHeight($image->height)
				;
				$imageEntities[] = $img->toObject();
			}
		}
        return [
			'id'            => $this->getId(),
			'date'    		=> (string) $this->getEventDate(),
			'time'    		=> (string) $this->getEventTime(),
			'title'         => $this->getTitle(),
			'subtitle'      => $this->getSubtitle(),
			'description'   => $this->getDescriptionText(),
            'category'      => $category ? $category->name : null,
            'theme'      	=> $theme ? $theme->name : null,
//			'links'         => ($links && $links->count()) ? implode("\n", $links->toArray()): null,
            'links'         => $links,
			'images'        => $imageEntities,
		];
    }
}
