<?php
/**
 * ImageForm.php
 *
 * @author    Bernd Engels
 * @created   25.02.19 14:47
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class MediaForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('id', 'hidden')
            ->add('title', Field::TEXT)
            ->add('remove',Field::CHECKBOX, [
                    'label' => 'Bild löschen',
                ]
            )
        ;
    }
}