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
 * Hour field.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class HourField extends AbstractField
{
    /**
     * {@inheritdoc}
     */
    protected function getFieldValueFromDate(\DateTime $date)
    {
        return $date->format('H');
    }

    /**
     * {@inheritdoc}
     */
    protected function isValid($value)
    {
        return 1 === preg_match($this->buildValidationRegex('[01]?\d|2[0-3]'), $value);
    }
}
