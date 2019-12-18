<?php
/**
 * MembersEntity.php
 *
 * @author    Bernd Engels
 * @created   19.06.19 20:09
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Entities\Newsletter;

use App\Entities\Entity;

/**
 * Class MembersEntity
 */
class MembersEntity extends Entity{

	/**
	 * @var string
	 */
	protected $id;
	/**
	 * @var string
	 */
	protected $email_address;
	/**
	 * @var
	 */
	protected $status;
	/**
	 * @var int
	 */
	protected $member_rating = 0;
	/**
	 * @var StatsEntity
	 */
	protected $stats;

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param string $id
	 * @return $this
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEmailAddress() {
		return $this->email_address;
	}

	/**
	 * @param string $email_address
	 * @return $this
	 */
	public function setEmailAddress($email_address) {
		$this->email_address = $email_address;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param string $status
	 * @return $this
	 */
	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getMemberRating() {
		return $this->member_rating;
	}

	/**
	 * @param int $member_rating
	 * @return $this
	 */
	public function setMemberRating($member_rating) {
		$this->member_rating = $member_rating;
		return $this;
	}

	/**
	 * @return StatsEntity
	 */
	public function getStats() {
		return $this->stats;
	}

	/**
	 * @param StatsEntity $stats
	 * @return $this
	 */
	public function setStats($stats) {
		$this->stats = new StatsEntity($stats);
		return $this;
	}
}