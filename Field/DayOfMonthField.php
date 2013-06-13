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
 * Day of the month field.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class DayOfMonthField extends AbstractField
{
    /**
     * {@inheritdoc}
     */
    public function matches(\DateTime $date)
    {
        if ('?' === $this->value) {
            return true;
        }

        $found = parent::matches($date);

        if (!$found) {
            $values = $this->getValueArray();

            foreach ($values as $value) {
                if ($found) {
                    break;
                }

                if ('L' === $value) {
                    $found = $date->format('t') == $date->format('d');
                } elseif (strpos($value, 'W') !== false) {
                    $found = $this->matchesNearestWeekday($value, $date);
                }
            }
        }

        return $found;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildFieldRangeRegex($fieldRegex)
    {
        return "(?:(?:$fieldRegex)(?:-(?:$fieldRegex)(?:/\d+)?|W)?|L)?";
    }

    /**
     * {@inheritdoc}
     */
    protected function getFieldValueFromDate(\DateTime $date)
    {
        return $date->format('j');
    }

    /**
     * {@inheritdoc}
     */
    protected function isValid($value)
    {
        if ('?' === $value) {
            return true;
        }

        return 1 === preg_match($this->buildValidationRegex('0?[1-9]|[12]\d|3[01]'), $value);
    }

    /**
     * Clones the given DateTime object and applies the modification.
     *
     * @param \DateTime $date
     * @param string    $modify
     *
     * @return \DateTime
     */
    private function getModifiedDateTime(\DateTime $date, $modify)
    {
        $date = clone $date;

        return $date->modify($modify);
    }

    /**
     * Checks if the given last day matches the given date.
     *
     * @param string    $day
     * @param \DateTime $date
     *
     * @return Boolean
     */
    private function matchesNearestWeekday($day, \DateTime $date)
    {
        $day = new \DateTime($date->format('F') . ' ' . substr($day, 0, -1) . ' ' . $date->format('Y'));
        $weekday = $day->format('w');

        if ($day->format('d') == $date->format('d') && $weekday !== '0' && $weekday !== '6') {
            return true;
        } elseif ($weekday == '0'
                  && $day->format('d') != $day->format('t')
                  && $this->getModifiedDateTime($day, 'next day')->format('d') == $date->format('d')
        ) {
            return true;
        } elseif ($weekday == '6'
                  && $day->format('d') != '1'
                  && $this->getModifiedDateTime($day, 'previous day')->format('d') == $date->format('d')
        ) {
            return true;
        }

        return false;
    }
}
