<?php
/**
 * NewsletterStatus.php
 *
 * @author    Bernd Engels
 * @created   15.06.19 13:01
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\NewsletterStatus
 *
 * @property int $id
 * @property string $name
 * @method static Builder|NewsletterStatus newModelQuery()
 * @method static Builder|NewsletterStatus newQuery()
 * @method static Builder|NewsletterStatus query()
 * @method static Builder|NewsletterStatus whereId($value)
 * @method static Builder|NewsletterStatus whereName($value)
 * @mixin Eloquent
 */
class NewsletterStatus extends Model
{
	/**
	 * @var string
	 */
	protected $table = 'newsletter_status';
	/**
	 * @var array
	 */
	protected $fillable = ['name'];

}
