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
 * Day of week field.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class DayOfWeekField extends AbstractField
{
    /**
     * {@inheritdoc}
     */
    public function __construct($value)
    {
        $value = str_ireplace(
            array('SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'),
            range(0, 6),
            $value
        );

        parent::__construct($value);
    }

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

                if (strpos($value, '#') !== false) {
                    $found = $this->matchesHash($value, $date);
                } elseif (strpos($value, 'L') !== false) {
                    $found = $this->matchesLastDay($value, $date);
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
        return "(?:$fieldRegex)(?:-(?:$fieldRegex)(?:/\d+)?|L|#[1-5])?";
    }

    /**
     * {@inheritdoc}
     */
    protected function getFieldValueFromDate(\DateTime $date)
    {
        return $date->format('w');
    }

    /**
     * {@inheritdoc}
     */
    protected function isValid($value)
    {
        if ('?' === $value) {
            return true;
        }

        return 1 === preg_match($this->buildValidationRegex('[0-7]'), $value);
    }

    /**
     * Checks if the hash value matches the given date.
     *
     * @param string    $hash
     * @param \DateTime $date
     *
     * @return Boolean
     */
    private function matchesHash($hash, \DateTime $date)
    {
        $ordinals = array(
            '1' => 'first',
            '2' => 'second',
            '3' => 'third',
            '4' => 'fourth',
            '5' => 'fifth'
        );
        $hashParts = explode('#', $hash);

        if ($hashParts[0] != $this->getFieldValueFromDate($date)) {
            return false;
        }

        return $this->matchesRelativeDate($date, $ordinals[$hashParts[1]]);
    }

    /**
     * Checks if the given last day matches the given date.
     *
     * @param string    $day
     * @param \DateTime $date
     *
     * @return Boolean
     */
    private function matchesLastDay($day, \DateTime $date)
    {
        $day = substr($day, 0, -1);

        if ($day != $this->getFieldValueFromDate($date)) {
            return false;
        }

        return $this->matchesRelativeDate($date, 'last');
    }

    /**
     * Checks if the given date matches the relative date with the given prefix (ordinal or last).
     *
     * @param \DateTime $date
     * @param string    $prefix
     *
     * @return Boolean
     */
    private function matchesRelativeDate(\DateTime $date, $prefix)
    {
        $correctDate = new \DateTime($prefix . ' ' . $date->format('D') . ' of ' . $date->format('F') . ' ' . $date->format('Y'));

        return $date->format('Y-m-d') == $correctDate->format('Y-m-d');
    }
}
