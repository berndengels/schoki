<?php
/**
 * ReportSummary.php
 *
 * @author    Bernd Engels
 * @created   15.06.19 17:22
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Entities\Newsletter;

use App\Entities\Entity;

/**
 * Class ReportSummaryEntity
 */
class ReportSummaryEntity extends Entity {

	/**
	 * @var int
	 */
	protected $opens = 0;
	/**
	 * @var int
	 */
	protected $unique_opens = 0;
	/**
	 * @var int
	 */
	protected $clicks = 0;
	/**
	 * @var int
	 */
	protected $subscriber_clicks = 0;

	/**
	 * @return mixed
	 */
	public function getOpens() {
		return $this->opens;
	}

	/**
	 * @param mixed $opens
	 * @return $this
	 */
	public function setOpens($opens) {
		$this->opens = $opens;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getUniqueOpens() {
		return $this->unique_opens;
	}

	/**
	 * @param mixed $unique_opens
	 * @return $this
	 */
	public function setUniqueOpens($unique_opens) {
		$this->unique_opens = $unique_opens;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getClicks() {
		return $this->clicks;
	}

	/**
	 * @param mixed $clicks
	 * @return $this
	 */
	public function setClicks($clicks) {
		$this->clicks = $clicks;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getSubscriberClicks() {
		return $this->subscriber_clicks;
	}

	/**
	 * @param mixed $subscriber_clicks
	 * @return $this
	 */
	public function setSubscriberClicks($subscriber_clicks) {
		$this->subscriber_clicks = $subscriber_clicks;
		return $this;
	}
}