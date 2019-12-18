<?php
/**
 * ImageForm.php
 *
 * @author    Bernd Engels
 * @created   25.02.19 14:47
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Forms;

use App\Models\MusicStyle;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class BandsForm extends Form
{
    protected $formOptions = [
        'id'    => 'frmBands',
        'method' => 'POST',
        'url' => '/contact/sendBands',
		'class' => 'mx-sm-1 mx-md-3 col-md-6',
    ];

    public function buildForm()
    {
        parent::buildForm();
        $this
            ->add('music_style_id', Field::ENTITY, [
                'class' => MusicStyle::class,
				'label'	=> 'Musik-Richtung',
                'empty_value'  => 'Bitte wählen ...',
				'query_builder' => function (MusicStyle $item) {
					return $item->orderBy('name');
				}
			])
            ->add('email', Field::EMAIL, [
                'rules' => ['required','email']
            ])
            ->add('name', Field::TEXT, [
                'rules' => ['required','max:50']
            ])
            ->add('message', Field::TEXTAREA, [
				'label'	=> 'Nachricht',
                'rules' => ['required','max:1000'],
				'attr'	=> [
					'wrap' => 'soft',
				]
            ])
            ->add('senden', Field::BUTTON_SUBMIT, [
                'attr' => ['class' => 'btn btn-primary pull-right'],
            ])
        ;
    }
}
