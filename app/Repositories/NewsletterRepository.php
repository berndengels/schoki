<?php

namespace App\Repositories;

use App\Entities\Newsletter\CampaignDefaults;
use App\Entities\Newsletter\ContactEntity;
use App\Entities\Newsletter\MembersEntity;
use App\Entities\Newsletter\SettingsEntity;
use App\Entities\Newsletter\StatsEntity;
use App\Models\Address;
use App\Models\AddressCategory;
use App\Models\Newsletter;
use Carbon\Carbon;
use Illuminate\Session\SessionManager;
use Illuminate\Session\Store;
use Illuminate\Support\Str;
use DrewM\MailChimp\MailChimp;
use Newsletter as SpatieNewsletter;
use Illuminate\Database\Eloquent\Collection;
use DB;

/**
 * Class NewsletterRepository
 */
class NewsletterRepository {

	/**
	 * @var int
	 */
	protected $maxCampaings = 10;
	/**
	 * @var string
	 */
	protected $campaignSessionKey = 'currentCampaignId';
	/**
	 * @var bool
	 */
	protected $useCache = FALSE;
	/**
	 * @var MailChimp
	 */
	protected $api;
	/**
	 * @var Collection
	 */
	protected $list;
	/**
	 * @var Collection
	 */
	protected $listTags;
	/**
	 * @var Collection
	 */
	protected $campaigns;
	/**
	 * @var int
	 */
	protected $listId;
	/**
	 * @var int
	 */
	protected $templateId;
	/**
	 * @var string
	 */
	protected $campaignId;

	/**
	 * NewsletterRepository constructor.
	 * @param bool $useCache
	 */
	public function __construct($useCache = FALSE) {
		$this->useCache = $useCache;
		$this->api = SpatieNewsletter::getApi();
		$this->campaigns = collect(collect($this->api->get('campaigns'))->get('campaigns'))
			->keyBy(function ($item) {
				return Carbon::createFromTimeString($item['create_time'])
					->format('Y-m-d H:i');
			})
			->sortKeysDesc();
		$this->templateId = (int) config('newsletter.templateId');
//		$this->campaignId	= config('newsletter.campaignId');
		$this->listId = config('newsletter.lists.subscribers.id');
		$this->list = collect($this->api->get('lists/' . $this->listId));
		$this->listTags = collect(collect($this->api->get('lists/' . $this->listId . '/segments'))->get('segments'));

		$this->forgotCampaignId();
		session()->forget($this->campaignSessionKey);
		$this->clearCampaigns();
	}

	/**
	 * @param Address $address
	 * @param bool $force
	 * @return array|bool|false|null
	 */
	public function addMember(Address $address, $force = FALSE) {
		if (!$force && SpatieNewsletter::isSubscribed($address->email)) {
			return NULL;
		}

		$mergeFields = [
			'tag' => $address->addressCategory->name,
			'name' => 'category',
			'type' => 'text',
		];
		$response = SpatieNewsletter::subscribeOrUpdate($address->email, $mergeFields);
		if (!SpatieNewsletter::lastActionSucceeded()) {
			return [
				'lastError' => SpatieNewsletter::getLastError(),
				'errors' => isset($response['errors']) ? $response['errors'] : NULL,
			];
		}

		$params = ['email_address' => $address->email];
		$response = $this->api->post("lists/{$this->listId}/segments/{$address->addressCategory->tag_id}/members", $params);

		if (!SpatieNewsletter::lastActionSucceeded()) {
			return [
				'lastError' => SpatieNewsletter::getLastError(),
				'errors' => isset($response['errors']) ? $response['errors'] : NULL,
			];
		}
		return $response;
	}

	/**
	 * @param $name
	 * @return array|false
	 */
	public function createListTag($name) {
		$params = [
			'name' => $name,
			'static_segment' => [],
		];
		$response = $this->api->post('lists/' . $this->listId . '/segments', $params);

		if (!SpatieNewsletter::lastActionSucceeded()) {
			return [
				'lastError' => SpatieNewsletter::getLastError(),
				'errors' => isset($response['errors']) ? $response['errors'] : NULL,
			];
		}
		return $response;
	}

