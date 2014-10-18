<?php
/**
 * Vest Configuration.
 */
return [

    // Default tasks
    'all' => [

        // Intentionally left blank, due to changes in config merging.
        // Update with your own tasks, or use the example below

    ],

    // Example default tasks list
    'example' => [
        'prepare',
        'lint',
        'phpcs',
        'phpmd',
        'phpunit',
        'phpspec',
        'security',
    ],

    // Clean up environment
    'prepare' => [

        // 'artisan' => [
        //     'clear-compiled',
        //     'optimize',
        //     'cache:clear',
        // ],

    ],

    // PHP Lint
    'lint' => [
        'exec' => './vendor/bin/parallel-lint ./app/',
    ],

    // PHPMD
    'phpmd' => [
        'exec' => './vendor/bin/phpmd ./app/ text cleancode,codesize,controversial,design,naming,unusedcode',
    ],

    // PHP Code Sniffer
    'phpcs' => [
        'exec' => './vendor/bin/phpcs --standard=PSR2 ./app/',
    ],

    // PHPUnit
    'phpunit' => [
        'exec'    => './vendor/bin/phpunit',
        // 'artisan' => [['vest:coverage', 'file' => './coverage.serialized', 'threshold' => 75]],
    ],

    // phpspec
    'phpspec' => [
        'exec' => './vendor/bin/phpspec run --format=pretty --ansi --no-interaction',
    ],

    // Check Composer Packages for known vulnerabilities.
    'security' => [
        'exec' => './vendor/bin/security-checker security:check',
    ],
];
