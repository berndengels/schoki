<?php
/**
 * SegmentOpts.php
 *
 * @author    Bernd Engels
 * @created   19.06.19 19:35
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Entities\Newsletter;

use App\Entities\Entity;

/**
 * Class SegmentOpts
 */
class SegmentOptsEntity extends Entity{

	/**
	 * @var int
	 */
	protected $saved_segment_id;

	/**
	 * @return int
	 */
	public function getSavedSegmentId() {
		return $this->saved_segment_id;
	}

	/**
	 * @param int $saved_segment_id
	 * @return $this
	 */
	public function setSavedSegmentId($saved_segment_id) {
		$this->saved_segment_id = $saved_segment_id;
		return $this;
	}
}
