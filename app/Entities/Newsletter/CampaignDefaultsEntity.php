<?php
/**
 * CampaignDefaults.php
 *
 * @author    Bernd Engels
 * @created   15.06.19 21:19
 * @copyright Webwerk Berlin GmbH
 */


namespace App\Entities\Newsletter;

use App\Entities\Entity;

class CampaignDefaultsEntity extends Entity {

	protected $from_name = 'Schokoladen Berlin-Mitte';
	protected $from_email = 'newsletter@schokoladen-mitte.de';
	protected $subject = 'Schokoladen Events';
	protected $language = 'DE';

	/**
	 * @return string
	 */
	public function getFromName() {
		return $this->from_name;
	}

	/**
	 * @return string
	 */
	public function getFromEmail() {
		return $this->from_email;
	}

	/**
	 * @return string
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * @return string
	 */
	public function getLanguage() {
		return $this->language;
	}
}