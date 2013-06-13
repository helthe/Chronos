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

use Helthe\Component\Chronos\Field\HourField;

/**
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class HourFieldTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers HourField::__construct
     * @covers HourField::isValid
     * @covers HourField::buildValidationRegex
     * @covers HourField::buildFieldRangeRegex
     */
    public function testConstructor()
    {
        $values = array('1', '*', '10-22', '1-12/2,14,15-23');

        foreach ($values as $value) {
            new HourField($value);
        }
    }

    /**
     * @covers HourField::matches
     * @covers HourField::isInRange
     * @covers HourField::isInIncrementsOfRange
     */
    public function testMatches()
    {
        $validMatches = array(
          '*' => 'now',
          '15,20' => 'today 20 hour',
          '0,10-20' => 'today',
          '*/4' => 'today 16 hour',
          '4-18/3' => 'today 10 hour'
        );

        foreach ($validMatches as $value => $date) {
            $field = new HourField($value);
            $this->assertTrue($field->matches(new \DateTime($date)), "$value does not match $date");
        }

        $invalidMatches = array(
          '1-3' => 'today 10 hour',
          '*/4' => 'today 9 hour',
          '2,5-18' => 'today 20 hour'
        );

        foreach ($invalidMatches as $value => $date) {
            $field = new HourField($value);
            $this->assertFalse($field->matches(new \DateTime($date)), "$value matches $date");
        }
    }
}
