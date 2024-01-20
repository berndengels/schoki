<?php
/**
 * ImageForm.php
 *
 * @author    Bernd Engels
 * @created   25.02.19 14:47
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Forms;

use App\Models\Media;
use Kris\LaravelFormBuilder\Field;

class ImagesForm extends MediaForm
{
    protected $formOptions = [
    ];

    public function buildForm()
    {
        parent::buildForm();
        // path in disk
        $uploadPath = config('filesystems.disks.image_upload.root');
        $uploadPathCropped = config('filesystems.disks.image_upload_cropped.root');

        // path on web root
        $uploadWebPath = config('filesystems.disks.image_upload.webRoot');
        $uploadWebPathCropped = config('filesystems.disks.image_upload_cropped.webRoot');

        $model  = $this->getModel() ? $this->getModel() : null;
        $imgUrl = '';

        if ($model instanceof Media && isset($model->internal_filename) && isset($model->internal_filename)) {
            if (file_exists($uploadPath.'/'.$model->internal_filename)) {
                $imgUrl = $uploadWebPath .'/'. $model->internal_filename;

            } else if (file_exists($uploadPathCropped.'/'.$model->internal_filename)) {
                $imgUrl = $uploadWebPathCropped .'/'. $model->internal_filename;
            }
        }

        $this
            ->add('external_filename', Field::TEXT, [
                'attr' => [
                    'disabled' => 'disabled',
                ]
//                'rules' => 'nullable|image|dimensions:min_width=500:ratio=16/9',
            ])
            ->add('internal_filename', 'static', [
                'tag' => 'img', // Tag to be used for holding static data,
                'label_show' => false,
                'attr' => [
                    'class' => 'form-control-static image',
                    'src'   => $imgUrl,
                ],
                'value' => '',
            ])
        ;
    }
}
