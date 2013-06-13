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

use Helthe\Component\Chronos\Field\YearField;

/**
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class YearFieldTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers YearField::__construct
     * @covers YearField::isValid
     * @covers YearField::buildValidationRegex
     * @covers YearField::buildFieldRangeRegex
     */
    public function testConstructor()
    {
        $values = array('*', '1970-1982', '1991-2002/2,2004,2005-2014');

        foreach ($values as $value) {
            new YearField($value);
        }
    }

    /**
     * @covers YearField::matches
     * @covers YearField::isInRange
     * @covers YearField::isInIncrementsOfRange
     */
    public function testMatches()
    {
        $validMatches = array(
          '*' => 'now',
          '1983,1990' => '1983',
          '1970-1982' => '1980',
          '*/10' => '1990',
          '1991-2002/2' => '1995'
        );

        foreach ($validMatches as $value => $date) {
            $field = new YearField($value);
            $this->assertTrue($field->matches(new \DateTime($date)), "$value does not match $date");
        }

        $invalidMatches = array(
          '1983' => 'now',
          '1970-1982' => '1990',
          '*/10' => '1984',
          '1991-2002/2' => '1998'
        );

        foreach ($invalidMatches as $value => $date) {
            $field = new YearField($value);
            $this->assertFalse($field->matches(new \DateTime($date)), "$value matches $date");
        }
    }
}
