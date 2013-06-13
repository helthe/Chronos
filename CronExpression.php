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

use Helthe\Component\Chronos\Field\DayOfMonthField;
use Helthe\Component\Chronos\Field\DayOfWeekField;
use Helthe\Component\Chronos\Field\FieldInterface;
use Helthe\Component\Chronos\Field\HourField;
use Helthe\Component\Chronos\Field\MinuteField;
use Helthe\Component\Chronos\Field\MonthField;
use Helthe\Component\Chronos\Field\YearField;

/**
 * A Unix CRON expression.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class CronExpression
{
    /**
     * @var string
     */
    private $expression;
    /**
     * @var array
     */
    private $fields;

    /**
     * Constructor.
     *
     * @param string $expression
     */
    public function __construct($expression)
    {
        $this->setExpression($expression);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getExpression();
    }

    /**
     * Set CRON expression.
     *
     * @param string $expression
     *
     * @throws \InvalidArgumentException
     */
    public function setExpression($expression)
    {
        $definitions = array(
            '@yearly' => '0 0 1 1 *',
            '@annually' => '0 0 1 1 *',
            '@monthly' => '0 0 1 * *',
            '@weekly' => '0 0 * * 0',
            '@daily' => '0 0 * * *',
            '@hourly' => '0 * * * *'
        );

        if (isset($definitions[$expression])) {
            $expression = $definitions[$expression];
        }

        $expressionParts = explode(' ', $expression);

        if (count($expressionParts) < 5 || count($expressionParts) > 6) {
            throw new \InvalidArgumentException(sprintf('%s is not a valid CRON expression.', $expression));
        }

        $this->expression = $expression;
        $this->fields = array();

        foreach ($expressionParts as $position => $value) {
            $this->fields[$position] = $this->getField($position, $value);
        }
    }

    /**
     * Get CRON expression.
     *
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * Checks if the CRON expression matches the given date.
     *
     * @param \DateTime $date
     *
     * @return Boolean
     */
    public function matches(\DateTime $date)
    {
        foreach ($this->fields as $field) {
            if (!$field->matches($date)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the field object for the given position with the given value.
     *
     * @param string $position
     * @param string $value
     *
     * @return FieldInterface
     * @throws \InvalidArgumentException
     */
    private function getField($position, $value)
    {
        switch ($position) {
            case 0:
                return new MinuteField($value);
            case 1:
                return new HourField($value);
            case 2:
                return new DayOfMonthField($value);
            case 3:
                return new MonthField($value);
            case 4:
                return new DayOfWeekField($value);
            case 5:
                return new YearField($value);
            default:
                throw new \InvalidArgumentException(sprintf('%s is not a valid CRON expression position.', $position));
        }
    }
}
