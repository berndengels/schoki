<?php
/**
 * ImageForm.php
 *
 * @author    Bernd Engels
 * @created   25.02.19 14:47
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Forms;

use App\Models\Newsletter;
use App\Models\AddressCategory;
use Carbon\Carbon;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class NewsletterForm extends MainForm
{
    protected $formOptions = [
        'id'    => 'frmNewsletter',
        'method' => 'POST',
		'url' => '/admin/newsletter/process',
    ];

    public function buildForm()
    {
		parent::buildForm();
		$model	= $this->getModel() ?: null;
		$data 	= $this->getData('data');

		/**
		 * @var $from Carbon
		 * @var $until Carbon
		 */
		$campaignId	= $data['campaignId'];
		$from		= $data['fromDefault'];
		$until		= $data['untilDefault'];
		$fromUS		= $from->format('Y-m-d');
		$untilUS 	= $until->format('Y-m-d');
		$fromDE 	= $from->format('d.m.Y');
		$untilDE 	= $until->format('d.m.Y');

        $this
			->add('campaignId', Field::TEXT, [
				'label' => 'Campaign ID',
				'attr'	=> [
					'disabled' => true,
				],
				'default_value'	=> $campaignId,
			])
			->add('tag_id', Field::ENTITY, [
				'rules' => 'required',
				'class' => AddressCategory::class,
				'label'	=> 'Empfänger Gruppe',
				'empty_value'  => 'Bitte wählen ...',
				'property_key'	=> 'tag_id',
				'selected' => ($model) ? $model->tag_id : null,
				'query_builder' => function (AddressCategory $item) {
					$item = $item->orderBy('name');
					if(app()->environment() !== 'prod') {
						$item = $item->where('name','Test');
					}
					return $item->get();
				}
			])
			->add('from', Field::DATE, [
				'label' => 'Events ab wann',
				'rules' => 'required',
				'default_value'	=> $fromUS,
			])
			->add('until', Field::DATE, [
				'label' => 'Events bis wann',
				'rules' => 'required',
				'default_value'	=> $untilUS,
			])
			->add('title', Field::TEXT, [
				'label' => 'Newsletter Titel',
				'default_value'	=> "Schokoladen Events vom $fromDE bis $untilDE",
			])
            ->add('header', Field::TEXTAREA, [
				'label' => 'Newsletter Einleitungs-Text',
				'attr'	=> [
					'placeholder'	=> 'Newsletter Einleitungs-Text',
				]
            ])
			->add('btnPreview', Field::BUTTON_SUBMIT, [
				'label' => 'Vorschau',
				'attr' => [
					'class' => 'btn btn-primary col-12 col-sm-auto',
					'name' => 'submit',
					'value' => 'preview',
				],
			])
			->add('btnCreate', Field::BUTTON_SUBMIT, [
				'label' => $campaignId ? 'Edit' : 'Create',
				'attr' => [
					'class' => 'btn btn-primary col-12 col-sm-auto',
					'name' => 'submit',
					'value' => $campaignId ? 'edit' : 'create',
				],
			])
			->add('btnCheck', Field::BUTTON_SUBMIT, [
				'label' => 'Prüfen',
				'attr' => [
					'class' => 'btn btn-primary col-12 col-sm-auto',
					'name' => 'submit',
					'value' => 'check',
				],
			])
			->add('btnTest', Field::BUTTON_SUBMIT, [
				'label' => 'Test-Mail',
				'attr' => [
					'class' => 'btn btn-primary col-12 col-sm-auto',
					'name' => 'submit',
					'value' => 'test',
				],
			])
			->add('btnSend', Field::BUTTON_SUBMIT, [
				'label' => 'Sende Newsletter',
				'attr' => [
					'class' => 'btn btn-primary col-12 col-sm-auto',
					'name' => 'submit',
					'value' => 'send',
				],
			])
        ;
    }
}
