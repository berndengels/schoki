<?php
/**
 * RecipientsEntity.php
 *
 * @author    Bernd Engels
 * @created   15.06.19 16:56
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Entities\Newsletter;

use App\Entities\Entity;

/**
 * Class RecipientsEntity
 */
class RecipientsEntity extends Entity {
	/**
	 * @var
	 */
	protected $list_id;
	/**
	 * @var
	 */
	protected $list_name;
	/**
	 * @var
	 */
	protected $list_is_active;
	/**
	 * @var
	 */
	protected $recipient_count;
	/**
	 * @var SegmentOpts
	 */
	protected $segment_opts;
	/**
	 * @return mixed
	 */
	public function getListId() {
		return $this->list_id;
	}

	/**
	 * @param $list_id
	 * @return $this
	 */
	public function setListId($list_id) {
		$this->list_id = $list_id;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getListName() {
		return $this->list_name;
	}

	/**
	 * @param $list_name
	 * @return $this
	 */
	public function setListName($list_name) {
		$this->list_name = $list_name;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getListIsActive() {
		return $this->list_is_active;
	}

	/**
	 * @param $list_is_active
	 * @return $this
	 */
	public function setListIsActive($list_is_active) {
		$this->list_is_active = $list_is_active;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getRecipientCount() {
		return $this->recipient_count;
	}

	/**
	 * @param $recipient_count
	 * @return $this
	 */
	public function setRecipientCount($recipient_count) {
		$this->recipient_count = $recipient_count;
		return $this;
	}

	/**
	 * @return SegmentOpts
	 */
	public function getSegmentOpts() {
		return $this->segment_opts;
	}

	/**
	 * @param SegmentOpts $segment_opts
	 * @return $this
	 */
	public function setSegmentOpts($segment_opts) {
		$this->segment_opts = new SegmentOptsEntity($segment_opts);
		return $this;
	}
}