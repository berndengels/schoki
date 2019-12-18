<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * App\Models\Group
 *
 * @method static Builder|Group newModelQuery()
 * @method static Builder|Group newQuery()
 * @method static Builder|Group query()
 * @mixin Eloquent
 */
class Group extends Model
{
    /**
     * @var string
     */
    protected $table = 'my_goup';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
    ];
}
