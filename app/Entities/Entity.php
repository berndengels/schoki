<?php
/**
 * Entity.php
 *
 * @author    Bernd Engels
 * @created   15.06.19 17:12
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Entities;

use App\Entities\Newsletter\ContactEntity;
use Illuminate\Support\Str;
use stdClass;

abstract class Entity {

	public function __construct($attributes = []) {
		if (count($attributes) > 0) {
			foreach ($attributes as $key => $val) {
				$setter = 'set' . ucfirst(Str::camel($key));
				if (property_exists($this, $key) && method_exists($this, $setter)) {
					$this->$setter($val);
				}
			}
		}
	}

	/**
	 * @return array
	 */
	public function toArray() {
		$arr = [];
		foreach (get_class_vars(get_called_class()) as $key => $value) {
			if ($value) {
				$arr[$key] = $value;
			}
		}
		return $arr;
	}

	public static function toStaticObject() {
		$obj = new stdClass();
		foreach (get_class_vars(get_called_class()) as $key => $value) {
			if ($value) {
				$obj->$key = $value;
			}
		}
		return $obj;
	}

	public function toObject() {
		$obj = new stdClass();
		foreach (get_class_vars(get_called_class()) as $key => $v) {
			$getter = 'get' . ucfirst(Str::camel($key));
			if (method_exists($this, $getter)) {
				$value = $this->$getter();
				if ($value) {
					$obj->$key = $value;
				}
			}
		}
		return $obj;
	}
}
