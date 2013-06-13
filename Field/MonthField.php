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
 * Month field.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class MonthField extends AbstractField
{
    /**
     * {@inheritdoc}
     */
    public function __construct($value)
    {
        $value = str_ireplace(
            array('JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'),
            range(1, 12),
            $value
        );

        parent::__construct($value);
    }

    /**
     * {@inheritdoc}
     */
    protected function getFieldValueFromDate(\DateTime $date)
    {
        return $date->format('m');
    }

    /**
     * {@inheritdoc}
     */
    protected function isValid($value)
    {
        return 1 === preg_match($this->buildValidationRegex('[1-9]|1[012]'), $value);
    }
}
