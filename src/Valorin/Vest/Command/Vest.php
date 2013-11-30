<?php namespace Valorin\Vest\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Vest extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'vest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the Vest Testsuite';

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
     * @return void
     */
    public function fire()
    {
        // Check for list
        if ($this->option('list')) {
            $this->listGroups();
            return 0;
        }

        // Check valid group
        $group = $this->argument('group');

        if (!Config::get('vest::vest.'.$group)) {
            $this->error("ERROR: Unknown group '{$group}'");
            return 1;
        }

        // Run group
        $this->banner($group);
        $this->runGroup($group);
    }

    /**
     * Displays the available vest groups
     *
     * @return void
     */
    protected function listGroups()
    {
        $this->info("Available Vest groups:");

        foreach (array_keys(Config::get('vest::vest')) as $group) {
            $this->comment('* '.$group);
        }
    }

    /**
     * Displays the banner listing the group being run.
     *
     * @return void
     */
    protected function banner($group)
    {
        $banner = "== Running Vest group: <comment>{$group}</comment> ==";
        $strlen = strlen($banner) - 19;
        $this->info(str_pad('', $strlen, '='));
        $this->info($banner);
        $this->info(str_pad('', $strlen, '='));
    }


    /**
     * Runs the specified test group.
     *
     * @return void
     */
    protected function runGroup($group)
    {

    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('group', InputArgument::OPTIONAL, 'Run the specified test group.', 'all'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('list', null, InputOption::VALUE_NONE, 'Lists the available Vest commands.'),
        );
    }
}
