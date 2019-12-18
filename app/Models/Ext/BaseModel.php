<?php
/**
 * BaseModel.php
 *
 * @author    Bernd Engels
 * @created   13.03.19 14:54
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Models\Ext;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Ext\BaseModel
 *
 * @method static Builder|BaseModel newModelQuery()
 * @method static Builder|BaseModel newQuery()
 * @method static Builder|BaseModel query()
 * @mixin Eloquent
 */
class BaseModel extends Model
{
	public $paginationLimit = 10;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
	}

}
