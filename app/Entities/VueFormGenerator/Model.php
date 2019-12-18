<?php

namespace App\Entities\VueFormGenerator;

use App\Entities\Entity;
use stdClass;

/**
 * Class Model
 * @package App\Entities\VueFormGenerator
 */
class Model extends stdClass
{
    /**
     * Model constructor.
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        if( count($attributes) > 0) {
            foreach($attributes as $name => $value) {
                if(is_string($name)) {
                    $this->$name = $value;
                }
            }
        }
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function set($name, $value): self
    {
        $this->$name = $value;
        return $this;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function get($name): string
    {
       return $this->$name;
    }
}
