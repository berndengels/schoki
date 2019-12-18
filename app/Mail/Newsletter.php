<?php
/**
 * Newsletter.php
 *
 * @author    Bernd Engels
 * @created   13.06.19 14:02
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Mail;

use App\Models\Event;
use App\Models\Address;
use App\Repositories\EventEntityRepository;
use App\Repositories\EventPeriodicRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\Newsletter\Newsletter as SpatieNewsletter;

class Newsletter {

	private $_campaine = 'SchokiEvents';
	private $_addresses;

	public function __construct() {

	}

	public function getAdresses($category) {
		$adresses = Address::whereHas('addressCategory', function ($query) use ($category) {
			$query->where('name', $category);
		});
		/**
		 * @var $address Address
		 */
		foreach ($adresses as $address) {
			if (!SpatieNewsletter::isSubscribed($address->email)) {
				SpatieNewsletter::subscribe($address->email);
			}
		}

		$api = $this->getApi();
		$data = [
			'template' => [
				'id' => 1,
				'section' => [
					'body' => 'myText',
				]
			]
		];
		$api->put('/campaigns/' . $this->_campaine . '/content', $data);
	}
}