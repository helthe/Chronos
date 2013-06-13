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

use Helthe\Component\Chronos\Exception\InvalidIdentifierException;
use Helthe\Component\Chronos\Exception\RuntimeException;
use Helthe\Component\Chronos\Job\CommandJobInterface;
use Symfony\Component\Process\Process;

/**
 * Manages the crontab.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class Crontab
{
    /**
     * @var string
     */
    private $baseComment;
    /**
     * @var string
     */
    private $executable;
    /**
     * @var array
     */
    private $jobs;

    /**
     * Constructor.
     *
     * @param array  $jobs
     * @param string $executable
     * @param string $identifier
     */
    public function __construct(array $jobs = array(), $executable = '/usr/bin/crontab', $identifier = null)
    {
        $this->executable = $executable;
        $this->baseComment = 'Helthe cron generated tasks';
        $this->jobs = array();

        if ($identifier) {
            $this->baseComment .= ' for ' . $identifier;
        }

        foreach ($jobs as $job) {
            $this->add($job);
        }
    }

    /**
     * Add a job to the crontab.
     *
     * @param CommandJobInterface $job
     */
    public function add(CommandJobInterface $job)
    {
        $this->jobs[] = $job;
    }

    /**
     * Clears the crontab for the given user. Default: current user.
     *
     * @param string $user
     *
     * @return integer
     */
    public function clear($user = null)
    {
        return $this->write('', $user);
    }

    /**
     * Updates the crontab for the given user. Default: current user.
     *
     * @param string $user
     *
     * @return integer
     */
    public function update($user = null)
    {
        return $this->write($this->render(), $user);
    }

    /**
     * Reads the crontab file and returns the output. Default: current user.
     *
     * @param string $user
     *
     * @return string
     */
    private function read($user = null)
    {
        $process = new Process($this->getCommand() . ' -l');

        $process->run();

        return $process->getOutput();
    }

    /**
     * Get the base crontab command. Default: current user.
     *
     * @param string $user
     *
     * @return string
     */
    private function getCommand($user = null)
    {
        $command = $this->executable;

        if ($user) {
            $command .= ' -u' . $user;
        }

        return $command;
    }

    /**
     * Get footer comment block.
     *
     * @return string
     */
    private function getFooterComment()
    {
        return '# End ' . $this->baseComment;
    }

    /**
     * Get header comment block.
     *
     * @return string
     */
    private function getHeaderComment()
    {
        return '# Begin ' . $this->baseComment;
    }

    /**
     * Generates the task definition for the given job.
     *
     * @param JobInterface $job
     *
     * @return string
     */
    private function getTaskDefinition(CommandJobInterface $job)
    {
        return $job->getCronExpression() . ' ' . $job->getCommand() . PHP_EOL;
    }

    /**
     * Render all the cron jobs.
     *
     * @return string
     */
    private function render()
    {
        $output = '';

        foreach ($this->jobs as $job) {
            $output .= $this->getTaskDefinition($job);
        }

        return $output;
    }

    /**
     * Writes the crontab into the system for the given user. Default: current user.
     *
     * @param string $content
     * @param string $user
     *
     * @return integer
     *
     * @throws InvalidIdentifierException
     * @throws RuntimeException
     */
    private function write($content, $user = null)
    {
        $crontab = $this->read($user);
        $header = $this->getHeaderComment();
        $footer = $this->getFooterComment();
        $content = $header . PHP_EOL . $content . $footer;
        $hasHeader = preg_match("/^$header\s*$/m", $crontab) === 1;
        $hasFooter = preg_match("/^$footer\s*$/m", $crontab) === 1;

        if ($hasHeader && !$hasFooter) {
            throw new InvalidIdentifierException(sprintf('Unclosed identifier. Your crontab contains "%s", but no "%s".', $header, $footer));
        } elseif (!$hasHeader && $hasFooter) {
            throw new InvalidIdentifierException(sprintf('Unopened identifier. Your crontab contains "%s", but no "%s".', $footer, $header));
        }

        if ($hasHeader && $hasFooter) {
            $crontab = preg_replace("/^$header\s*$.*?^$footer\s*$/sm", $content, $crontab);
        } else {
            $crontab .= PHP_EOL . $content;
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'helthe_cron');
        file_put_contents($tempFile, $crontab);
        $process = new Process($this->getCommand($user) . ' ' . $tempFile);

        if ($process->run() === 1) {
            throw new RuntimeException($process->getErrorOutput());
        }

        return $process->getExitCode();
    }
}
