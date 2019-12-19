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
                'rules' => 'required',
            ])
            ->add('body', Field::TEXTAREA, [
                'attr'  => ['id' => 'tinymce'],
            ])
        ;
/*
		if( $model && $model->audios && $model->audios->count() > 0 ) {
			$this->add('audios', 'collection', [
				'prototype'     => true,
				'type'          => 'form',
				'label_show'    => false,
				'wrapper'       => [
					'id'	=> 'audios',
//					'class' => 'form-group event-images collapseImages multi-collapse show',
					'class' => 'form-group page-audios collapseAudios multi-collapse show',
				],
				'options'   => [
					'class' => AudiosForm::class,
					'label' => false,
				]
			]);
		}
*/
		$this->addSubmits();

        if( $id > 0 ) {
            $this->formOptions['url'] .= $id;
        }
    }
}
