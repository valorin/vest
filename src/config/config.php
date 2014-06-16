<?php
/**
 * Vest Configuration.
 */

return array(
    // Run everything (default command)
    'all' => array(
        'prepare',
        'lint',
        'phpunit',
        'phpcs',
    ),

    // Clean up environment
    'prepare' => array(
        'artisan' => array(
            'clear-compiled',
            'optimize',
            'cache:clear',
        ),
    ),

    // PHP Lint
    'lint' => array(
        'exec' => './vendor/bin/parallel-lint ./app/',
    ),

    // PHPUnit
    'phpunit' => array(
        'exec' => './vendor/bin/phpunit ./app/',
    ),

    // PHP Code Sniffer
    'phpcs' => array(
        'exec' => './vendor/bin/phpcs --standard=phpcs.xml ./app/',
    ),
);
