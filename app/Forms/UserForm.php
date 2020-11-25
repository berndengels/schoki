<?php
/**
 * EventForm.php
 *
 * @author    Bernd Engels
 * @created   30.01.19 22:55
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Forms;

//use App\Models\Role;

use App\Models\User;
use App\Models\MusicStyle;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class UserForm extends MainForm
{
    protected $formOptions = [
        'method' => 'POST',
        'url' => '/admin/users/store/',
    ];

    public function buildForm()
    {
        /**
         * @var $model User
         */
		$model	= $this->getModel() ?: null;
		$id     = $model ? $this->getModel()->id : null;

        $this
            ->add('id', Field::HIDDEN)
            ->add('enabled', Field::CHECKBOX);

		if( auth()->user() && auth()->user()->is_super_admin ) {
			$this->add('is_super_admin', Field::CHECKBOX);
		}

		$this->add('username', Field::TEXT )
			->add('email', Field::EMAIL )
            ->add('password', Field::PASSWORD )
			->add('music_style_id', Field::ENTITY, [
				'class' => MusicStyle::class,
				'label' => 'Musik Stile für Bandanfragen',
				'empty_value'  => 'Bitte wählen ...',
                'selected' => ($model) ? $model->musicStyles : null,
                'multiple' => true,
				'help_block' => [
					'text' => 'Bandanfragen werden an Eure Musik-Stile weitergeleitet',
					'tag' => 'p',
					'attr' => ['class' => 'help-block text-info font-weight-bold']
				],
				'query_builder' => function (MusicStyle $item) {
					return $item->orderBy('name')->get();
				}
			])
        ;
		$this->addSubmits();

        if( $id > 0 ) {
            $this->formOptions['url'] .= $id;
        }
    }
}
