<?php
/**
 * ImageEntity.php
 *
 * @author    Bernd Engels
 * @created   02.07.19 19:31
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Entities;

/**
 * Class ImageEntity
 */
class ImageEntity extends MediaEntity {
	/**
	 * @var int
	 */
	protected $width;
	/**
	 * @var int
	 */
	protected $height;

	/**
	 * @return int
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * @param int $width
	 * @return $this
	 */
	public function setWidth($width) {
		$this->width = $width;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * @param int $height
	 * @return $this
	 */
	public function setHeight($height) {
		$this->height = $height;
		return $this;
	}
}
