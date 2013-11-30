<?php namespace Valorin\Vest\Command;

use Illuminate\Console\Command;
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
        $group = $this->option('group');

        $banner = "== Running Vest group: <comment>{$group}</comment> ==";
        $strlen = strlen($banner) - 19;
        $this->info(str_pad('', $strlen, '='));
        $this->info($banner);
        $this->info(str_pad('', $strlen, '='));

    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('group', null, InputOption::VALUE_OPTIONAL, 'Run the specified test group.', 'all'),
        );
    }
}
