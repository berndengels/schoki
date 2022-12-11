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
use Carbon\Carbon;
use App\Helper\MyDate;
use App\Models\Event;
use App\Models\User;
use App\Models\Video;
use App\Models\Images;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Kris\LaravelFormBuilder\FormBuilder;

class MainController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $paginationLimit = 20;
    protected $entity       = '';
    protected $entityPlural  = '';
    protected $addLink      = '';
    protected $listLink     = '';
    protected $title        = '';
    protected $obj          = null;
    protected $objForm      = null;
    protected $today        = null;
	/**
	 * @var null|Model
	 */
	protected $_model = null;
    static protected $model = null;
    static protected $form = null;
	static protected $searchForm = null;
	static protected $orderBy = null;
	static protected $orderDirection = 'asc';

	protected $untilValidDate;

    /**
     * @var User|Authenticatable|null
     */
    protected $user = null;
	protected $isAdmin = false;
	protected $imagePath = '';
	protected $reservedDates = [];
	protected $strReservedDates = '';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('store.success');
		$this->middleware(function ($request, $next) {
			$this->user = auth()->user();
			if($this->user) {
				$this->isAdmin = (bool) $this->user->is_super_admin;
			}

			return $next($request);
		});
		$this->untilValidDate = MyDate::getUntilValidDate();
        $this->today = Carbon::today('Europe/Berlin');
		$this->imagePath = public_path('/media/images');

		/**
		 * @var $model Model
		 */
        $modelClass = static::$model;

        if( $modelClass ) {
            $this->entity   = lcfirst(class_basename($modelClass));
            $this->entityPlural = Str::plural($this->entity);
            $this->addLink  = route('admin.'.$this->entity.'New');
            $this->listLink = route('admin.'.$this->entity.'List');
            $this->title    = ucfirst($this->entity);
        }
	}

	protected function initReservedDates()
	{
		$this->reservedDates = $this->getReservedDates();
		$this->strReservedDates = implode(",", $this->reservedDates);
	}

	public function getReservedDates()
	{
		$dates = Event::whereDate('event_date','>=', $this->today)
			->orderBy('event_date','asc')
			->pluck('event_date')
			->toArray()
		;

		array_walk($dates, function(&$v){
			$v = "'$v'";
		});

		return $dates;
	}

	protected function index()
    {
		/**
		 * @var $model Model
		 */
        $model = static::$model;
        if($model) {
			$data = $model::sortable( static::$orderBy ? [static::$orderBy, static::$orderDirection ] : null )
				->paginate( $this->paginationLimit )
			;
            $viewName = 'admin.' . $this->entityPlural;
            $data = [
				'searchForm' => static::$searchForm,
                'data'      => $data,
                'addLink'   => $this->addLink,
                'entity'    => $this->entity,
                'title'     => Str::plural($this->title),
            ];
            $view = view($viewName, $data);
            return $view;
        }
        return null;
    }

    public function edit( FormBuilder $formBuilder, $id = 0 , $options = []) {
        $model  = static::$model;
        $form   = static::$form;

        if( $model && $form ) {
            $this->obj      = ($id > 0) ? $model::findOrFail($id): null;
            $this->objForm  = $formBuilder->create($form, ['model' => $this->obj], $options);
            $formOptions    = [
                'id'        => $id,
                'form'      => $this->objForm,
                'title'     => $this->title,
                'listLink'  => $this->listLink,
            ];

            if($options && is_array($options)) {
                $formOptions += $options;
            }
            return view('admin.form.'.$this->entity, $formOptions);
        }
        return null;
    }

    public function delete( $id ) {
        $model  = static::$model;
        if($model) {
            /**
             * @var $entity Model
             */
            $entity = $model::find($id);
            if($entity) {
                try {
                    $entity->delete();
                    Cache::forget($this->cacheEventKey);
                } catch(Exception $e) {
                    echo $e->getMessage().'<br>';
                    dd($e->getTrace());
                }
                if(isset($entity->images)) {
                    $this->removeImages($entity->images);
                }
                return redirect()->route('admin.'.$this->entity.'List');
            }
        }
        return null;
    }

    protected function processImages( FormRequest $request, $id = 0, $override = 0, $template = 0 )
    {
        $path   = config('filesystems.disks.image_upload.root').'/';
        $images = ($request->images && count($request->images) > 0) ? $request->images : null;
        $addedImages = ($request->addedImgages && count($request->addedImgages) > 0) ? $request->addedImgages : null;

        /**
         * @var $image Images
         * @var $imgFile UploadedFile
         */
        if ($images) {
            foreach($images as $img) {
                $imgID = (int) $img['id'];
                $image = Images::find($imgID);

				if($override > 0 || $template > 0 ) {
					$attributes = $image->getAttributes();
					$image = new Images();
					$image->setRawAttributes($attributes);
					$image->id = null;
					$image->event_id = $id;

					if( $override > 0 ) {
						$image->event_periodic_id = null;
					}
					if( $template > 0 ) {
						$image->event_template_id = null;
					}
				}

				if( isset($img['remove']) ) {
                    $fullPath = realpath($path . $image->internal_filename);
                    DB::table('images')->where('id', '=', $imgID)->delete();
                    @unlink($fullPath);
                    continue;
                }

				$image->title = $img['title'];
                try {
                    $image->saveOrFail();
                } catch (Exception $e) {
                    return back()->with('error','Fehler: '.$e->getMessage());
                }
            }
        }

        // new image files
        if( $addedImages && count($addedImages) > 0 ) {
            foreach($addedImages as $filename => $img) {
                $data = json_decode($img);
                if ($data->success) {
                    $image = new Images();
                    $image->internal_filename   = $filename;
                    $image->external_filename   = $data->external_filename;
                    $image->width               = $data->width;
                    $image->height              = $data->height;
                    $image->filesize            = $data->filesize;
                    $image->extension           = $data->extension;

                    switch($this->entity) {
                        case 'event':
                            $image->event_id = $id;
                            break;
						case 'eventTemplate':
							$image->event_template_id = $id;
							break;
                        case 'eventPeriodic':
                            $image->event_periodic_id = $id;
                            break;
                    }

                    $image->title = '';
                    try {
                        $image->saveOrFail();
                    } catch (Exception $e) {
                        return back()->with('error','Fehler: '.$e->getMessage());
                    }
                }
            }
        }
    }

    protected function removeImages(Collection $images)
    {
        if($images && $images->count() > 0) {
            $path   = config('filesystems.disks.image_upload.root').'/';
            foreach($images as $img) {
                $fullPath = realpath($path . '/' . $img->internal_filename);
                $img->delete();
                @unlink($fullPath);
            }
        }
    }

    public function user()
    {
        return Auth::user();
    }

    protected function redirectTo( Request $request )
    {
        return route('login');
    }
}
