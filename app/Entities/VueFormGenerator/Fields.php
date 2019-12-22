<?php

namespace App\Entities\VueFormGenerator;

use App\Entities\Entity;

/**
 * Class Fields
 * @package App\Entities\VueFormGenerator
 */
class Fields extends Entity
{
    /**
     * @var string
     */
    public $type        = 'input';
    /**
     * @var string
     */
    public $inputType   = 'text';
    /**
     * @var string
     */
    public $label       = 'Label';
    /**
     * @var string
     */
    public $model       = '';
    /**
     * @var string
     */
    public $placeholder = '';
    /**
     * @var bool
     */
    public $featured    = false;
    /**
     * @var bool
     */
    public $required    = false;
    /**
     * @var string
     */
    public $hint        = '';
    /**
     * @var string
     */
    public $validator   = '';
    /**
     * @var bool
     */
    public $readonly    = false;
    /**
     * @var bool
     */
    public $disabled    = false;
    /**
     * @var array
     */
    public $values      = [];
    /**
     * @var null
     */
    public $default     = null;

    /**
     * @var string
     */
    public $noneSelectedText = 'bitte wählen';
    /**
     * @var bool
     */
    public $hideNoneSelectedText = false;
    /**
     * @var string
     */
    public $value = 'id';
    /**
     * @var null
     */
    public $selectOptions = null;

    /**
     * @var string
     */
    public $buttonText = '';

    /**
     * @var bool
     */
    public $validateBeforeSubmit = false;

    /**
     * @param string $buttonText
     * @return $this
     */
    public function setButtonText(string $buttonText): self
    {
        $this->buttonText = $buttonText;
        return $this;
    }

    /**
     * @param bool $validateBeforeSubmit
     * @return $this
     */
    public function setValidateBeforeSubmit(bool $validateBeforeSubmit): self
    {
        $this->validateBeforeSubmit = $validateBeforeSubmit;
        return $this;
    }

    /**
     * @return string
     */
    public function getButtonText(): string
    {
        return $this->buttonText;
    }

    /**
     * @return bool
     */
    public function isValidateBeforeSubmit(): bool
    {
        return $this->validateBeforeSubmit;
    }

    /**
     * @return string
     */
    public function getNoneSelectedText(): string
    {
        return $this->noneSelectedText;
    }

    /**
     * @return bool
     */
    public function isHideNoneSelectedText(): bool
    {
        return $this->hideNoneSelectedText;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return null
     */
    public function getSelectOptions()
    {
        return $this->selectOptions;
    }

    /**
     * @param string $noneSelectedText
     * @return $this
     */
    public function setNoneSelectedText(string $noneSelectedText): self
    {
        $this->noneSelectedText = $noneSelectedText;
        return $this;
    }

    /**
     * @param bool $hideNoneSelectedText
     * @return $this
     */
    public function setHideNoneSelectedText(bool $hideNoneSelectedText): self
    {
        $this->hideNoneSelectedText = $hideNoneSelectedText;
        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param $selectOptions
     * @return $this
     */
    public function setSelectOptions($selectOptions): self
    {
        $this->selectOptions = $selectOptions;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getInputType(): string
    {
        return $this->inputType;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    /**
     * @return bool
     */
    public function isFeatured(): bool
    {
        return $this->featured;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @return string
     */
    public function getHint(): string
    {
        return $this->hint;
    }

    /**
     * @return string
     */
    public function getValidator(): string
    {
        return $this->validator;
    }

    /**
     * @return bool
     */
    public function isReadonly(): bool
    {
        return $this->readonly;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @return null
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $inputType
     */
    public function setInputType(string $inputType): self
    {
        $this->inputType = $inputType;
        return $this;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @param string $model
     */
    public function setModel(string $model): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @param string $placeholder
     */
    public function setPlaceholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * @param bool $featured
     */
    public function setFeatured(bool $featured): self
    {
        $this->featured = $featured;
        return $this;
    }

    /**
     * @param bool $required
     */
    public function setRequired(bool $required): self
    {
        $this->required = $required;
        return $this;
    }

    /**
     * @param string $hint
     */
    public function setHint(string $hint): self
    {
        $this->hint = $hint;
        return $this;
    }

    /**
     * @param string $validator
     */
    public function setValidator(string $validator): self
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * @param bool $readonly
     */
    public function setReadonly(bool $readonly): self
    {
        $this->readonly = $readonly;
        return $this;
    }

    /**
     * @param bool $disabled
     */
    public function setDisabled(bool $disabled): self
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * @param array $values
     */
    public function setValues(array $values): self
    {
        $this->values = $values;
        return $this;
    }

    /**
     * @param $default
     */
    public function setDefault($default): self
    {
        $this->default = $default;
        return $this;
    }
}
