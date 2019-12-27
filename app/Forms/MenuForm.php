<?php
namespace App\Forms;

use App\Models\MenuItemType;
//use App\Models\PublicRoute;
use App\Libs\Routes as MyRoutes;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class MenuForm extends Form
{
    protected $formOptions = [
		'id'		=> 'frmMenu',
        'url'		=> '/admin/menus/store',
		'method'	=> 'POST',
    ];

    public function buildForm()
    {
		$publicRoutes = MyRoutes::getPublicRoutes()->toArray();

        $this
            ->add('id', Field::HIDDEN)
            ->add('is_published', Field::CHECKBOX)
            ->add('api_enabled', Field::CHECKBOX)
            ->add('name', Field::TEXT, [
                'rules' => 'required',
            ])
			->add('icon', Field::TEXT, [
			])
            ->add('fa_icon', Field::TEXT, [
                'label'	=> 'Font-Awesome-Icon',
            ])
			->add('menuItemType', Field::ENTITY, [
				'class' => MenuItemType::class,
				'label'	=> 'Type wählen',
				'property' => 'label',
				'empty_value' => 'Bitte wählen ...',
				'selected' => null,
				'query_builder' => function (MenuItemType $item) {
					return $item->all();
				}
			])
			->add('route', 'select', [
				'choices'		=> $publicRoutes,
				'label'			=> 'Route wählen',
				'empty_value'	=> 'Bitte wählen ...',
				'wrapper' => ['class' => 'form-group d-none'],
			])
			->add('url', Field::TEXT, [
				'label'	=> 'externe URL oder interne Route',
//				'rules' => 'required',
				'wrapper' => ['class' => 'form-group d-none'],
			])
			->add('btnSubmit', Field::BUTTON_BUTTON, [
				'label' => 'Speichern',
				'attr' => [
					'id'	=> 'btnSubmit',
					'class'	=> 'btn btn-primary col-12 col-sm-auto',
					'name'	=> 'submit',
					'value'	=> 'save',
				],
			])
			->add('btnRemove', Field::BUTTON_BUTTON, [
				'label' => 'Löschen',
				'attr' => [
					'id'	=> 'btnRemove',
					'class'	=> 'btn btn-danger col-12 col-sm-auto mt-xs-2 float-right d-none',
					'name'	=> 'submit',
					'value'	=> 'remove',
				],
			])
		;
    }
}
