<?php
/**
 * EventEntity.php
 *
 * @author    Bernd Engels
 * @created   11.04.19 18:49
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Entities;

use App\Helper\MyDate;
use App\Http\Controllers\EventController;
use App\Models\Category;
use App\Models\Event;
use App\Models\Theme;
use App\Repositories\EventEntityRepository;
use App\Repositories\EventPeriodicRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravelium\Feed\Feed;

class EventEntity extends Entity {

	/**
	 * @var Collection
	 */
	private $id;
	private $domId;
	private $title;
	private $subtitle;
	private $description;
	private $descriptionSanitized;
	private $descriptionText;
    private $descriptionWithoutVideo;
	/**
	 * @var null|Collection
	 */
	private $links;
	private $is_periodic = 0;
	/**
	 * @var Carbon
	 */
	private $event_date;
	private $event_time;
	/**
	 * @var null|Category
	 */
	private $category;
	/**
	 * @var null|Theme
	 */
	private $theme;
	/**
	 * @var null|Collection
	 */
	private $images;
	/**
	 * @var Carbon
	 */
	private $created_at;
	/**
	 * @var Carbon
	 */
	private $updated_at;
	private $createdBy;
	private $updatedBy;
	private $eventLink;

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDomId() {
		return $this->domId;
	}

	/**
	 * @param mixed $domId
	 */
	public function setDomId($domId) {
		$this->domId = $domId;
		return $this;
	}

	/**
	 * @return null
	 */
	public function getDescriptionSanitized() {
		return $this->descriptionSanitized;
	}

	/**
	 * @param null $descriptionSanitized
	 */
	public function setDescriptionSanitized($descriptionSanitized) {
		$this->descriptionSanitized = $descriptionSanitized;
		return $this;
	}

    /**
     * @return mixed
     */
    public function getDescriptionWithoutVideo()
    {
        return $this->descriptionWithoutVideo;
    }

    /**
     * @param mixed $descriptionWithoutVideo
     * @return EventEntity
     */
    public function setDescriptionWithoutVideo($descriptionWithoutVideo): EventEntity
    {
        $this->descriptionWithoutVideo = $descriptionWithoutVideo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEventLink()
    {
        return $this->eventLink;
    }

    /**
     * @param mixed $eventLink
     * @return EventEntity
     */
    public function setEventLink($eventLink): EventEntity
    {
        $this->eventLink = $eventLink;
        return $this;
    }

	/**
	 * @return mixed
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param mixed $title
	 */
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getSubtitle() {
		return $this->subtitle;
	}

	/**
	 * @param mixed $subtitle
	 */
	public function setSubtitle($subtitle) {
		$this->subtitle = $subtitle;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param mixed $description
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 * @return null
	 */
	public function getDescriptionText() {
		return html_entity_decode(strip_tags(preg_replace("/(<br[ ]?[\/]?>){1,}/i","\n",$this->description)));
	}

	/**
	 * @return mixed
	 */
	public function getLinks() {
		return $this->links;
	}

    public function getLinksArray() {
        return collect(preg_split('/[\n\r]+/', $this->links)) ?? collect([]);
    }

	public function getHtmlLinks()
	{
		if($this->links && $this->links instanceof Collection && $this->links->count()) {
			return $this->links->map(function($item) {
				return "<a href='$item' target='_blank'>$item</a>";
			});
		}
		return null;
	}

	/**
	 * @param mixed $links
	 */
	public function setLinks($links = null) {
		if($links) {
			$this->links = $links;
		}
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getIsPeriodic() {
		return $this->is_periodic;
	}

	/**
	 * @param mixed $is_periodic
	 */
	public function setIsPeriodic($is_periodic) {
		$this->is_periodic = $is_periodic;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getEventDate() {
		return $this->event_date;
	}

	/**
	 * @return mixed
	 */
	public function getEventDateTime() {
//		$str = $this->event_date->format('Y-m-d') .' '. $this->getEventTime();
        $str = Carbon::make($this->event_date)->format('Y-m-d') .' '. $this->getEventTime();
		return Carbon::createFromFormat('Y-m-d H:i', $str);
	}

	/**
	 * @param mixed $event_date
	 */
	public function setEventDate( $event_date ) {
		$this->event_date = $event_date;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getEventTime() {
		if( !$this->event_time || '' === trim($this->event_time)) {
			$this->event_time = '19:00';
		}
		return str_replace('.',':', $this->event_time);
	}

	/**
	 * @param mixed $event_time
	 */
	public function setEventTime($event_time) {
		$this->event_time = $event_time;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCreatedAt() {
		return $this->created_at;
	}

	/**
	 * @param mixed $created_at
	 */
	public function setCreatedAt($created_at) {
		if(is_string($created_at)) {
			$this->created_at = Carbon::createFromTimeString($created_at);
		}
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getUpdatedAt() {
		return $this->updated_at;
	}

	/**
	 * @param mixed $updated_at
	 */
	public function setUpdatedAt($updated_at) {
		if(is_string($updated_at)) {
			$this->updated_at = Carbon::createFromTimeString($updated_at);
		}
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCreatedBy() {
		return $this->createdBy;
	}

	/**
	 * @param mixed $created_by
	 */
	public function setCreatedBy($createdBy) {
		$this->createdBy = $createdBy;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getUpdatedBy() {
		return $this->updatedBy;
	}

	/**
	 * @param mixed $updated_by
	 */
	public function setUpdatedBy($updatedBy) {
		$this->updatedBy = $updatedBy;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCategory() {
		return $this->category;
	}

	/**
	 * @param mixed $category
	 */
	public function setCategory($category) {
		$this->category = $category;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getTheme() {
		return $this->theme;
	}

	/**
	 * @param mixed $theme
	 */
	public function setTheme($theme) {
		$this->theme = $theme;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getImages() {
		return $this->images;
	}

	/**
	 * @param mixed $images
	 */
	public function setImages($images) {
		$this->images = $images;
		return $this;
	}

	public function __toString() {
		return (string) $this->title;
	}

	public function toCalendarData(){

		$body = $this->descriptionWithoutVideo;
		if ($this->subtitle && '' !== $this->subtitle) {
			$body = "<div class='subtitle'>{$this->subtitle}</div>$body";
		}
        $eventDate = Carbon::make($this->event_date);
		return [
            'date'		=> $eventDate->format('Y-m-d'),
			'badge' 	=> true,
			'title'		=> $this->title,
			'body' 		=> $body,
			'footer'	=> ($this->getHtmlLinks() && $this->getHtmlLinks()->count()) ? $this->getHtmlLinks()->join('<br>') : '',
			'classname'	=> 'calendar-event',
		];
	}

	public function toFeedArray()
	{
		$description = trim(strip_tags(preg_replace('/<br[ ]?[\/]?>/i',"\n",$this->description)));
		$description = preg_replace('/[\n\r]+/', "\n", $description);
		$description = preg_replace('/[ ]+/', " ", $description);
		$title = $this->event_date.' '.$this->event_time.' ('.$this->category->name.'): '.$this->title;
        $eventDate = Carbon::make($this->event_date);

		return [
			'title'			=> $title,
			'author'		=> $this->createdBy ? $this->createdBy->email : null,
			'category'		=> $this->category->name,
			'description'	=> $this->subtitle,
			'content'		=> $description,
			'link'			=> route('public.event.eventsShow', ['date' => $eventDate->format('Y-m-d')]),
			'pubdate'		=> $eventDate->toRfc822String(),
		];
	}

    /**
     * @return array
     */
    public function toArray() {
        $eventDate = Carbon::make($this->event_date);
        return [
            'id'			=> $this->id,
            'title'			=> $this->title,
            'subtitle'	    => $this->subtitle,
            'category_id'	=> $this->category->id,
            'theme_id'	    => $this->theme->id,
            'description'	=> $this->description,
            'links'			=> $this->links,
            'event_date'	=> $eventDate->format('Y-m-d'),
            'event_time'	=> $this->event_time,
            'images'	    => $this->getImages(),
        ];
    }
}