	/**
	 * @return Collection|\Illuminate\Support\Collection
	 */
	public function getListTags() {
		return $this->listTags;
	}

	/**
	 * @param $name
	 * @return array
	 */
	public function getListTagByName($name) {
		$response = $this->getListTags();

		if (!SpatieNewsletter::lastActionSucceeded()) {
			return [
				'lastError' => SpatieNewsletter::getLastError(),
				'errors' => isset($response['errors']) ? $response['errors'] : NULL,
			];
		}

		return $response->where('name', $name)->first();
	}

	/**
	 * @param $id
	 * @return array
	 */
	public function getListTag($id) {
		$response = $this->api->get("lists/{$this->listId}/segments/$id");

		if (!SpatieNewsletter::lastActionSucceeded()) {
			return [
				'lastError' => SpatieNewsletter::getLastError(),
				'errors' => isset($response['errors']) ? $response['errors'] : NULL,
			];
		}
		return $response;
	}

	/**
	 * @return mixed
	 */
	public function getList() {
		return $this->list;
	}

	/**
	 * @return Collection
	 */
	public function getCampaigns() {
		return $this->campaigns;
	}

	/**
	 * @return array
	 */
	public function getCampaign() {
		$campaignId = $this->getCampaignId();
		if (!$campaignId) {
			return ['error' => 'no campaignId exist'];
		}
		$response = $this->api->get("campaigns/$campaignId");

		if (!SpatieNewsletter::lastActionSucceeded()) {
			return [
				'campaignId' => $campaignId,
				'error' => SpatieNewsletter::getLastError(),
			];
		}

		return $response;
	}

	/**
	 * @param $segmentId
	 * @return array|\Illuminate\Support\Collection
	 */
	public function getMembers($segmentId) {
		$response = $this->api->get("lists/{$this->listId}/segments/$segmentId/members");

		if (!SpatieNewsletter::lastActionSucceeded()) {
			return [
				'segmentId' => $segmentId,
				'error' => SpatieNewsletter::getLastError(),
			];
		}

		if(isset($response['members']) && count($response['members'])) {
			return $response['members'];
		}

		return null;
	}

	/**
	 * @param $campaignId
	 * @return array|false
	 */
	public function removeCampaign($campaignId) {
		$response = $this->api->delete('campaigns/' . $campaignId);

		if (!SpatieNewsletter::lastActionSucceeded()) {
			return [
				'campaignId' => $campaignId,
				'lastError' => SpatieNewsletter::getLastError(),
				'errors' => isset($response['errors']) ? $response['errors'] : NULL,
			];
		}
		DB::table('newsletter')->where('id', $campaignId)->delete();
		$this->removeCampaignId($campaignId);

		return $response;
	}

	/**
	 * @param SettingsEntity $settings
	 * @param $tagID
	 * @return array
	 */
	public function createCampaign(SettingsEntity $settings, $tagID) {
		$params = [
			'type' => 'regular',
			'recipients' => [
				'list_id' => $this->listId,
				'segment_opts' => [
					'saved_segment_id' => (int) $tagID,
				]
			],
			'settings' => $settings->toObject(),
			'tracking' => [
				'opens' => TRUE,
				'text_clicks' => FALSE,
				'html_clicks' => FALSE,
			],
		];
		$response = $this->api->post('campaigns', $params);

		$id = isset($response['id']) ? $response['id'] : null;
		$this->forgotCampaignId();
		$this->setCampaignId($id);

		if (!SpatieNewsletter::lastActionSucceeded()) {
			return [
				'lastError' => SpatieNewsletter::getLastError(),
				'errors' => isset($response['errors']) ? $response['errors'] : NULL,
			];
		}

		return $response;
	}

