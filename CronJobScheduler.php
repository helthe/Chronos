<?php

/*
 * This file is part of the Helthe Chronos package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Component\Chronos;

use Helthe\Component\Chronos\Job\JobInterface;

/**
 * Programmatic cron job scheduler.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class CronJobScheduler
{
    /**
     * @var array
     */
    private $jobs;

    /**
     * Constructor.
     *
     * @param array $jobs
     */
    public function __construct(array $jobs = array())
    {
        $this->jobs = array();

        foreach ($jobs as $job) {
            $this->add($job);
        }
    }

    /**
     * Add job to the scheduler.
     *
     * @param JobInterface $job
     */
    public function add(JobInterface $job)
    {
        $this->jobs[] = $job;
    }

    /**
     * Run all the jobs that are due now.
     */
    public function runJobs()
    {
        foreach ($this->jobs as $job) {
            if ($job->isDue()) {
                $job->run();
            }
        }
    }
}
