<?php
/**
 * MainController.php
 *
 * @author    Bernd Engels
 * @created   30.01.19 22:13
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class FileController extends Controller
{
    protected $uploadImgPath;
    protected $uploadImgWebPath;
    protected $maxImageWidth;
    protected $maxImageHeight;
    protected $imageQuality = 70;

    public function __construct()
    {
		$this->middleware('auth');
		$this->uploadImgTempPath = config('filesystems.disks.upload.root');
        $this->uploadImgWebTempPath = config('filesystems.disks.upload.webRoot');
        $this->uploadImgPath = config('filesystems.disks.image_upload.root');
        $this->uploadImgWebPath = config('filesystems.disks.image_upload.webRoot');

		$this->uploadAudioPath = config('filesystems.disks.audio_upload.root');
		$this->uploadAudioWebPath = config('filesystems.disks.audio_upload.webRoot');

        $this->maxImageHeight = config('event.maxImageHeight');
        $this->maxImageWidth = config('event.maxImageWidth');
    }

    public function upload(Request $request)
    {
		try {
			/**
			 * @var $file UploadedFile
			 */
			$file       = $request->file('image');
			$fileName   = $file->getClientOriginalName();
			$extension  = $file->getExtension();

			$hashName   = $file->hashName();
			$tmpName    = new File($file->getPathname());

			$destPath   = $this->uploadImgTempPath.'/'.$hashName;
			$url		= $this->uploadImgWebTempPath.'/'.$hashName;

			Storage::disk('upload')->putFileAs(
				'',
				$tmpName,
				$hashName
			);
			chmod($destPath, 0666);
			[$width, $height] = getimagesize($destPath);
			$response = [
				'success'   => true,
				'error'     => null,
				'url'       => $url,
				'filesize'  => $file->getSize(),
				'width'     => $width,
				'height'    => $height,
				'extension' => $extension,
				'internal_filename' => $hashName,
				'external_filename' => $fileName,
				'location'	=> $url,
			];
		} catch(Exception $e) {
			$response = [
				'success'   => false,
				'error'     => $e->getMessage(),
				'location'	=> null,
			];
		}

		return response()->json($response);
    }

	public function uploadAudio(Request $request)
	{
		/**
		 * @var $file UploadedFile
		 */
		$file       = $request->file('audio');
		$fileName   = $file->getClientOriginalName();
		$extension  = $file->getClientOriginalExtension();
		$hashName   = $file->hashName();
		$tmpName    = new File($file->getPathname());

		$destPath   = $this->uploadAudioPath.'/'.$hashName;
		$url		= $this->uploadAudioWebPath.'/'.$hashName;

		try {
			Storage::disk('audio_upload')->putFileAs(
				'',
				$tmpName,
				$hashName
			);
			chmod($destPath, 0666);

			$response = [
				'success'   => true,
				'error'     => null,
				'url'       => $url,
				'filesize'  => $file->getSize(),
				'extension' => $extension,
				'internal_filename' => $hashName,
				'external_filename' => $fileName,
				'location'	=> $url,
			];
		} catch(Exception $e) {
			$response = [
				'success'   => false,
				'error'     => $e->getMessage(),
				'location'	=> null,
			];
		}
		return response()->json($response);
	}

	public function editorUpload( $data ) {
		$response = [
			'success'   => false,
//			'error'     => $e->getMessage(),
			'location'	=> 'URL',
		];
		return response()->json($response);
	}

    public function uploadCropped( Request $request ) {
        /**
         * @var $file UploadedFile
         */
        $file       = $request->file('croppedImage');
        $extension  = $file->getClientOriginalExtension();
        $fileName   = $request->filenameOrig;
        $hashName   = $request->filename;
        $tmpName    = new File($file->getPathname());
        $destPath   = $this->uploadImgPath .'/'.$hashName;

        try {
            Storage::disk('image_upload')->putFileAs(
                '',
                $tmpName,
                $hashName
            );
            $fileSize = $file->getSize();
            chmod($destPath, 0666);
            [$width, $height] = getimagesize($destPath);
            // pre resize image
            if($height > $this->maxImageHeight) {
                try {
                    $file = Image::make($destPath)->heighten($this->maxImageHeight);
                    $file->save(null, 70);
                    $width      = $file->getWidth();
                    $height     = $file->getHeight();
                    $fileSize   = $file->filesize();

                } catch( Exception $e ) {
                    logger($e->getMessage());
                }
            }
            $response = [
                'success'   => true,
                'error'     => null,
                'width'     => $width,
                'height'    => $height,
                'filesize'  => $fileSize,
                'extension' => $extension,
                'internal_filename' => $hashName,
                'external_filename' => $fileName,
            ];
        } catch(Exception $e) {
            $response = [
                'success'   => false,
                'error'     => $e->getMessage(),
            ];
        }
        return response()->json($response);
    }

    public function delete( Request $request ) {
        $response = ['success' => false ];

        // remove original file
        $file = $this->uploadImgPath.'/'.$request->filename;
        if(file_exists($file) && is_writable($file)) {
            $response = [
                'success'       => true,
                'deleteOrig'    => @unlink($file),
            ];
        }

        return response()->json($response);
    }

    public function deleteDropfile( Request $request ) {
        $response = ['success' => false ];

        // remove original file
        $file = $this->uploadImgPath.'/'.$request->filename;
        if(file_exists($file) && is_writable($file)) {
            $response = [
                'success'       => true,
                'deleteOrig'    => @unlink($file),
            ];
        }

        return response()->json($response);
    }

    protected function optimizeImage($imageFile)
    {
        $path = config('filesystems.images');
        $fileName = "$path/$imageFile";

    }
}
