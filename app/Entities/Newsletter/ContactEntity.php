<?php
/**
 * ContactEntity.php
 *
 * @author    Bernd Engels
 * @created   15.06.19 20:49
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Entities\Newsletter;

use App\Entities\Entity;

/**
 * Class ContactEntity
 */
class ContactEntity extends Entity {

	/**
	 * @var string
	 */
	protected $company = 'Schokoladen Berlin-Mitte';
	/**
	 * @var string
	 */
	protected $address1 = 'Ackerstrasse 169';
	/**
	 * @var string
	 */
	protected $city = 'Berlin';
	/**
	 * @var string
	 */
	protected $zip = '10115';
	/**
	 * @var string
	 */
	protected $country = 'DE';
	/**
	 * @var string
	 */
	protected $state = '';

	/**
	 * @return string
	 */
	public function getCompany() {
		return $this->company;
	}

	/**
	 * @return string
	 */
	public function getAddress1() {
		return $this->address1;
	}

	/**
	 * @return string
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * @return string
	 */
	public function getZip() {
		return $this->zip;
	}

	/**
	 * @return string
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * @return string
	 */
	public function getState() {
		return $this->state;
	}
}