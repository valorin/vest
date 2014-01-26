<?php namespace Valorin\Vest\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateDb extends Command
{
    /**
     * @var string
     */
    const HOST = 'localhost';
    const USER = 'dev';
    const PASS = '';
    const PREFIX = 'dev';


    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'vest:createdb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Creates a new dev/testing database, if one doesn't already exist.";

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
        if ($this->option('create-user') && !$this->createUser()) {
            return 1;
        }

        $conn = Config::get('database.default');
        $database = Config::get("database.connections.{$conn}.database");
        if (starts_with($database, Config::get('dbcreate.prefix', self::PREFIX))
            && !$this->option('ignore-existing')) {
            $this->info("Database already created.");
            return 1;
        }


        $this->comment('Attempting to connect to the database...');
        $host = Config::get('dbcreate.host', self::HOST);
        $user = Config::get('dbcreate.user', self::USER);
        $pass = Config::get('dbcreate.pass', self::PASS);
        $conn = Config::get('database.default');
        Config::set("database.connections.{$conn}.database", "information_schema"); // TODO: Remove hardcoded db
        Config::set("database.connections.{$conn}.hostname", $host);
        Config::set("database.connections.{$conn}.username", $user);
        Config::set("database.connections.{$conn}.password", $pass);

        try {
            DB::connection();
        } catch (\PDOException $e) {
            $this->error($e->getMessage());
            return 1;
        }

        $this->comment('Creating dev database...');

        $prefix = Config::get('dbcreate.prefix', self::PREFIX);
        $database = $prefix.'_'.time();
        DB::statement("CREATE DATABASE {$database}");

        $this->comment('Saving development config...');
        $config = array();

        // Check if dir && file exist
        $path = app_path().'/config/'.App::environment().'/';
        $file = 'database.php';
        if (is_dir($path) && file_exists($path.$file)) {

            // Import existing config and overwrite
            $config = include $path.$file;

        } elseif (!is_dir($path)) {
            // Else, ensure dir exists
            mkdir($path);
        }

        // Build config array
        $config['connections'][$conn]['database'] = $database;
        $config['connections'][$conn]['hostname'] = $host;
        $config['connections'][$conn]['username'] = $user;
        $config['connections'][$conn]['password'] = $pass;

        // Save to file
        $save = "<?php\n\nreturn ".var_export($config, true).";\n";
        $save = preg_replace("/\s+\\n/", "\n", $save);
        file_put_contents($path.$file, $save);

        $this->info("Database created successfully.");
    }


    protected function createUser()
    {
        $devHost = Config::get('dbcreate.host', self::HOST);
        $devUser = Config::get('dbcreate.user', self::USER);
        $devPass = Config::get('dbcreate.pass', self::PASS);
        $devPrefix = Config::get('dbcreate.prefix', self::PREFIX);

        $this->info(
            "To create the {$devUser} user and {$devPrefix} db prefix, "
            ."please provide the valid database administrative permissions."
        );

        $user = $this->ask('Database admin User: ');
        $pass = $this->secret('Database admin Password: ');

        $this->comment('Attempting to connect to database...');

        $conn = Config::get('database.default');
        Config::set("database.connections.{$conn}.database", "mysql"); // TODO: Remove hardcoded db
        Config::set("database.connections.{$conn}.hostname", $devHost);
        Config::set("database.connections.{$conn}.username", $user);
        Config::set("database.connections.{$conn}.password", $pass);

        try {
            DB::connection();
        } catch (\PDOException $e) {
            $this->error($e->getMessage());
            return false;
        }

        $this->comment('Attempting to create database user, and db prefix permisions...');

        try {
            // TODO: remove hardcoded localhost
            DB::statement("CREATE USER '{$devUser}'@'localhost' IDENTIFIED BY '{$devPass}'");
        } catch (\Exception $e) {
            $this->error("EXCEPTION: {$e->getMessage()}");
        }
        try {
            DB::statement("GRANT ALL PRIVILEGES ON `{$devPrefix}\_%`.* TO '{$devUser}'@'localhost'");
        } catch (\Exception $e) {
            $this->error("EXCEPTION: {$e->getMessage()}");
        }
        DB::statement("FLUSH PRIVILEGES;");


        return true;
    }


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('ignore-existing', null, InputOption::VALUE_NONE, 'Ignores existing database connection.', null),
            array(
                'create-user', null, InputOption::VALUE_NONE,
                'Create dev database user and permissions (will ask for admin user/pass).'
            ),
        );
    }
}
