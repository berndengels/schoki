<?php
/**
 * ImageForm.php
 *
 * @author    Bernd Engels
 * @created   25.02.19 14:47
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Forms;

use App\Models\AddressCategory;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class AddressCategoryForm extends MainForm
{
    protected $formOptions = [
        'id'    => 'frmAddressCategory',
        'method' => 'POST',
		'url' => '/admin/addressCategories/store/',
    ];

    public function buildForm()
    {
		parent::buildForm();
		$model	= $this->getModel() ?: null;
		$id     = $model ? $this->getModel()->id : null;
        $this->add('name', Field::TEXT );
		$this->addSubmits();

		if( $id > 0 ) {
			$this->formOptions['url'] .= $id;
		}
    }
}
