# Vest
*Valorin's Test Suite Artisan Command for Laravel 4.*

Vest is a simple package I wrote to allow me to re-use my Laravel 4 test suite
across all of my projects without needing to worry about keeping it in sync.

I've made very little effort to make it massively customizable, since it's
designed by me, for me. If it doesn't work for you, either write your own
or submit a pull request with changes and we can discuss your needs :-)

Installation
------------

Add the package to your application with composer:

```
composer require "valorin/vest:~1.0"
```

Add the `VestServiceProvider` service provider to the `providers` list in `./app/config/app.php`:

```
'providers' => array(
    ...
    'Valorin\Vest\VestServiceProvider',
),
```

Usage
-----

Run the `vest` command in the `testing` environment:

```
./artisan --env=testing vest
```

Configuration
-------------

To change the default configuration, run:

```
./artisan config:publish "valorin/vest"
```

And then edit the configuration file at:

```
./app/config/packages/valorin/vest/config.php
```

Version History
---------------

- **v1.1.0**
  - Removed legacy 'createdb' command.
  - Supporting Laravel 5
- **v1.0.0**
  - Initial Release
