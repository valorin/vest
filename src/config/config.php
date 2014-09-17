<?php
/**
 * Vest Configuration.
 */

return array(
    // Run everything (default command)
    'all' => array(
        'prepare',
        'lint',
        'phpcs',
        'phpmd',
        'phpunit',
        'phpspec',
        'security',
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
        'exec'    => './vendor/bin/phpunit',
        'artisan' => [['vest:coverage', 'file' => './coverage.serialized', 'threshold' => 75]],
    ),

    // PHPMD
    'phpmd' => array(
        'exec' => './vendor/bin/phpmd ./app/ text cleancode,codesize,controversial,design,naming,unusedcode',
    ),

    // PHP Code Sniffer
    'phpcs' => array(
        'exec' => './vendor/bin/phpcs --standard=PSR2 ./app/',
    ),

    // phpspec
    'phpspec' => array(
        'exec' => './vendor/bin/phpspec run --format=pretty --ansi --no-interaction',
    ),

    // Check Composer Packages for known vulnerabilities.
    'security' => array(
        'exec' => './vendor/bin/security-checker security:check',
    ),
);
