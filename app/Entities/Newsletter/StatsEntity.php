<?php
/**
 * StatsEntity.php
 *
 * @author    Bernd Engels
 * @created   19.06.19 20:13
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Entities\Newsletter;

use App\Entities\Entity;

/**
 * Class StatsEntity
 */
class StatsEntity extends Entity {

	/**
	 * @var int
	 */
	protected $avg_open_rate = 0;
	/**
	 * @var int
	 */
	protected $avg_click_rate = 0;

	/**
	 * @return int
	 */
	public function getAvgOpenRate() {
		return $this->avg_open_rate;
	}

	/**
	 * @param int $avg_open_rate
	 * @return $this
	 */
	public function setAvgOpenRate($avg_open_rate) {
		$this->avg_open_rate = $avg_open_rate;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getAvgClickRate() {
		return $this->avg_click_rate;
	}

	/**
	 * @param int $avg_click_rate
	 * @return $this
	 */
	public function setAvgClickRate($avg_click_rate) {
		$this->avg_click_rate = $avg_click_rate;
		return $this;
	}
}