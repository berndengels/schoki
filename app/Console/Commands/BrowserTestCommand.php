<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BrowserTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'browser-test:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//		$response = $this->call('dusk:run',['--filter'=>'test_that_all_coms_work','--path' => 'tests/Browser/CommunicationsTest.php']);
		$response = $this->call('dusk:run');
    }
}
