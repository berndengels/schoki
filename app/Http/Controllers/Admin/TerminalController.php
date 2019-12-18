<?php
/**
 * TerminalController.php
 *
 * @author    Bernd Engels
 * @created   19.05.19 15:45
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Recca0120\Terminal\Kernel;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Routing\ResponseFactory;
use Recca0120\Terminal\Http\Controllers\TerminalController as VendorTerminalController;

class TerminalController extends VendorTerminalController {

	public function __construct(Request $request, ResponseFactory $responseFactory) {
		parent::__construct($request, $responseFactory);

		$this->middleware('auth');
		$this->middleware(function ($request, $next) {
			$this->user = auth()->user();
			if($this->user) {
				$this->isAdmin = (bool) $this->user->is_super_admin;
			}

			return $next($request);
		});
	}

	public function index(Kernel $kernel, $view = 'index') {
//		return parent::index($kernel, $view);
		$token = null;
		if ($this->request->hasSession() === true) {
			$token = $this->request->session()->token();
		}

		$kernel->call('list --ansi');
		$options = json_encode(array_merge($kernel->getConfig(), [
			'csrfToken' => $token,
			'helpInfo' => $kernel->output(),
		]));
		$id = ($view === 'panel') ? Str::random(30) : null;

//		return $this->responseFactory->view('terminal::'.$view, compact('options', 'id'));
//		return $this->responseFactory->view("vendor.terminal.$view", compact('options', 'id'));
		return view("vendor.terminal.$view", compact('options', 'id'));
	}

	public function endpoint(Kernel $kernel) {
		return parent::endpoint($kernel);
	}

	public function media(Filesystem $files, $file) {
		$filename = public_path().'/'.$file;
		$mimeType = strpos($filename, '.css') !== false ? 'text/css' : 'application/javascript';
		$lastModified = $files->lastModified($filename);
		$eTag = sha1_file($filename);
		$headers = [
			'content-type' => $mimeType,
			'last-modified' => date('D, d M Y H:i:s ', $lastModified).'GMT',
		];

		if (@strtotime($this->request->server('HTTP_IF_MODIFIED_SINCE')) === $lastModified ||
			trim($this->request->server('HTTP_IF_NONE_MATCH'), '"') === $eTag
		) {
			$response = $this->responseFactory->make(null, 304, $headers);
		} else {
			$response = $this->responseFactory->stream(function () use ($filename) {
				$out = fopen('php://output', 'wb');
				$file = fopen($filename, 'rb');
				stream_copy_to_stream($file, $out, filesize($filename));
				fclose($out);
				fclose($file);
			}, 200, $headers);
		}

		return $response->setEtag($eTag);
	}
}
