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
use App\Models\EventTemplate;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class EventTemplateSelectForm extends MainForm
{
    protected $formOptions = [
        'id'    => 'frmEventTemplateSelect',
//		'url' => '/admin/events/template',
		'method' => 'POST',
		'class'	=> 'event-template-select-form form-inline my-0 ml-2',
    ];

    public function buildForm()
    {
		parent::buildForm();
        $this
            ->add('eventTemplate', Field::ENTITY, [
                'class' => EventTemplate::class,
				'label'	=> 'oder aus Template erstellen &nbsp;',
				'empty_value'  => 'Bitte wählen ...',
				'property' => 'title',
				'query_builder' => function (EventTemplate $item) {
					return $item->orderBy('id')->get();
				},
            ])
			->add('btnSubmit', Field::BUTTON_SUBMIT, [
				'label' => 'OK',
				'label_show'    => false,
				'wrapper'	=> [
//					'class' => 'form-group',
				],
				'attr' => [
					'class' => 'btn btn-primary col-sm-auto ml-1 mt-0',
				],
			])
        ;
    }
}