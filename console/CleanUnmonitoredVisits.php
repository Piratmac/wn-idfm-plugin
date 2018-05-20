<?php namespace Piratmac\Idfm\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Db;

class CleanUnmonitoredVisits extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'idfm:cleanunmonitoredvisits';

    /**
     * @var string The console command description.
     */
    protected $description = 'Cleans visits that are no longer related to any monitored stop';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
      $visits = \Piratmac\Idfm\Models\Visit::leftJoin('piratmac_idfm_monitored_stop', 'piratmac_idfm_monitored_stop.stop_id', '=', 'piratmac_idfm_visits.stop_id')->whereNull('piratmac_idfm_monitored_stop.stop_id');
      trace_log('IDFM module: '.$visits->count().' visits were deleted as they\'re no longer related to any monitored stop');
      $this->info('IDFM module: '.$visits->count().' visits were deleted as they\'re no longer related to any monitored stop');
      $visits->delete();
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
