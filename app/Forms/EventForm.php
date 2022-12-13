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

class EventForm extends MainForm
{
    protected $formOptions = [
        'method' => 'POST',
        'id'    => 'frmDropzone',
        'class' => 'dropzone',
        'url' => '/admin/events/store/',
    ];

    public function buildForm()
    {
        $model      = $this->getModel() ?: null;
        $id         = $model ? $model->id : null;
        $isPeriodic = ($id) ? $model->is_periodic : 0;
        $categoryId = ($model && $model->category) ? $model->category->id : null;
        $themeId    = ($model && $model->theme) ? $model->theme->id : null;
		$eventTime	= ($model && $model->event_time) ? str_replace('.',':',$model->event_time) : config('event.defaultEventTime');

		if(null === $id ) {
			$this->add('template', Field::HIDDEN);
		}
        $this
            ->add('is_periodic', Field::HIDDEN, [
                'attr' => [
                    'id' => 'isPeriodic',
                ],
                'value' => $isPeriodic,
            ])
            ->add('id', Field::HIDDEN, [
				'attr' => [
					'id' => 'id',
				]
			])
			->add('override', Field::HIDDEN, [
				'attr' => [
					'id' => 'override',
				]
			])
            ->add('is_published', Field::CHECKBOX)
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
//                'empty_value'  => $id ? null : 'Bitte wählen ...',
                'empty_value'  => 'Bitte wählen ...',
            ])
/*
			->add('event_date',Field::DATE, [
				'rules' => ['required'],
				'label' => 'Datum',
				'attr'	=> [
					'min' => $today,
				],
				'wrapper' => [
					'id'	=> 'wrapperEventDate',
					'class' => 'form-group'
				],
				'help_block' => [
					'id'	=> 'wrapperEventDate',
					'text' => 'Dieses Datum ist bereits einem periodischen Event zugewiesen (ID %ID%, "%TITLE%", %DATE%).<br>Sollen die Daten desselben übernommen werden, um es zu überschreiben?',
					'tag' => 'p',
					'attr' => ['class' => 'd-none help-block text-danger bold mt-2']
				],
			])
*/

            ->add('event_date',Field::TEXT, [
                'label' => 'Datum',
				'wrapper' => [
					'id'	=> 'wrapperEventDate',
					'class' => 'form-group'
				],
				'help_block' => [
					'id'	=> 'wrapperEventDate',
					'text' => 'Dieses Datum ist bereits einem periodischen Event zugewiesen (ID %ID%, "%TITLE%", %DATE%).<br>Sollen die Daten desselben übernommen werden, um es zu überschreiben?',
					'tag' => 'p',
					'attr' => ['class' => 'd-none help-block text-danger bold']
				],
                'attr'  => [
					'class' => 'form-control datepicker',
                    'data-provide' => 'datepicker',
//                    'data-date-format'  => 'dd.mm.yyyy',
                    'data-date-format'  => 'yyyy-mm-dd',
                    'readonly' => 'readonly',
                ]
            ])

			->add('btnSubmitOverride', Field::BUTTON_SUBMIT, [
				'label' => 'Ja, Daten übernehmen',
				'wrapper' => [
					'id'	=> 'wrapperBtnSubmitOverride',
					'class' => 'd-none form-group mt-0'
				],
				'attr' => [
					'class' => 'btn btn-danger col-12 col-sm-auto',
					'formaction' => '/admin/events/override/'.$id,
					'name' => 'submit',
					'value' => 'override',
				],
			])
			->add('confirmReset', Field::BUTTON_BUTTON, [
				'wrapper' => [
					'id'	=> 'wrapperConfirmReset',
					'class' => 'd-none form-group'
				],
				'label' => 'Datum zurücksetzen',
				'attr'  => [
					'class' => 'form-control btn btn-info',
//					'readonly' => 'readonly',
				]
			])
            ->add('event_time', Field::TIME, [
				'default_value' => $eventTime,
            ])
            ->add('title', Field::TEXT, [
            ])
            ->add('subtitle', Field::TEXT, [
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
