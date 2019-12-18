<?php
/**
 * ImageForm.php
 *
 * @author    Bernd Engels
 * @created   25.02.19 14:47
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Forms;

use App\Forms\MediaForm;
use Kris\LaravelFormBuilder\Field;

class VideosForm extends MediaForm
{
    public function buildForm()
    {
        parent::buildForm();
    }
}
