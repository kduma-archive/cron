<?php namespace October\Rain\Cron\Console;

use Illuminate\Console\Command;
use October\Rain\Cron\Models\Job;
use October\Rain\Cron\CronJob;
use Symfony\Component\Console\Input\InputOption;

class CronCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'queue:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Executes the latest job in the cron queue";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
	    $runs = 0;
	    $time = microtime(true);
	    while ($job = Job::isAvailable()->first()) {
		    $cronJob = new CronJob($this->laravel, $job);
		    $cronJob->fire();

		    if(++$runs >= $this->option('runlimit'))break;
		    if((microtime(true) - $time) > $this->option('timelimit'))break;
	    }
    }
	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('timelimit', 't', InputOption::VALUE_OPTIONAL, 'A time limited to run all commands in seconds.', 50),
			array('runlimit', 'r', InputOption::VALUE_OPTIONAL, 'Maximum runs.',100),
		);
	}
}
