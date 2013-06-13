<?php

/*
 * This file is part of the Helthe Chronos package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Component\Chronos\Tests\Field;

use Helthe\Component\Chronos\Field\MinuteField;

/**
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class MinuteFieldTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers MinuteField::__construct
     * @covers MinuteField::isValid
     * @covers MinuteField::buildValidationRegex
     * @covers MinuteField::buildFieldRangeRegex
     */
    public function testConstructor()
    {
        $values = array('1', '*', '10-42', '21-52/15,34,45-54');

        foreach ($values as $value) {
            new MinuteField($value);
        }
    }

    /**
     * @covers MinuteField::matches
     * @covers MinuteField::isInRange
     * @covers MinuteField::isInIncrementsOfRange
     */
    public function testMatches()
    {
        $validMatches = array(
          '*' => 'now',
          '15,45' => 'today 45 min',
          '0,30-40' => 'today 33 min',
          '*/5' => 'today 20 min',
          '24-48/3' => 'today 39 min'
        );

        foreach ($validMatches as $value => $date) {
            $field = new MinuteField($value);
            $this->assertTrue($field->matches(new \DateTime($date)), "$value does not match $date");
        }

        $invalidMatches = array(
          '1-3' => 'today 10 min',
          '*/4' => 'today 9 min',
          '2,15-38' => 'today 50 min'
        );

        foreach ($invalidMatches as $value => $date) {
            $field = new MinuteField($value);
            $this->assertFalse($field->matches(new \DateTime($date)), "$value matches $date");
        }
    }
}
