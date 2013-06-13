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
 * Common implementation of a job.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
abstract class AbstractJob implements JobInterface
{
    /**
     * @var CronExpression
     */
    protected $expression;

    /**
     * Constructor.
     *
     * @param mixed $expression
     */
    public function __construct($expression)
    {
        if (!$expression instanceof CronExpression) {
            $expression = new CronExpression($expression);
        }

        $this->expression = $expression;
    }

    /**
     * {@inheritdoc}
     */
    public function getCronExpression()
    {
        return $this->expression;
    }

    /**
     * {@inheritdoc}
     */
    public function isDue($date = 'now')
    {
        if (!$date instanceof \DateTime) {
            $date = new \DateTime($date);
        }

        return $this->expression->matches($date);
    }
}
