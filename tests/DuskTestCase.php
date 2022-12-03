<?php

namespace Tests;

use App\Models\User;
use Laravel\Dusk\TestCase;
use Laravel\Dusk\Browser;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use Intervention\Image\ImageManagerStatic as StaticImage;
use Intervention\Image\Image;

abstract class DuskTestCase extends TestCase
{
    use CreatesApplication;

	/**
	 * @var User
	 */
	private $user;
	/**
	 * @var User
	 */
	private $adminUser;
	public static $screenshotWidth 			= 800;
	public static $screenshotThumbWidth 	= 200;
	public static $screenshotCompression	= 60;
	/**
	 * @var string
	 */
	private static $screenPath;

	public function setUp(): void
	{
		parent::setUp();
		self::$screenPath	= app()->basePath() . '/tests/Browser/screenshots';
		$this->adminUser	= User::where('username','bengels')->first();
		$this->user			= User::where('username','schoki')->first();
	}

	/**
	 * @return string
	 */
	public static function getScreenPath() {
		return self::$screenPath;
	}

	/**
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @return User
	 */
	public function getAdminUser() {
		return $this->adminUser;
	}

	/**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
//		static::useChromedriver('/usr/local/bin/chromedriver');
		self::startChromeDriver();
    }

	/**
     * Create the RemoteWebDriver instance.
     *
     * @return RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless',
//            '--window-size=1920,1080',
			'--window-size=1024,768',
			'--no-sandbox',
			'--ignore-certificate-errors',
        ]);
        $driver = RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome()
				->setCapability(ChromeOptions::CAPABILITY, $options)
				->setCapability(WebDriverCapabilityType::ACCEPT_SSL_CERTS, true)
				->setCapability('acceptInsecureCerts', true)
        );
		return $driver;
    }

	protected function captureFailuresFor($browsers)
	{
		$browsers->each(function (Browser $browser, $key) {
			$body = $browser->driver->findElement(WebDriverBy::tagName('body'));
			if (!empty($body)) {
				$currentSize = $body->getSize();
				$size = new WebDriverDimension($currentSize->getWidth(), $currentSize->getHeight());
				$browser->driver->manage()->window()->setSize($size);
			}
			$file = 'failure-'.$this->getName().'-'.$key;
			$browser->screenshot($file);
			@chmod(self::$screenPath . '/' . $file, 0666);
		});
	}

	public static function createJpeg( $screenName, $removeOrigial = true ) {

		$publicPath		= public_path('/reports/images');
		$screenFullPath = self::$screenPath .'/'.$screenName . '.png';
		$thumbPath		= $publicPath . '/thumbs';
		$fileToSave 	= $publicPath.'/'.$screenName.'.jpg';

		$img = StaticImage::make($screenFullPath)
			->widen(static::$screenshotWidth)
			->encode('jpg', static::$screenshotCompression)
			->save($fileToSave)
		;

		@chmod($fileToSave, 0666);
		self::createJpegThumbnail( $img, $thumbPath );

		if($removeOrigial) {
			unlink($screenFullPath);
		}

		return $img;
	}

	public static function createJpegThumbnail( Image $img, $path ) {
		$file = $path.'/'.$img->filename.'.'.$img->extension;
		$img = StaticImage::make($img->basePath())
			->widen(static::$screenshotThumbWidth)
			->save($file)
		;
		@chmod($file, 0666);
		return $img;
	}
}
