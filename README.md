# Chronos [![Build Status](https://secure.travis-ci.org/helthe/Chronos.png?branch=master)](http://travis-ci.org/helthe/Chronos) [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/helthe/Chronos/badges/quality-score.png?s=99255d57c39f4377574c7820c1a8d36a32b9ee36)](https://scrutinizer-ci.com/g/helthe/Chronos/)

Chronos provides an object oriented library for managing cron jobs both with crontab and programmatically.

## Installation

Add the following in your componser.json:

```json
{
    "require": {
        "helthe/chronos": "~1.0"
    }
}
```

## Usage

### CRON expression

At its core, Chronos uses a CRON expression parser to validate all cron jobs. It supports all the language
characteristics defined [here](http://en.wikipedia.org/wiki/Cron#CRON_expression) as well as the
[predefined scheduling definitions](http://en.wikipedia.org/wiki/Cron#Predefined_scheduling_definitions) except `@reboot`.

### Crontab

You can use the library to both deploy cron jobs directly into crontab.

```php
use Helthe\Component\Chronos\Crontab;
use Helthe\Component\Chronos\Job\CommandJob;

$crontab = new Crontab();
$job = new CommandJob('@hourly', '/usr/bin/my_great_command');

$crontab->add($job);

$crontab->update();
```

### CronJobScheduler

You can also programmatically run cron jobs.

```php
use Helthe\Component\Chronos\CronJobScheduler;
use Helthe\Component\Chronos\Job\CommandJob;

$scheduler = new CronJobScheduler();
$job = new CommandJob('@hourly', '/usr/bin/my_great_command');

$scheduler->add($job);

$scheduler->runJobs();
```

## Credits

Chronos was created to fill the need for managing recurring jobs in PHP. The initial inspiration for it was to
have a [Whenever](https://github.com/javan/whenever) equivalent in PHP.

The CRON expression parser was initially based on the [parser](https://github.com/mtdowling/cron-expression)
built by Michael Dowling.

## Resources

You can run the unit tests with the following command:

```bash
$ cd path/to/Helthe/Component/XXX/
$ composer.phar install --dev
$ phpunit
```
