<?php
namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class CategoryForm extends MainForm
{
    protected $formOptions = [
        'method' => 'POST',
        'url' => '/admin/categories/store/',
    ];

    public function buildForm()
    {
        $model	= $this->getModel() ?: null;
        $id     = $model ? $this->getModel()->id : null;

        $this
            ->add('id', Field::HIDDEN)
            ->add('is_published', Field::CHECKBOX)
            ->add('name', Field::TEXT, [
                'rules' => 'required',
            ])
			->add('icon', Field::TEXT)
            ->add('default_price', Field::NUMBER)
            ->add('default_time', Field::TIME)
        ;
		$this->addSubmits();

		if( $id > 0 ) {
            $this->formOptions['url'] .= $id;
        }
    }
}