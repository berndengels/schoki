<?php
/**
 * ImageForm.php
 *
 * @author    Bernd Engels
 * @created   25.02.19 14:47
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Forms;

use App\Models\PeriodicPosition;
use App\Models\PeriodicWeekday;
use Carbon\Carbon;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class PeriodicDateForm extends Form
{
    public function buildForm()
    {
        $model  = $this->data['model'];
        $id     = $model ? $model->id : null;
        $periodicPositionId = $model ? $model->periodic_position_id : null;
        $periodicWeekdayId  = $model ? $model->periodic_weekday_id : null;

        $this
            ->add('position', Field::ENTITY, [
                'label' => '',
                'label_show' => false,
                'property' => 'name',
                'class' => PeriodicPosition::class,
                'selected'  => $periodicPositionId,
                'empty_value'  => $id ? null : 'Bitte wählen ...',
            ])
            ->add('weekday', Field::ENTITY, [
                'label' => '',
                'label_show' => false,
                'property' => 'name_de',
                'class' => PeriodicWeekday::class,
                'selected'  => $periodicWeekdayId,
                'empty_value'  => $id ? null : 'Bitte wählen ...',
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
        ;
    }
}
