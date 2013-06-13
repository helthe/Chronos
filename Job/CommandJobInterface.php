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

/**
 * Interface that defines jobs that are meant to run a command in the system.
 * Jobs with this interface can be added to the Crontab.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
interface CommandJobInterface extends JobInterface
{
    /**
     * Get the job command.
     *
     * @return string
     */
    public function getCommand();
}