	/**
	 * @param $body
	 * @return bool|mixed
	 */
	public function setCampaignContent($html, $plaintext) {
		$campaignId = $this->getCampaignId();
		if (!$campaignId) {
			return ['error' => 'no campaignId exist'];
		}
		$params = [
			'html' => $html,
			'plain_text' => $plaintext,
		];

		$response = $this->api->put("campaigns/{$campaignId}/content", $params);

		if (!SpatieNewsletter::lastActionSucceeded()) {
			return [
				'campaignId' => $campaignId,
				'lastError' => SpatieNewsletter::getLastError(),
				'errors' => isset($response['errors']) ? $response['errors'] : NULL,
			];
		}

		return $response;
	}

	/**
	 * @param array $emails
	 * @param string $mailType html or plaintext
	 * @return bool|mixed
	 */
	public function sendTestCampaign($emails, $mailType = 'plaintext') {
		$campaignId = $this->getCampaignId();
		if (!$campaignId) {
			return ['error' => 'no campaignId exist'];
		}
		$params = [
			'test_emails' => $emails,
			'send_type' => $mailType,
		];
		$response = $this->api->post("campaigns/{$campaignId}/actions/test", $params);

		if (!SpatieNewsletter::lastActionSucceeded()) {
			return [
				'campaignId' => $campaignId,
				'lastError' => SpatieNewsletter::getLastError(),
				'errors' => isset($response['errors']) ? $response['errors'] : NULL,
			];
		}

		return $response;
	}

	/**
	 * @return bool|mixed
	 */
	public function sendCampaign() {
		$campaignId = $this->getCampaignId();
		if (!$campaignId) {
			return ['error' => 'no campaignId exist'];
		}
		$response = $this->api->post("campaigns/{$campaignId}/actions/send");

		if (!SpatieNewsletter::lastActionSucceeded()) {
			return [
				'campaignId' => $campaignId,
				'lastError' => SpatieNewsletter::getLastError(),
				'errors' => isset($response['errors']) ? $response['errors'] : NULL,
			];
		}

		return $response;
	}

	/**
	 * @return bool|mixed
	 */
	public function checkSendCampaign() {
		$campaignId = $this->getCampaignId();
		if (!$campaignId) {
			return ['error' => 'no campaignId exist'];
		}
		$params = ['fields' => 'is_ready'];
		$response = $this->api->get("campaigns/{$campaignId}/send-checklist", $params);

		if (!SpatieNewsletter::lastActionSucceeded()) {
			return [
				'campaignId' => $campaignId,
				'lastError' => SpatieNewsletter::getLastError(),
				'errors' => isset($response['errors']) ? $response['errors'] : NULL,
			];
		}

		return $response;
	}

	/**
	 * @return bool
	 */
	public function isReadyForSend() {
		$response = $this->checkSendCampaign();
		if ($response && isset($response['is_ready'])) {
			return (bool) $response['is_ready'];
		}
		return FALSE;
	}

	/**
	 * @param null $id
	 * @return SessionManager|Store|mixed
	 */
	public function setCampaignId($id = null) {
		return session([$this->campaignSessionKey => $id]);
	}

	/**
	 * @return SessionManager|Store|mixed
	 */
	public function getCampaignId() {
		return session($this->campaignSessionKey, null);
	}

	/**
	 *
	 */
	public function forgotCampaignId() {
		return session()->forget($this->campaignSessionKey);
	}

	/**
	 * @param $value
	 */
	public function removeCampaignId($value) {
		if(session()->has($this->campaignSessionKey) && $value === session($this->campaignSessionKey)) {
			session()->forget($this->campaignSessionKey);
		}
	}

	/**
	 * @return array
	 */
	public function clearCampaigns() {
		$removed = [];
		if ($this->campaigns->count() > $this->maxCampaings) {
			$counter = 1;
			foreach ($this->campaigns as $item) {
				if ($counter > $this->maxCampaings) {
					$removed[$item['id']] = $this->removeCampaign($item['id']);
					DB::table('newsletter')->where('id', $item['id'])->delete();
					$this->removeCampaignId($item['id']);
				}
				$counter++;
			}
		}
		return $removed;
	}
}
