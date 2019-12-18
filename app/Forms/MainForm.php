<?php
/**
 * AbtractForm.php
 *
 * @author    Bernd Engels
 * @created   04.04.19 23:41
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class MainForm extends Form {

	protected function addSubmits()
	{
		$this
			->addSubmit()
			->addSubmitAndBack()
		;

		return $this;
	}

	protected function addSubmit()
	{
		$this->add('btnSubmit', Field::BUTTON_SUBMIT, [
			'label' => 'Speichern',
			'attr' => [
				'class' => 'btn btn-primary col-12 col-sm-auto',
				'name' => 'submit',
				'value' => 'save',
			],
		]);

		return $this;
	}

	protected function addSubmitAndBack()
	{
		$this->add('btnSubmitAndBack', Field::BUTTON_SUBMIT, [
			'label' => 'Speichern und zurück zur Liste',
			'attr' => [
				'class' => 'btn btn-primary ml-sm-2 col-12 col-sm-auto',
				'name' => 'submit',
				'value' => 'saveAndBack',
			],
		]);

		return $this;
	}

	protected function addRemove()
	{
		$this->add('btnRemove', Field::BUTTON_BUTTON, [
			'label' => 'Löschen',
			'attr' => [
				'id'	=> 'btnRemove',
				'class'	=> 'btn btn-danger col-12 col-sm-auto mt-xs-2 float-right d-none softdel',
				'name'	=> 'submit',
				'value'	=> 'remove',
			],
		]);
	}
}