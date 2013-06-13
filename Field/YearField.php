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
 * Year field.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class YearField extends AbstractField
{
    /**
     * {@inheritdoc}
     */
    protected function getFieldValueFromDate(\DateTime $date)
    {
        return $date->format('Y');
    }

    /**
     * {@inheritdoc}
     */
    protected function isValid($value)
    {
        return 1 === preg_match($this->buildValidationRegex('19[7-9]\d|20\d{2}'), $value);
    }
}
