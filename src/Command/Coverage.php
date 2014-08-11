<?php namespace Valorin\Vest\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class Coverage extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'vest:coverage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks a PHPUnit Coverage PHP serialised object for the specified threshold.';

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
        // Open serialised file
        $serialised = File::get($this->argument('file'));
        if (!Str::startsWith($serialised, '<?php')) {
            $coverage   = unserialize($serialised);

        // Require PHP object in file
        } else {
            $coverage = require $this->argument('file');
        }


        // Check coverage percentage
        $total      = $coverage->getReport()->getNumExecutableLines();
        $executed   = $coverage->getReport()->getNumExecutedLines();
        $percentage = nf((($executed / $total) * 100), 2);

        // Compare percentage to threshold
        $threshold = $this->argument('threshold');
        if ($percentage >= $threshold) {
            $this->info("Code Coverage of {$percentage}% is higher than the minimum threshold required {$threshold}%!");
            return;
        }

        // Throw error
        $this->error("Code Coverage of {$percentage}% is below than the minimum threshold required {$threshold}%!");

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
            array('file', InputArgument::REQUIRED, 'Coverage File'),
            array('threshold', InputArgument::REQUIRED, 'Minimum coverage threshold'),
        );
    }
}
