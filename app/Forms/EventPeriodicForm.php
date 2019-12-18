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
use Kris\LaravelFormBuilder\Field;

class EventPeriodicForm extends MainForm
{
    protected $formOptions = [
        'id'        => 'frmEventPeriodic',
        'method'    => 'POST',
        'files'     => true,
        'enctype'   => 'multipart/form-data',
        'url'       => '/admin/eventsPeriodic/store/',
//        'class'     => 'dropzone',
    ];

    public function buildForm()
    {
        $model      = $this->getModel() ?: null;
        $id         = $model ? $this->getModel()->id : null;
        $categoryId = $model ? $this->getModel()->category_id : null;
        $themeId    = $model ? $this->getModel()->theme_id : null;
		$eventTime	= ($model && $model->event_time) ? str_replace('.',':',$model->event_time) : config('event.defaultEventTime');

        $this
            ->add('id','hidden')
            ->add('is_published', Field::CHECKBOX)
            ->add('category_id', Field::ENTITY, [
                'class' => 'App\Models\Category',
                'selected'  => $categoryId,
                'empty_value'  => $id ? null : 'Bitte wählen ...',
            ])
            ->add('theme_id', Field::ENTITY, [
                'class' => 'App\Models\Theme',
                'selected'  => $themeId,
                'empty_value'  => 'Bitte wählen ...',
            ])
            ->add('periodicDate', 'form', [
                'label' => 'Termin',
                'class' => $this->formBuilder->create(PeriodicDateForm::class, [], [
                    'model' => $model,
                ]),
            ])
			->add('event_time', Field::TIME, [
//				'rules' => 'required',
				'default_value'	=> $eventTime,
				'wrapper' => [
					'class' => 'form-group clear mt-2'
				]
			])
            ->add('title', Field::TEXT, [
//				'rules' => 'required|min:3|max:160'
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
