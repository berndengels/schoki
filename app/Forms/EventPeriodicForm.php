<?php
/**
 * EventForm.php
 *
 * @author    Bernd Engels
 * @created   30.01.19 22:55
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Forms;

use Carbon\Carbon;
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
    ];

    public function buildForm()
    {
        $model      = $this->getModel() ?: null;
        $id         = $model ? $this->getModel()->id : null;
        $categoryId = $model ? $this->getModel()->category_id : null;
        $themeId    = $model ? $this->getModel()->theme_id : null;
		$eventTime	= ($model && $model->event_time) ? str_replace('.',':',$model->event_time) : config('event.defaultEventTime');
        $periodicPosition = $model ? $model->periodic_position : null;
        $periodicWeekday  = $model ? $model->periodic_weekday : null;

        $this
            ->add('id','hidden')
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
                'empty_value'  => 'Bitte wählen ...',
            ])
            ->add('periodic_position', Field::SELECT, [
                'label' => 'Position',
                'choices'   => config('event.periodicPosition'),
                'selected'  => $periodicPosition,
                'empty_value'  => $id ? null : 'Bitte wählen ...',
                'rules' => ['required'],
                'error_messages' => [
                    'periodic_position.required' => 'Bitte eine Datums-Position angeben.'
                ],
            ])
            ->add('periodic_weekday', Field::SELECT, [
                'label' => 'Wochentag',
                'choices'   => config('event.periodicWeekday'),
                'selected'  => $periodicWeekday,
                'empty_value'  => $id ? null : 'Bitte wählen ...',
                'rules' => ['required'],
                'error_messages' => [
                    'periodic_position.required' => 'Bitte einen Wochentag angeben.'
                ],
            ])
            ->add('periodic_dates', 'static', [
                'tag' => 'div', // Tag to be used for holding static data,
                'label' => 'periodische Termine',
                'label_show' => true,
                'attr' => [
                    'class' => 'datepicker',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'yyyy-mm-dd',
                    'data-date-end-date' => Carbon::now('Europe/Berlin')->addMonth(6)->format('Y-m-d'),
                ],
            ])

			->add('event_time', Field::TIME, [
//				'rules' => 'required',
				'default_value'	=> $eventTime,
				'wrapper' => [
					'class' => 'form-group clear mt-2'
				]
			])
            ->add('title', Field::TEXT, [
            ])
            ->add('subtitle', Field::TEXT, [
            ])
			->add('promoter', Field::TEXT, [
				'label'	=> 'Promoter',
				'wrapper' => [
					'class' => 'form-group'
				],
				'attr'  => [
					'class' => 'form-control',
//					'maxlength' => '100',
				]
			])
			->add('dj', Field::TEXT, [
				'label'	=> 'DJ / Party',
				'wrapper' => [
					'class' => 'form-group'
				],
				'attr'  => [
					'class' => 'form-control',
//					'maxlength' => '100',
				]
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
