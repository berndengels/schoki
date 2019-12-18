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
 * Class AudioEntity
 */
class AudioEntity extends MediaEntity {
	/**
	 * @var int
	 */
	protected $bitrate;
	/**
	 * @var int
	 */
	protected $duration;

    /**
     * @param int $bitrate
     * @return $this
     */
    public function setBitrate(int $bitrate): self
    {
        $this->bitrate = $bitrate;
        return $this;
    }

    /**
     * @param int $duration
     * @return $this
     */
    public function setDuration(int $duration): self
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return int
     */
    public function getBitrate(): int
    {
        return $this->bitrate;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }
}
