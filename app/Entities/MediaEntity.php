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
 * Class MediaEntity
 */
class MediaEntity extends Entity {

    /**
     * @var
     */
    protected $id;
	/**
	 * @var string
	 */
	protected $internalName;
	/**
	 * @var string
	 */
	protected $externalName;
	/**
	 * @var string
	 */
	protected $title;
	/**
	 * @var string
	 */
	protected $extension;
	/**
	 * @var int
	 */
	protected $filesize;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

	/**
	 * @return string
	 */
	public function getInternalName() {
		return $this->internalName;
	}

	/**
	 * @param string $internalName
	 * @return $this
	 */
	public function setInternalName($internalName) {
		$this->internalName = $internalName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getExternalName() {
		return $this->externalName;
	}

	/**
	 * @param string $externalName
	 * @return $this
	 */
	public function setExternalName($externalName) {
		$this->externalName = $externalName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return $this
	 */
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getExtension() {
		return $this->extension;
	}

	/**
	 * @param string $extension
	 * @return $this
	 */
	public function setExtension($extension) {
		$this->extension = $extension;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getFilesize() {
		return $this->filesize;
	}

	/**
	 * @param int $filesize
	 * @return $this
	 */
	public function setFilesize($filesize) {
		$this->filesize = $filesize;
		return $this;
	}
}
