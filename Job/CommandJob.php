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

use Helthe\Component\Chronos\Job\AbstractJob;
use Symfony\Component\Process\Process;

/**
 * Basic implementation of a job to run a command.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class CommandJob extends AbstractJob implements CommandJobInterface
{
    /**
     * @var string
     */
    private $command;

    /**
     * Constructor.
     *
     * @param mixed  $expression
     * @param string $command
     */
    public function __construct($expression, $command)
    {
        parent::__construct($expression);

        $this->command = $command;
    }

    /**
     * {@inheritdoc}
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $process = new Process($this->getCommand());

        return $process->run() === 0;
    }
}
