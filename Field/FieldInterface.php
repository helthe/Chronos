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
 * Interface used to define CRON expression fields.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
interface FieldInterface
{
    /**
     * Get field value.
     *
     * @return string
     */
    public function getValue();

    /**
     * Checks if the given date matches the field value.
     *
     * @param \DateTime $date
     *
     * @return Boolean
     */
    public function matches(\DateTime $date);
}
