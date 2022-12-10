<?php

namespace App\Traits\Models;

trait Sanitize
{
    public function sanitize(string $value)
    {
        return filter_var(strip_tags($value),FILTER_SANITIZE_STRING);
    }
}
