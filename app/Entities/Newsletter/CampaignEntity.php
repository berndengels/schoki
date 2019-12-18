<?php
/**
 * CampaignEntity.php
 *
 * @author    Bernd Engels
 * @created   15.06.19 16:45
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Entities\Newsletter;

use Carbon\Carbon;
use App\Entities\Entity;

class CampaignEntity extends Entity {

	protected $id;
	protected $type;
	/**
	 * @var Carbon
	 */
	protected $create_time;
	protected $status;
	protected $emails_sent;
	/**
	 * @var Carbon
	 */
	protected $send_time;
	protected $content_type;
	/**
	 * @var RecipientsEntity
	 */
	private $recipients;
	/**
	 * @var SettingsEntity
	 */
	private $settings;
	/**
	 * @var ReportSummaryEntity
	 */
	private $report_summary;

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 * @return $this
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getContentType() {
		return $this->content_type;
	}

	/**
	 * @param mixed $content_type
	 * @return $this
	 */
	public function setContentType($content_type) {
		$this->content_type = $content_type;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param mixed $type
	 * @return $this
	 */
	public function setType($type) {
		$this->type = $type;
		return $this;
	}

	/**
	 * @return Carbon
	 */
	public function getCreateTime() {
		return $this->create_time;
	}

	/**
	 * @param mixed $create_time
	 * @return $this
	 */
	public function setCreateTime($create_time) {
		$this->create_time = Carbon::createFromTimeString($create_time);
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param mixed $status
	 * @return $this
	 */
	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getEmailsSent() {
		return $this->emails_sent;
	}

	/**
	 * @param mixed $emails_sent
	 * @return $this
	 */
	public function setEmailsSent($emails_sent) {
		$this->emails_sent = $emails_sent;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getSendTime() {
		return $this->send_time;
	}

	/**
	 * @param mixed $send_time
	 * @return $this
	 */
	public function setSendTime($send_time) {
		if ($send_time && '' != $send_time) {
			$this->send_time = Carbon::createFromTimeString($send_time);
		}
		return $this;
	}

	/**
	 * @return RecipientsEntity
	 */
	public function getRecipients() {
		return $this->recipients;
	}

	/**
	 * @param RecipientsEntity $recipients
	 * @return $this
	 */
	public function setRecipients($recipients) {
		$this->recipients = new RecipientsEntity($recipients);
		return $this;
	}

	/**
	 * @return SettingsEntity
	 */
	public function getSettings() {
		return $this->settings;
	}

	/**
	 * @param SettingsEntity $settings
	 * @return $this
	 */
	public function setSettings($settings) {
		$this->settings = new SettingsEntity($settings);
		return $this;
	}

	/**
	 * @return ReportSummaryEntity
	 */
	public function getReportSummary() {
		return $this->report_summary;
	}

	/**
	 * @param ReportSummaryEntity $report_summary
	 */
	public function setReportSummary($report_summary) {
		$this->report_summary = new ReportSummaryEntity($report_summary);
		return $this;
	}
}