<?php

namespace Bizbozo\AdventOfCode\Tests\Ranges;

use Bizbozo\AdventOfCode\Ranges\InvalidArgumentException;
use Bizbozo\AdventOfCode\Ranges\Range;
use PHPUnit\Framework\TestCase;

class RangeTest extends TestCase
{
    /**
     * Test contains method
     */
    public function testContains()
    {
        $range = new Range(1, 10);

        $this->assertTrue($range->contains(5));
        $this->assertFalse($range->contains(11));
    }

    /**
     * Test start and end properties
     */
    public function testRangeProperties()
    {
        $range = new Range(1, 10);

        $this->assertEquals(1, $range->start);
        $this->assertEquals(10, $range->end);
    }

    /**
     * Test invalid range
     */
    public function testInvalidRange()
    {
        $this->expectException(InvalidArgumentException::class);

        $range = new Range(10, 1);
    }

    public function testIntersection()
    {
        $range1 = new Range(1, 5);
        $range2 = new Range(4, 8);

        $intersect = $range1->intersect($range2);

        $this->assertTrue($intersect->contains(4));
        $this->assertTrue($intersect->contains(5));
        $this->assertFalse($intersect->contains(3));
        $this->assertFalse($intersect->contains(6));
    }

    /**
     * Test divergence method
     */
    public function testDivergence()
    {
        $range1 = new Range(1, 10);
        $range2 = new Range(6, 15);

        $divergences = $range1->difference($range2);

        $this->assertEquals(1, count($divergences));
        $this->assertTrue($divergences[0]->contains(1));
        $this->assertTrue($divergences[0]->contains(5));
        $this->assertFalse($divergences[0]->contains(6));
        $this->assertFalse($divergences[0]->contains(10));
    }

    public function testDoubleRangeDivergence()
    {
        $range1 = new Range(1, 10);
        $range2 = new Range(4, 6);

        $divergences = $range1->difference($range2);

        $this->assertCount(2, $divergences);

        $this->assertTrue($divergences[0]->contains(1));
        $this->assertTrue($divergences[0]->contains(3));
        $this->assertFalse($divergences[0]->contains(4));

        $this->assertTrue($divergences[1]->contains(7));
        $this->assertTrue($divergences[1]->contains(10));
        $this->assertFalse($divergences[1]->contains(6));
    }

    public function testEmptyIntersection()
    {
        $range1 = new Range(1, 5);
        $range2 = new Range(6, 10);

        $intersection = $range1->intersect($range2);

        $this->assertNull($intersection);  // Change this according to your implementation
    }

    /**
     * Test divergence method for non-overlapping ranges
     */
    public function testNonOverlappingDifference()
    {
        $range1 = new Range(1, 10);
        $range2 = new Range(11, 20);

        $divergences = $range1->difference($range2);

        $this->assertEquals(1, $divergences[0]->start);

        $this->assertEquals(1, $divergences[0]->start);
        $this->assertEquals(10, $divergences[0]->end);
        $this->assertNotEquals(11, $divergences[1]->start);
        $this->assertNotEquals(20, $divergences[1]->end);
    }


}
