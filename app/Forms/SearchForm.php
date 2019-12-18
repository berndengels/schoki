<?php
namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class SearchForm extends Form
{
    protected $formOptions = [
        'method' => 'GET',
		'class'	=> 'search-form form-inline my-2 my-md-0',
    ];

    public function buildForm()
    {
		$this->formOptions['url'] = $this->getFormOption('url');
        $this
            ->add('search', Field::TEXT, [
				'label_show'    => false,
				'attr' => [
					'placeholder'	=> 'Suche',
				],
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