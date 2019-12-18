<?php
namespace App\Forms;

use Kris\LaravelFormBuilder\Field;

class MusicStyleForm extends MainForm
{
    protected $formOptions = [
        'method' => 'POST',
        'url' => '/admin/musicStyles/store/',
    ];

    public function buildForm()
    {
        $model	= $this->getModel() ?: null;
        $id     = $model ? $this->getModel()->id : null;

        $this
            ->add('id', Field::HIDDEN)
            ->add('name', Field::TEXT, [
                'rules' => 'required',
            ])
        ;
		$this->addSubmits();

		if( $id > 0 ) {
            $this->formOptions['url'] .= $id;
        }
    }
}