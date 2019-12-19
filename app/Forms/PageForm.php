<?php
namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class PageForm extends MainForm
{
    protected $formOptions = [
        'method' => 'POST',
        'url' => '/admin/pages/store/',
    ];

    public function buildForm()
    {
        $model	= $this->getModel() ?: null;
        $id     = $model ? $this->getModel()->id : null;

        $this
            ->add('id', Field::HIDDEN)
            ->add('is_published', Field::CHECKBOX)
            ->add('title', Field::TEXT, [
            ])
            ->add('body', Field::TEXTAREA, [
                'attr'  => ['id' => 'tinymce'],
            ])
        ;
		$this->addSubmits();

        if( $id > 0 ) {
            $this->formOptions['url'] .= $id;
        }
    }
}
