<?php
/**
 * EventForm.php
 *
 * @author    Bernd Engels
 * @created   30.01.19 22:55
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Forms;

use App\Models\Image;
use App\Forms\ImagesForm;
use Carbon\Carbon;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;
use App\Forms\Fields\DatePickerType;

class EventTemplateForm extends MainForm
{
    protected $formOptions = [
        'method' => 'POST',
        'id'    => 'frmDropzone',
        'class' => 'dropzone',
        'url' => '/admin/eventsTemplate/store/',
    ];

    public function buildForm()
    {
        $model      = $this->getModel() ?: null;
        $id         = $model ? $model->id : null;
        $categoryId = ($model && $model->category) ? $model->category->id : null;
        $themeId    = ($model && $model->theme) ? $model->theme->id : null;
        $this
            ->add('id', Field::HIDDEN, [
				'attr' => [
					'id' => 'id',
				]
			])
            ->add('category_id', Field::ENTITY, [
                'label' => 'Kategorie',
                'class' => 'App\Models\Category',
                'selected'  => $categoryId,
                'empty_value'  => $id ? null : 'Bitte wählen ...',
            ])
            ->add('theme_id', Field::ENTITY, [
                'label' => 'Thema',
                'class' => 'App\Models\Theme',
                'selected'  => $themeId,
                'empty_value'  => 'Bitte wählen ...',
            ])
            ->add('title', Field::TEXT, [
//                'rules' => 'required|min:5|max:160'
            ])
            ->add('subtitle', Field::TEXT, [
//                'rules' => 'max:100'
            ])
            ->add('description', Field::TEXTAREA, [
                'attr'  => ['id' => 'tinymce'],
            ])
            ->add('links', Field::TEXTAREA, [
                'value' => function ($val = null) {
                    return ($val && $val->count() > 0) ? $val->join("\n") : '';
                },
                'help_block' => [
                    'text' => 'Mehrere Links bitte untereinander eintragen!',
                    'tag' => 'p',
                    'attr' => ['class' => 'help-block text-info font-weight-bold']
                ],
            ]);

        if( $model && $model->images && $model->images->count() > 0 ) {
            $this->add('images', 'collection', [
                'prototype'     => true,
                'type'          => 'form',
                'label_show'    => false,
                'wrapper'       => [
					'id'	=> 'images',
                    'class' => 'form-group event-images collapseImages multi-collapse show',
                ],
                'options'   => [
                    'class' => ImagesForm::class,
                    'label' => false,
                ]
            ]);
        }
		$this->addSubmits();

		$id = $this->getField('id')->getValue();
        if( $id > 0 ) {
            $this->formOptions['url'] .= $id;
        }
    }
}
