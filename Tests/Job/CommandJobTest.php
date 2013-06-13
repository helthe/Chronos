<?php

/*
 * This file is part of the Helthe Chronos package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Component\Chronos\Tests;

use Helthe\Component\Chronos\Job\CommandJob;

/**
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class CommandJobTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers CommandJob::getCommand
     */
    public function testGetCommand()
    {
        $job = new CommandJob('@hourly', '/usr/bin/my_awesome_cmd');

        $this->assertEquals('/usr/bin/my_awesome_cmd', $job->getCommand());
    }
}
