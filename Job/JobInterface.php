<?php

/*
 * This file is part of the Helthe Chronos package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Component\Chronos\Job;

use Helthe\Component\Chronos\CronExpression;

/**
 * Interface used to define a cron job.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
interface JobInterface
{
    /**
     * Get the job CRON expression.
     *
     * @return CronExpression
     */
    public function getCronExpression();

    /**
     * Checks if the job is due to run. You may optionally set the date to check.
     *
     * @param mixed $date
     *
     * @return Boolean
     */
    public function isDue($date = 'now');

    /**
     * Run the job. Returns true if job was successful.
     *
     * @return Boolean
     */
    public function run();
}
