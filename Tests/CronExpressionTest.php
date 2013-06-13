<?php

/*
 * This file is part of the Helthe Chronos package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Component\Chronos\Tests;

use Helthe\Component\Chronos\CronExpression;

/**
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class CronExpressionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers CronExpression::__construct
     * @covers CronExpression::matches
     * @covers CronExpression::setExpression
     */
    public function testMatches()
    {
        $validMatches = array(
          '* * * * *' => 'now',
          '0 0 ? */2 FRI#2 *' => 'june 14th 2013',
        );

        foreach ($validMatches as $value => $date) {
            $expression = new CronExpression($value);
            $this->assertTrue($expression->matches(new \DateTime($date)), "$value does not match $date");
        }

        $invalidMatches = array(
          '1 * * * *' => 'midnight',
        );

        foreach ($invalidMatches as $value => $date) {
            $expression = new CronExpression($value);
            $this->assertFalse($expression->matches(new \DateTime($date)), "$value matches $date");
        }
    }

    /**
     * @covers CronExpression::__construct
     * @covers CronExpression::getExpression
     * @covers CronExpression::setExpression
     */
    public function setExpression()
    {
        $expression = new CronExpression('* * * * *');
        $expression->setExpression('@hourly');

        $this->assertEquals('0 * * * *', $expression->getExpression());
    }
}
