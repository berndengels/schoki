<?php
/**
 * ImageForm.php
 *
 * @author    Bernd Engels
 * @created   25.02.19 14:47
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Forms;

use Kris\LaravelFormBuilder\Field;

class AudiosForm extends MediaForm
{
	public function buildForm()
	{
		parent::buildForm();
		// path in disk
		$uploadPath = config('filesystems.disks.audio_upload.root');

		// path on web root
		$uploadWebPath = config('filesystems.disks.audio_upload.webRoot');

		$model  = ($this->getModel()) ? $this->getModel() : null;
		$url = '';

		if ($model) {
			if (file_exists($uploadPath.'/'.$model->internal_filename)) {
				$url = $uploadWebPath .'/'. $model->internal_filename;
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
				'tag' => 'audio', // Tag to be used for holding static data,
				'label_show' => false,
				'attr' => [
					'class' => 'form-control-static image',
					'src'   => $url,
				],
				'value' => '',
			])
		;
	}
}
