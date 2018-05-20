<?php namespace Piratmac\Idfm\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Db;
use SystemException;
use ApplicationException;
use Log;

class GetVisits extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'idfm:getvisits';

    /**
     * @var string The console command description.
     */
    protected $description = 'Updates visits from the IDFM server';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $db_config = Db::getConfig();
        $ds = DIRECTORY_SEPARATOR;
        if ($db_config['driver'] == 'mysql') {
          $script_path = plugins_path().$ds.'piratmac'.$ds.'idfm'.$ds.'scripts'.$ds.'main.py';
          $args_text = '';
          foreach (['host', 'port', 'database', 'username', 'password'] as $arg)
            $args_text .= '--'.$arg.'='.$db_config[$arg].' ';
          $args_text .= ' --api_key='.\Piratmac\Idfm\Models\Settings::get('api_key');
          $commandline = 'python3 '.$script_path.' 2>&1 '.$args_text;

          if (\Piratmac\Idfm\Models\Settings::get('api_key') == '')
            throw new ApplicationException('API Key not set up for IDFM module (please go to settings page). Update of visits can\'t run.');

          exec($commandline, $output, $return_var);

          if ($return_var != 0)
            throw new SystemException('Error during refresh of Piratmac.Idfm. See more details in Event log. Error: '. implode("\n", $output));
        }
        else {
          throw new ApplicationException('Piratmac.Idfm module currently supports only MySQL databases.');
        }
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
