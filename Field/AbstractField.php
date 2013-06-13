<?php

/*
 * This file is part of the Helthe Chronos package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Component\Chronos\Field;

/**
 * Implements common field functionality.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
abstract class AbstractField implements FieldInterface
{
    /**
     * @var string
     */
    protected $value;

    /**
     * Constructor.
     *
     * @param string $value
     */
    public function __construct($value)
    {
        if (!$this->isValid($value)) {
            throw new \InvalidArgumentException(sprintf('%s is not a valid CRON expression field value.', $value));
        }

        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function matches(\DateTime $date)
    {
        if ('*' === $this->value) {
            return true;
        }

        $dateValue = $this->getFieldValueFromDate($date);
        $values = $this->getValueArray();
        $found = false;

        foreach ($values as $value) {
            if ($found) {
                break;
            }

            if ($dateValue == $value) {
                $found = true;
            } elseif (strpos($value, '/') !== false) {
                $found = $this->isInIncrementsOfRange($value, $dateValue);
            } elseif (strpos($value, '-') !== false) {
                $found = $this->isInRange($value, $dateValue);
            }
        }

        return $found;
    }

    /**
     * Builds the regex for field ranges.
     *
     * @param type $fieldRegex
     *
     * @return string
     */
    protected function buildFieldRangeRegex($fieldRegex)
    {
        return "(?:$fieldRegex)(?:-(?:$fieldRegex)(?:/\d+)?)?";
    }

    /**
     * Builds the validation regex.
     *
     * @param string $fieldRegex
     *
     * @return string
     */
    protected function buildValidationRegex($fieldRegex)
    {
        /**
         * This implementation is based on this answer on stack overflow:
         * http://stackoverflow.com/questions/235504/validating-crontab-entries-w-php
         */
        $range = $this->buildFieldRangeRegex($fieldRegex);

        return "%^(?:\*(?:/\d+)?|$range(?:,$range)*)$%";
    }

    /**
     * Generates an array of individual field values.
     *
     * @return array
     */
    protected function getValueArray()
    {
        return explode(',', $this->value);
    }

    /**
     * Checks if the given value is in the given increments of range.
     *
     * @param string $increments
     * @param string $value
     *
     * @return Boolean
     */
    protected function isInIncrementsOfRange($increments, $value)
    {
        $incrementsParts = explode('/', $increments);

        if ('*' === $incrementsParts[0]) {
            return (int) $value % $incrementsParts[1] === 0;
        }

        if (!$this->isInRange($incrementsParts[0], $value)) {
            return false;
        }

        $rangeParts = explode('-', $incrementsParts[0]);

        for ($i = $rangeParts[0]; $i <= $rangeParts[1]; $i += $incrementsParts[1]) {
            if ($i == $value) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if the given value is in the given range.
     *
     * @param string $range
     * @param string $value
     *
     * @return Boolean
     */
    protected function isInRange($range, $value)
    {
        $parts = explode('-', $range);

        return $value >= $parts[0] && $value <= $parts[1];
    }

    /**
     * Get the field value from the date.
     *
     * @param \DateTime $date
     *
     * @return int
     */
    abstract protected function getFieldValueFromDate(\DateTime $date);

    /**
     * Checks if the field value is valid.
     *
     * @param string $value
     *
     * @return Boolean
     */
    abstract protected function isValid($value);
}
