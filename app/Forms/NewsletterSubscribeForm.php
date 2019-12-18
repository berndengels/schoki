<?php
/**
 * ImageForm.php
 *
 * @author    Bernd Engels
 * @created   25.02.19 14:47
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Forms;

use App\Models\AddressCategory;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class NewsletterSubscribeForm extends Form
{
    protected $formOptions = [
        'id'    => 'frmSubscribeNewsletter',
        'method' => 'POST',
        'url' => '/contact/sendNewsletterSubscribe',
		'class' => 'mx-sm-1 mx-md-3 col-md-6',
	];

    public function buildForm()
    {
        parent::buildForm();
        $this
            ->add('address_category', Field::ENTITY, [
				'label' => 'Adress-Kategorie',
                'class' => AddressCategory::class,
                'empty_value'  => 'Bitte wählen ...',
                'query_builder' => function (AddressCategory $item) {
//                    return $item->whereIn('id', [4,5])->orderBy('name');
					return $item->orderBy('name');
                }
            ])
            ->add('email', Field::EMAIL, [
                'rules' => 'required'
            ])
            ->add('remove', Field::CHECKBOX, [
                'label' => 'Ich möchte den Newsletter abbestellen',
            ])
            ->add('senden', Field::BUTTON_SUBMIT, [
                'attr' => ['class' => 'btn btn-primary pull-right'],
            ])
        ;
    }
}