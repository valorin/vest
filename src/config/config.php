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
        'exec' => './vendor/bin/phpunit ./app/',
    ),

    // PHPMD
    'phpmd' => array(
        'exec' => './vendor/bin/phpmd ./app/ text cleancode,codesize,controversial,design,naming,unusedcode',
    ),

    // PHP Code Sniffer
    'phpcs' => array(
        'exec' => './vendor/bin/phpcs --standard=PSR2 ./app/',
    ),

    // Check Composer Packages for known vulnerabilities.
    'security' => array(
        'exec' => './vendor/bin/security-checker security:check',
    ),
);
