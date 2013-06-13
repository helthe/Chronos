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

use Helthe\Component\Chronos\Field\DayOfWeekField;

/**
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class DayOfWeekFieldTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers DayOfWeekField::__construct
     * @covers DayOfWeekField::isValid
     * @covers DayOfWeekField::buildValidationRegex
     * @covers DayOfWeekField::buildFieldRangeRegex
     */
    public function testConstructor()
    {
        $values = array('1', '*', '?', 'SUN-2', '5L', '1-3/2,5L', 'FRI-SUN/1', '2,FRI#3');

        foreach ($values as $value) {
            new DayOfWeekField($value);
        }
    }

    /**
     * @covers DayOfWeekField::getValue
     */
    public function testGetValue()
    {
        $values = array(
            'SUN-2' => '0-2',
            'FRI' => '5',
            'MON,WED-SAT' => '1,3-6',
            'SUN#1' => '0#1'
        );

        foreach ($values as $value => $expected) {
            $field = new DayOfWeekField($value);
            $this->assertEquals($expected, $field->getValue());
        }
    }

    /**
     * @covers DayOfWeekField::matches
     * @covers DayOfWeekField::isInRange
     * @covers DayOfWeekField::isInIncrementsOfRange
     * @covers DayOfWeekField::matchesHash
     * @covers DayOfWeekField::matchesLastDay
     * @covers DayOfWeekField::matchesRelativeDate
     */
    public function testMatches()
    {
        $validMatches = array(
          '*' => 'now',
          '?' => 'now',
          '1,5' => 'friday',
          '0,1-WED' => 'tuesday',
          '*/2' => 'sunday',
          'MON-6/3' => 'thursday',
          'FRI#2' => 'second friday of may',
          'TUE#3,3L' => 'last wednesday of june'
        );

        foreach ($validMatches as $value => $date) {
            $field = new DayOfWeekField($value);
            $this->assertTrue($field->matches(new \DateTime($date)), "$value does not match $date");
        }

        $invalidMatches = array(
          '1-3' => 'friday',
          '*/2' => 'monday',
          'WED#1' => 'first monday of july',
          '2L,FRI#5' => 'last thursday of november'
        );

        foreach ($invalidMatches as $value => $date) {
            $field = new DayOfWeekField($value);
            $this->assertFalse($field->matches(new \DateTime($date)), "$value matches $date");
        }
    }
}
