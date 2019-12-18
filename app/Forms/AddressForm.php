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
use Kris\LaravelFormBuilder\Field;

class AddressForm extends MainForm
{
    protected $formOptions = [
        'id'    => 'frmAddress',
        'method' => 'POST',
		'url' => '/admin/addresses/store/',
    ];

    public function buildForm()
    {
		parent::buildForm();
		$model	= $this->getModel() ?: null;
		$id     = $model ? $this->getModel()->id : null;
        $this
            ->add('address_category_id', Field::ENTITY, [
                'class' => AddressCategory::class,
				'label'	=> 'Art der Adresse',
                'empty_value'  => 'Bitte wählen ...',
				'selected' => ($model) ? $model->address_category_id : null,
				'query_builder' => function (AddressCategory $item) {
					return $item->orderBy('name')->get();
				}
            ])
            ->add('email', Field::EMAIL)
        ;
		$this->addSubmits();

		if( $id > 0 ) {
			$this->formOptions['url'] .= $id;
		}
    }
}
