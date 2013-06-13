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

use Helthe\Component\Chronos\Field\DayOfMonthField;

/**
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class DayOfMonthFieldTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers DayOfMonthField::__construct
     * @covers DayOfMonthField::isValid
     * @covers DayOfMonthField::buildValidationRegex
     * @covers DayOfMonthField::buildFieldRangeRegex
     */
    public function testConstructor()
    {
        $values = array('1', '*', '?', '1-12', 'L', '5W', '11-12,20W,L');

        foreach ($values as $value) {
            new DayOfMonthField($value);
        }
    }

    /**
     * @covers DayOfMonthField::matches
     * @covers DayOfMonthField::isInRange
     * @covers DayOfMonthField::isInIncrementsOfRange
     * @covers DayOfMonthField::matchesNearestWeekday
     */
    public function testMatches()
    {
        $validMatches = array(
          '*' => 'now',
          '?' => 'now',
          '1,5' => 'may 5th',
          '1,11-23' => 'july 15th',
          '*/5' => 'june 25th',
          '4-17,L,23' => 'last day of october',
          '9W,20-24' => 'april 9th 2013',
          '13W' => 'april 12th 2013',
          'L,7W' => 'april 8th 2013'
        );

        foreach ($validMatches as $value => $date) {
            $field = new DayOfMonthField($value);
            $this->assertTrue($field->matches(new \DateTime($date)), "$value does not match $date");
        }

        $invalidMatches = array(
          '1-3' => 'may 13th',
          '*/2' => 'april 13th',
          '1-15,16W' => 'november 26th',
          'L,5W' => 'october 13th'
        );

        foreach ($invalidMatches as $value => $date) {
            $field = new DayOfMonthField($value);
            $this->assertFalse($field->matches(new \DateTime($date)), "$value matches $date");
        }
    }
}
