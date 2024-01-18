<?php
/**
 * MainController.php
 *
 * @author    Bernd Engels
 * @created   30.01.19 22:13
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Audios;
use App\Models\Images;
use Exception;
use Intervention\Image\Constraint;
use Ixudra\Curl\Facades\Curl;
use App\Helper\FilePermissions;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;
use Spatie\DbDumper\Compressors\GzipCompressor;
use App\Libs\MySqlDumper;
use Spatie\DbDumper\Databases\MySql;

class ServiceController extends Controller
{
	protected $user = null;
	protected $isAdmin = false;

	public function __construct() {
		parent::__construct();

		$this->middleware('auth');
		$this->middleware(function ($request, $next) {
			$this->user = auth()->user();
			if($this->user) {
				$this->isAdmin = (bool) $this->user->is_super_admin;
			}

			return $next($request);
		});
	}

	public function dumpDb() {
	    $dbName = env('DB_DATABASE');
	    $file = database_path('snapshots/' . $dbName . '.sql.gz');

		$binaryPath = env('DB_BINARY_PATH');
		$user = env('DB_USERNAME');
		$password = env('DB_PASSWORD');
		$host = env('DB_HOST');

		$dumpCommand = MySql::create()
			->setDumpBinaryPath($binaryPath)
			->setUserName($user)
			->setPassword($password)
			->setDbName($dbName)
			->setHost($host)
			->setDefaultCharacterSet('utf8mb3')
			->addExtraOption('--insert-ignore --add-drop-table -eC')
			->useCompressor(new GzipCompressor());

	    try {
			$dumpCommand->dumpToFile($file);
			chmod($file, 0755);
			$name = Carbon::now()->format('YmdHi') . '_schokoladen.sql.gz';

			return response()->download($file, $name);
        } catch(Exception $e) {
	        echo $e->getMessage().'<br>';
	        return '<pre>' . $e->getTraceAsString() . '</pre>';
        }
    }


	public function browserTestReport()
	{
		$imagePath	= public_path() . '/reports/images';
		$today		= Carbon::create('now');

		$images = collect(scandir($imagePath))->filter(function ($value) {
			return strrpos($value, '.jpg') ;
		});

		return view('admin.report', [
			'title'		=> 'Test-Report ' . $today->format('d.m.Y H:i'),
			'images'	=> $images,
		]);
	}

	public function sanitizeFilePermissions()
	{
		FilePermissions::makeWritable();
	}

	public function makeThumbs()
	{
		$path       = public_path('/media/images');
		$pathThumbs	= public_path('/media/images/thumbs');
		$images		= [];
		$thumbs 	= [];

		foreach( scandir($path) as $item ) {
			if( preg_match("/\.jp[e]?g$|\.gif$|\.png$/i",$item) ) {
				$images[] = basename($item);
			}
		}
		foreach( scandir($pathThumbs) as $item ) {
			if( preg_match("/\.jp[e]?g$|\.gif$|\.png$/i",$item) ) {
				$thumbs[] = basename($item);
			}
		}

		$images = array_unique($images);
		$thumbs = array_unique($thumbs);
		$toHandle	= array_diff($images, $thumbs);

		$count	= count($toHandle);

		set_time_limit(0);
		ob_implicit_flush(true);
		ob_end_flush();
		echo "<h3>Thumbs to generate: $count</h3>";

		foreach($toHandle as $index => $img) {
			$fullPathImages = $path . '/' . $img;
			$fullPathThumbs = $pathThumbs . '/' . $img;
			try {
				$myImg = Image::make($fullPathImages);
				$myImg->fit(100,100, function(Constraint $constraint){
					$constraint->aspectRatio();
				},'center');
				$myImg->save($fullPathThumbs, 70);
				chmod($fullPathThumbs, 0666);
				echo "$index: Thumb from $img generated<br>";
			} catch(Exception $e) {
				$msg = $e->getMessage();
				echo "$index: ERROR -> $img ($msg)<br>";
			}
			usleep(10000);
		}
	}

	public function syncImages()
    {
        if('prod' === config('app.env')) {
            return;
        }

        $dbImages   = Images::where('internal_filename','!=','none')->pluck('internal_filename')->toArray();
        $path       = public_path('/media/images');
        $remotePath	= 'https://www.schokoladen-mitte.de/media/images';
        $existing	= [];

        foreach( scandir($path) as $item ) {
            if( preg_match("/\.jp[e]?g$|\.gif$|\.png$/i",$item) ) {
                $existing[] = basename($item);
            }
        }

		$dbImages			= array_unique($dbImages);
		$existing			= array_unique($existing);
        $toDownload			= array_diff($dbImages, $existing);
        $countToDownload	= count($toDownload);

        if( $countToDownload > 0 ) {
            set_time_limit(0);
            ob_implicit_flush(true);
            ob_end_flush();
            echo "<h3>to download: $countToDownload</h3>";

			foreach( $toDownload as $index => $item ) {
				$url	= "$remotePath/$item";
				$dest	= "$path/$item";

				try {
					$response = Curl::to($url)->get();
//					dd($response);
					file_put_contents($dest, $response);
					chmod($dest, 0666);

					if( !preg_match('/\.jp[e]?g$/', $item) ) {
						$myImg = Image::make($dest)->encode('jpg');
						$size = $myImg->filesize();
						unlink($dest);
						$compression = $size > 80000 ? 70 : 90;
						$myImg->save(null, $compression);
						chmod($dest, 0666);
					}

					echo "$index: $item<br>";
					usleep(5000);
				} catch (Exception $e) {
					echo 'error: ('.$index.' '.$item.'): '.$e->getMessage().'<br>';
				}
			}
		} else {
            echo "<h3>nothing to download</h3>";
        }
    }

	public function syncAudios()
	{
		if('prod' === config('app.env')) {
			return;
		}

		$dbItems = Audios::where('internal_filename','!=','none')
			->whereIn('extension', ['mp3'])
			->pluck('internal_filename')
			->toArray()
		;
		$path       = public_path('/media/audios');
		$remotePath = 'https://www.schokoladen-mitte.de/media/audios';
		$existing = [];

		foreach( scandir($path) as $item ) {
			if( preg_match("/\.mp3$/i",$item) ) {
				$existing[] = basename($item);
			}
		}

		$dbItems			= array_unique($dbItems);
		$existing			= array_unique($existing);
		$toDownload			= array_diff($dbItems, $existing);
		$countToDownload	= count($toDownload);

		if( $countToDownload > 0 ) {
			set_time_limit(0);
			ob_implicit_flush(true);
			ob_end_flush();
			echo "<h3>to download: $countToDownload</h3>";

			foreach( $toDownload as $index => $item ) {
				$url	= "$remotePath/$item";
				$dest	= "$path/$item";

				try {
					$response = Curl::to($url)->get();
//					dd($response);
					file_put_contents($dest, $response);
					chmod($dest, 0666);
					echo "$index: $item<br>";
					usleep(5000);
				} catch (Exception $e) {
					echo 'error: ('.$index.' '.$item.'): '.$e->getMessage().'<br>';
				}
			}
		} else {
			echo "<h3>nothing to download</h3>";
		}
	}

    public function sanitizeImageDB()
    {
		$path	= config('filesystems.images');
//        $images = Images::where('width',0)->orWhere('height')->orWhere('extension', null)->get();
        $images = Images::all();
		$count	= $images->count();

		if($count > 0) {
			set_time_limit(0);
			ob_implicit_flush(true);
			ob_end_flush();
			echo "<h3>images zo sanitize: $count</h3>\n<pre>";

			/**
			 * @var $img Images
			 */
			foreach( $images as $index => $img ) {
				$id			= $img->id;
				$fileName	= $img->internal_filename;
				$file		= "$path/$fileName";

				if(file_exists($file)) {
					$extension = substr($fileName, strrpos($fileName, '.') + 1);
					$size = filesize($file);
					[$width, $height] = getimagesize($file);

					$img->extension	= $extension;
					$img->filesize	= $size;
					$img->width		= $width;
					$img->height	= $height;
					try {
						$img->save();
						echo "$index save $fileName\n";
					} catch(Exception $e) {
						echo "$index error: can't save $fileName: " . $e->getMessage() . "\n";
					}
				} else {
					echo "$index file not exist: $fileName\n";
				}
			}
		} else {
			echo "no image data to sanitize!\n";
		}
		echo "</pre><h3>fertig!</h3>\n";
    }

    public function cropImages()
    {
		$maxHeight      = config('event.maxImageHeight');
		$maxWidth       = config('event.maxImageWidth');
		$validFormat    = ['jpg','jpeg'];
        $path			= config('filesystems.images');
		$toCropped 		= [];
		$toConverted	= [];

		set_time_limit(0);
		ob_implicit_flush(true);
		ob_end_flush();

        foreach(scandir($path) as $item) {
            if(preg_match('/\.jp[e]?g$|\.png$|\.gif$/', $item)) {
				[$w,$h] = getimagesize($path.'/'.$item);
				$extension = substr($item, strrpos($item,'.') + 1);

				if($w > $maxWidth || $h > $maxHeight) {
					$toCropped[] = $item;
				}
				if( !in_array($extension, $validFormat) ) {
					$toConverted[] = $item;
				}
            }
        }

		$toCropped			= array_unique($toCropped);
		$toConverted		= array_unique($toConverted);
        $countToCropped 	= count($toCropped);
		$countToConverted	= count($toConverted);

        if( $countToCropped > 0 ) {
            echo "<h3>to cropped files: $countToCropped</h3><pre>";

            foreach($toCropped as $index => $fileName) {
                $source = "$path/$fileName";
                $dest   = $source;

                try {
                    $img			= Image::make($source);
                    $size			= $img->filesize();
                    $compression	= ($size > 80000) ? 70 : 90;
                    $img->heighten($maxHeight)->save($dest, $compression);

                    chmod($dest, 0666);

                    list($width,) = getimagesize($dest);
                    if($width > $maxWidth) {
                        Image::make($dest)
                            ->crop($maxWidth,$maxHeight)
                            ->save()
                        ;
                    }

                    echo "$index crop: $fileName\n";
                } catch( Exception $e ) {
					$msg = $e->getMessage();
					logger($msg);
					echo "error: $index can't crop: $fileName: $msg\n";
                    Images::where('internal_filename','=',$fileName)->delete();
                    unlink($source);
					continue;
                }
            }

            echo '<pre>';
        } else {
            echo "<h3>nothing to crop</h3>";
        }

		if($countToConverted > 0) {
			echo "<h3>to converted files: $countToConverted</h3><pre>";

			foreach($toConverted as $index => $fileName) {
				try {
					$source 	= "$path/$fileName";
					$dest   	= $source;
					$extension	= substr($fileName, strrpos($fileName,'.') + 1);
					$myImg		= Image::make($dest)->encode('jpg');
					$size 		= $myImg->filesize();
					$newName	= preg_replace("/\.$extension$/",'',$fileName) . '.jpg';
					$dest		= "$path/$newName";
					$cmpression = $size > 80000 ? 70 : 90;
					$myImg->save($dest, $cmpression);
					unlink($source);
					$dbImage = Images::where('internal_filename', $fileName)->first();
					if($dbImage) {
						$dbImage->internal_filename = $newName;
						$dbImage->extension = 'jpg';
						$dbImage->filesize = $size;
						$dbImage->width = $myImg->getWidth();
						$dbImage->height = $myImg->getHeight();
						$dbImage->save();
					}
					echo "$index converted: $fileName\n";
				} catch(Exception $e){
					$msg = $e->getMessage();
					logger($msg);
					echo "error: $index can't convert: $fileName: $msg\n";
					Images::where('internal_filename','=',$fileName)->delete();
					unlink($source);
					continue;
				}
			}
		} else {
			echo "<h3>nothing to convert</h3>";
		}
    }
}
