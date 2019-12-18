<?php
/**
 * ImageForm.php
 *
 * @author    Bernd Engels
 * @created   25.02.19 14:47
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Forms;

use App\Models\Address;
use App\Models\AddressCategory;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class AddressCategoryFilterForm extends MainForm
{
    protected $formOptions = [
        'id'    => 'frmAddressCategoryFilter',
//		'url' => '/admin/addresses',
		'method' => 'GET',
		'class'	=> 'address-category-filter-form form-inline my-0 ml-2',
    ];

    public function buildForm()
    {
		parent::buildForm();
		$addressCategory = request()->get('addressCategory');

		$this
            ->add('addressCategory', Field::ENTITY, [
                'class' => AddressCategory::class,
//				'label'	=> 'Art der Adresse',
				'label_show'    => false,
				'empty_value'  => 'Alle auswählen',
				'selected' => ($addressCategory) ? $addressCategory : null,
				'query_builder' => function (AddressCategory $item) {
					return $item->orderBy('name')->get();
				}
            ])
			->add('btnSubmit', Field::BUTTON_SUBMIT, [
				'label' => '',
				'label_show'    => false,
				'wrapper'	=> [
//					'class' => 'form-group',
				],
				'attr' => [
					'class' => 'btn btn-primary col-sm-auto ion-md-help-circle-outline ml-1 mt-0',
				],
			])
        ;
    }
}
