<?php
/**
 * MessageFilterForm.php
 *
 * @author    Bernd Engels
 * @created   16.04.19 23:28
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Forms\Filter;

use App\Models\MusicStyle;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class MessageFilterForm extends Form {

	protected $formOptions = [
		'method'	=> 'GET',
		'url'		=> '/admin/messages',
		'class'		=> 'form-horizontal',
	];

	public function buildForm()
	{
		$data	= $this->getData();
		$this
			->add('musicStyle', Field::ENTITY, [
				'class' => MusicStyle::class,
				'label'	=> 'Musikstil',
				'selected' => $data['musicStyle'],
				'empty_value' => 'Bitte wählen ...',
				'wrapper'       => [
					'class' => 'form-group d-inline',
				],
				'attr' => [
					'class' => 'form-group d-inline',
				],
			])
			->add('submit', Field::BUTTON_SUBMIT, [
				'label'	=> '',
				'wrapper'       => [
					'class' => 'form-group d-inline',
				],
				'attr' => [
					'class' => 'form-group d-inline fa fa-search',
				],
			])
		;
	}
}