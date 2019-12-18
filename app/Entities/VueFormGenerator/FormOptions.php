<?php

namespace App\Entities\VueFormGenerator;

use App\Entities\Entity;

/**
 * Class FormOptions
 * @package App\Entities\VueFormGenerator
 */
class FormOptions extends Entity
{
    /**
     * @var bool
     */
    public $validateAsync           = true;
    /**
     * @var bool
     */
    public $validateAfterLoad       = true;
    /**
     * @var bool
     */
    public $validateAfterChanged    = true;

    /**
     * @return bool
     */
    public function isValidateAsync(): bool
    {
        return $this->validateAsync;
    }

    /**
     * @return bool
     */
    public function isValidateAfterLoad(): bool
    {
        return $this->validateAfterLoad;
    }

    /**
     * @return bool
     */
    public function isValidateAfterChanged(): bool
    {
        return $this->validateAfterChanged;
    }

    /**
     * @param bool $validateAsync
     * @return $this
     */
    public function setValidateAsync(bool $validateAsync): self
    {
        $this->validateAsync = $validateAsync;
        return $this;
    }

    /**
     * @param bool $validateAfterLoad
     * @return $this
     */
    public function setValidateAfterLoad(bool $validateAfterLoad): self
    {
        $this->validateAfterLoad = $validateAfterLoad;
        return $this;
    }

    /**
     * @param bool $validateAfterChanged
     * @return $this
     */
    public function setValidateAfterChanged(bool $validateAfterChanged): self
    {
        $this->validateAfterChanged = $validateAfterChanged;
        return $this;
    }


}
