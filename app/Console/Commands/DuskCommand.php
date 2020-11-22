<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\App;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;
use Laravel\Dusk\Console\DuskCommand as VendorDuskCommand;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DuskCommand extends VendorDuskCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dusk:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'My Dusk Command';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		if(!isset($_SERVER['argv'])) {
/*
			$_SERVER['argv'] = [
				'/opt/local/bin/php72',
//				'php',
				'vendor/phpunit/phpunit/phpunit',
				'-c',
				app()->basePath() . '/phpunit.dusk.xml',
			];
*/
			$_SERVER['argv'] = [
				'artisan',
				'dusk:run'
			];
		}
		$this->addOption('without-tty', true);

		$this->purgeScreenshots();
		$this->purgeConsoleLogs();

		$options = array_slice($_SERVER['argv'], $this->option('without-tty') ? 3 : 2);

		return $this->withDuskEnvironment(function () use ($options) {
			$binary = $this->binary();

			if('' === $binary[0]) {
				$phpBinaryFinder = new PhpExecutableFinder();
//				$binary[0] = $phpBinaryFinder->find();
//				$binary[0] = 'php';
			}
			$binary[0] = 'php';

			$params	= array_merge($binary, $this->phpunitArguments($options));
			/**
			 * @var $process Process
			 */
			$process = (new Process($params, app()->basePath(), $_ENV))->setTimeout(null);

			try {
				$process->setTty(Process::isTtySupported());
			} catch (RuntimeException $e) {
				$this->output->writeln('TTY Warning: '.$e->getMessage());
			}
			$this->output->title('Starte Browser Tests');

			$process->run(function ($type, $buffer) {
				if (Process::ERR === $type) {
					$this->output->error($buffer);
				} else {
					$this->output->write($buffer);
				}
			});

			// executes after the command finishes
			if (!$process->isSuccessful()) {
//				print_r($params);
				throw new ProcessFailedException($process);
			}

			$this->output->success('Tests erfolgreich abgeschlossen!');

			return $process;
		});
	}
}
