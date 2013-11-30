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
            'less:compile',
        ),
    ),

    // PHP Lint
    'lint' => array(
        'exec' => './vendor/bin/parallel-lint ./app/',
    ),

    // PHPUnit
    'phpunit' => array(
        'exec' => './vendor/bin/phpunit --configuration '.__DIR__.'/../../phpunit.xml',
    ),

    // PHP Code Sniffer
    'phpcs' => array(
        'exec' => './vendor/bin/phpcs --standard='.__DIR__.'/../../phpcs.xml ./app/',
    ),
);
