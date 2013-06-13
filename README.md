# Chronos

The Helthe Chronos provides an object oriented library for managing cron jobs
both with crontab and programmatically.

[![Build Status](https://secure.travis-ci.org/helthe/Chronos.png?branch=master)](http://travis-ci.org/helthe/Chronos)

## Installation

Add the following in your componser.json:

    {
        "require": {
            "helthe/chronos": "~1.0"
        }
    }

## Usage

### CRON expression

At its core, the component uses a CRON expression parser to validate all cron jobs. It supports all the language
characteristics defined [here](http://en.wikipedia.org/wiki/Cron#CRON_expression) as well as the
[predefined scheduling definitions](http://en.wikipedia.org/wiki/Cron#Predefined_scheduling_definitions) except `@reboot`.

### Crontab

You can use the library to both deploy cron jobs directly into crontab.

    use Helthe\Component\Chronos\Crontab;
    use Helthe\Component\Chronos\Job\CommandJob;

    $crontab = new Crontab();
    $job = new CommandJob('@hourly', '/usr/bin/my_great_command');

    $crontab->add($job);

    $crontab->update();

### CronJobScheduler

You can also programmatically run cron jobs.

    use Helthe\Component\Chronos\CronJobScheduler;
    use Helthe\Component\Chronos\Job\CommandJob;

    $scheduler = new CronJobScheduler();
    $job = new CommandJob('@hourly', '/usr/bin/my_great_command');

    $scheduler->add($job);

    $scheduler->runJobs();

## Credits

This component was created to fill the need for managing recurring jobs in PHP. The initial inspiration for this
component was to have a [Whenever](https://github.com/javan/whenever) equivalent in PHP.

The CRON expression parser was initially based on the [parser](https://github.com/mtdowling/cron-expression)
built by Michael Dowling.

## Resources

You can run the unit tests with the following command:

    $ cd path/to/Helthe/Component/XXX/
    $ composer.phar install --dev
    $ phpunit