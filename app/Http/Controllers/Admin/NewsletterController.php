<?php
/**
 * NewsletterController.php
 *
 * @author    Bernd Engels
 * @created   15.06.19 12:15
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Http\Controllers\Admin;

use App\Entities\Newsletter\CampaignEntity;
use App\Entities\Newsletter\SettingsEntity;
use App\Forms\NewsletterForm;
use App\Models\Address;
use App\Models\AddressCategory;
use App\Models\Event;
use App\Models\Newsletter;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DrewM\MailChimp\MailChimp;
use Illuminate\Database\Eloquent\Collection;
use Newsletter as SpatieNewsletter;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use App\Repositories\NewsletterRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NewsletterController extends Controller
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests, FormBuilderTrait;
	/**
	 * @var User|Authenticatable|null
	 */
	protected $user = null;
	protected $isAdmin = false;
	private $campaignId;
	public $isTest = true;

	/**
	 * @var NewsletterRepository
	 */
	protected $repository;

	public function __construct() {

		$this->repository = new NewsletterRepository();
		$this->middleware('auth');
		$this->middleware(function ($request, $next) {
			$this->user = auth()->user();
			if($this->user) {
				$this->isAdmin = (bool) $this->user->is_super_admin;
				if($this->isTest) {
					$emails	= [
						$this->user->email,
					];

					foreach($emails as $email) {
						if ( !SpatieNewsletter::isSubscribed($email) ) {
							SpatieNewsletter::subscribe($email);
						}
					}
				}
			}

			return $next($request);
		});
	}

	public function index()
	{
		$campaigns = $this->repository->getCampaigns();
		$newsletters = collect([]);

		if($campaigns->count()) {
			foreach($campaigns as $campaign) {
				$newsletters->add(new CampaignEntity($campaign));
			}
		}

		return view('admin.newsletter', [
			'title'	=> 'Newsletter',
			'data'	=> $newsletters,
		]);
	}

	public function edit( FormBuilder $formBuilder, $campaignId = null )
	{
		$nextMonth		= Carbon::today('Europe/Berlin')->addMonthNoOverflow();
		$fromDefault 	= $nextMonth->copy()->firstOfMonth();
		$untilDefault	= $nextMonth->copy()->lastOfMonth();

		$options = [
			'data'	=> [
				'campaignId'	=> $campaignId,
				'fromDefault'	=> $fromDefault,
				'untilDefault'	=> $untilDefault,
			]
		];
		$model = Newsletter::first();
		$form  = $formBuilder->create(NewsletterForm::class, ['model' => $model], $options);

		$formOptions = [
			'form'      => $form,
			'title'     => 'Newsletter',
		];

		if($options && is_array($options)) {
			$formOptions += $options;
		}

		if(request()->submit && !$form->isValid()) {
			return redirect()->back()->withErrors($form->getErrors())->withInput();
		}

		return view('admin.form.newsletter', $formOptions);
	}

	public function process( Request $request )
	{
		$from		= $request->post('from');
		$until		= $request->post('until');
		$title		= $request->post('title');
		$header		= $request->post('header');
		$tagID 		= $request->post('tag_id');
		$tagName	= AddressCategory::where('tag_id',$tagID)->pluck('name')->first();
		$fromDate	= Carbon::createFromFormat('Y-m-d', $from);
		$untilDate	= Carbon::createFromFormat('Y-m-d', $until);
		$events		= Event::eventsForNewsletter($fromDate, $untilDate);
		$html		= $this->render('html', $events, $title, $header);
		$text		= $this->render('text', $events, $title, $header);

		$settings	= new SettingsEntity([
			'title'	=> "$title [$tagName]",
			'subject_line'	=> $title,
		]);

		switch($request->submit) {
			case 'preview':
				return $this->preview('html', $events, $title, $header);
				break;
			case 'create':
				return $this->create($settings, $tagID, $html, $text);
				break;
			case 'edit':
				return $this->setContent($html, $text);
				break;
			case 'check':
				return $this->check();
				break;
			case 'test':
				return $this->test([$this->user->email]);
				break;
			case 'send':
				return $this->send();
				break;
		}
	}

	public function preview( $format, $events, $title, $header = '' )
	{
		return view('mail.'.$format.'.newsletter', [
			'backLink'	=> true,
			'title'		=> $title,
			'header'	=> $header,
			'data'		=> $events,
		]);
	}

	public function create($settings, $tagID, $html, $plaintext)
	{
		$response = [];
		try {
			$response['createCampaign'] = $this->repository->createCampaign($settings, $tagID);
			if($response) {
				$campaignID = isset($response['createCampaign']['id']) ? $response['createCampaign']['id'] : null;
				if($campaignID) {
					$response['persist'] = $this->persist($campaignID, $tagID);
				}
				sleep(10);
				$response['updateCampaignContent'] = $this->setContent($html, $plaintext);
			}
			return view('admin.newsletter');
		} catch (Exception $e) {
			$message = $e->getMessage();
			return view('components.error', compact('message'));
		}
	}

	public function setContent($html, $plaintext)
	{
		return $this->repository->setCampaignContent($html, $plaintext);
	}

	public function persist($campaignID, $tagID)
	{
		$newsletter = new Newsletter();
		$newsletter->id		= $campaignID;
		$newsletter->tag_id = $tagID;

		try {
			return $newsletter->saveOrFail();
		} catch( Exception $e ) {
			$message = $e->getMessage();
			return view('components.error', compact('message'));
		}
	}

	public function check()
	{
		try {
			$response = $this->repository->isReadyForSend();
			return view('admin.newsletter.response', compact('response'));
		} catch (Exception $e) {
			$message = $e->getMessage();
			return view('components.error', compact('message'));
		}
	}

	public function test($emails)
	{
		try {
			$response = $this->repository->sendTestCampaign($emails);
			return view('admin.newsletter.response', compact('response'));
		} catch (Exception $e) {
			$message = $e->getMessage();
			return view('components.error', compact('message'));
		}
	}

	public function delete($campaignId)
	{
		try {
			$response = $this->repository->removeCampaign($campaignId);
			return redirect()->route('admin.newsletterList');
		} catch (Exception $e) {
			$message = $e->getMessage();
			return view('components.error', compact('message'));
		}
	}

	public function send()
	{
		try {
			$response = $this->repository->sendCampaign();
			return view('admin.newsletter.response', compact('response'));
		} catch (Exception $e) {
			$message = $e->getMessage();
			return view('components.error', compact('message'));
		}
	}

	public function getMembers( $segmentId )
	{
		try {
			$response = $this->repository->getMembers($segmentId);
			return response()->json([
				'sid' 		=> $segmentId,
				'result'	=> $response,
				'error'		=> null,
			]);
		} catch(Exception $e) {
			return response()->json([
				'sid' 		=> $segmentId,
				'result'	=> null,
				'error'		=> $e->getMessage(),
			]);
		}
	}

	public function render($format, $events, $title, $header = '')
	{
		return $this->view($format, $events, $title, $header)->render();
	}

	public function view($format, $events, $title, $header = '')
	{
		return view('mail.'.$format.'.newsletter', [
			'backLink'	=> false,
			'title'		=> $title,
			'header'	=> $header,
			'data'      => $events,
		]);
	}
}
