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
        return $this->runGroup($group);
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
     * @param  string $group
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
     * @param  string $group
     * @return void
     */
    protected function runGroup($group)
    {
        // Resolve commands
        $commands = $this->resolve($group);

        // Run Commands
        return $this->runCommands($commands);
    }


    /**
     * Resolves linked and nested commands from the configuration into a list of useful commands.
     *
     * @param  string $group
     * @return array
     */
    protected function resolve($group)
    {
        // Load config
        $config   = Config::get('vest::vest');
        $commands = array();

        // Loop steps
        foreach ($config[$group] as $key => $value) {

            // Reference to another step
            if (is_numeric($key)) {
                $resolved = $this->resolve($value);
                $commands = array_merge($commands, $resolved);
                continue;
            }

            // Array of values
            if (is_array($value)) {
                foreach ($value as $val) {
                    $commands[] = [$key, $val];
                }
                continue;
            }

            $commands[] = [$key, $value];
        }

        return $commands;
    }


    /**
     * Runs the specified commands.
     *
     * @param  array
     * @return void
     */
    protected function runCommands($commands)
    {
        // Switch working directory
        chdir(base_path());

        // Loop commands
        $status = 0;
        foreach ($commands as $command) {

            // Handle types
            list($type, $cmd) = $command;
            switch ($type) {

                // Artisan Commands
                case 'artisan':
                    $this->question("Artisan: {$cmd}");
                    $status += $this->call($cmd);
                    $this->comment('');
                    break;

                // Exec command
                case 'exec':
                    $this->question("Exec: {$cmd}");
                    system($cmd, $code);
                    $status += $code;
                    $this->comment('');
                    break;
            }
        }

        // Check status code
        if (!$status) {
            $this->info("All commands executed successfully!");
            return 0;
        }

        $this->error("One or more commands failed!");
        return 1;
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
