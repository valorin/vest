<?php

return array(
    // Run everything (default command)
    'all' => array(
        'cleanup',
        'lint',
        'phpunit',
        'phpcs',
    ),

    // Clean up environment
    'cleanup' => array(
        'artisan' => array(
            'optimize',
            'cache:clear',
            'compile:less',
        ),
    ),

    // PHP Lint
    'lint' => array(
        'exec' => './vendor/bin/parallel-lint ./app/',
    ),

    // PHPUnit
    'phpunit' => array(
        'exec' => './vendor/bin/phpunit',
    ),

    // PHP Code Sniffer
    'phpcs' => array(
        'exec' => './vendor/bin/phpcs --standard=phpcs.xml ./app/',
    ),
);
