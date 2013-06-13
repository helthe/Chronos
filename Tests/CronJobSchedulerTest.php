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

use Helthe\Component\Chronos\CronJobScheduler;

/**
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class CronJobSchedulerTest extends \PHPUnit_Framework_TestCase
{
    public function testRunJobsWithoutJobs()
    {
        $scheduler = new CronJobScheduler();

        $scheduler->runJobs();
    }

    public function testRunJobsWithJobs()
    {
        $scheduler = new CronJobScheduler();
        $validJobMock = $this->getMock('Helthe\Component\Chronos\Job\JobInterface');
        $invalidJobMock = $this->getMock('Helthe\Component\Chronos\Job\JobInterface');

        $validJobMock->expects($this->once())
            ->method('isDue')
            ->will($this->returnValue(true));
        $validJobMock->expects($this->once())
            ->method('run');
        $invalidJobMock->expects($this->once())
            ->method('isDue')
            ->will($this->returnValue(false));
        $invalidJobMock->expects($this->never())
            ->method('run');

        $scheduler->add($validJobMock);
        $scheduler->add($invalidJobMock);
        $scheduler->runJobs();
    }
}
