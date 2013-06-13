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
 * Minute field.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class MinuteField extends AbstractField
{
    /**
     * {@inheritdoc}
     */
    protected function getFieldValueFromDate(\DateTime $date)
    {
        return $date->format('i');
    }

    /**
     * {@inheritdoc}
     */
    protected function isValid($value)
    {
        return 1 === preg_match($this->buildValidationRegex('[0-5]?\d'), $value);
    }
}
