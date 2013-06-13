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

use Helthe\Component\Chronos\Field\MonthField;

/**
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class MonthFieldTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers MonthField::__construct
     * @covers MonthField::isValid
     * @covers MonthField::buildValidationRegex
     * @covers MonthField::buildFieldRangeRegex
     */
    public function testConstructor()
    {
        $values = array('1', '*', 'JAN-3', '1-3/2,MAY');

        foreach ($values as $value) {
            new MonthField($value);
        }
    }

    /**
     * @covers MonthField::getValue
     */
    public function testGetValue()
    {
        $values = array(
            'JAN-2' => '1-2',
            'FEB-JUL/2,OCT' => '2-7/2,10'
        );

        foreach ($values as $value => $expected) {
            $field = new MonthField($value);
            $this->assertEquals($expected, $field->getValue());
        }
    }

    /**
     * @covers MonthField::matches
     * @covers MonthField::isInRange
     * @covers MonthField::isInIncrementsOfRange
     */
    public function testMatches()
    {
        $validMatches = array(
          '*' => 'now',
          'JUN,OCT' => 'june',
          'JAN,3-MAY' => 'april',
          '*/3' => 'march',
          'AUG-12/2' => 'december'
        );

        foreach ($validMatches as $value => $date) {
            $field = new MonthField($value);
            $this->assertTrue($field->matches(new \DateTime($date)), "$value does not match $date");
        }

        $invalidMatches = array(
          '1-3' => 'may',
          '*/4' => 'february',
          '2,MAY-8' => 'november'
        );

        foreach ($invalidMatches as $value => $date) {
            $field = new MonthField($value);
            $this->assertFalse($field->matches(new \DateTime($date)), "$value matches $date");
        }
    }
}
