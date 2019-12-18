<?php

namespace App\Entities\VueFormGenerator;

use App\Entities\Entity;
use phpDocumentor\Reflection\Types\This;

class Schema extends Entity
{
    /**
     * @var Fields
     */
    public $fields = [];

    public function __construct($fields = [])
    {
        if(count($fields) > 0) {
            $this->fields = collect($fields)->values();
        }
    }
}
