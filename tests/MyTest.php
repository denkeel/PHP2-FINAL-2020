<?php

namespace App\tests;

use PHPUnit\Framework\TestCase;

class MyTest extends TestCase
{
    /**
     * @param $i
     * @param $x
     * @param $y
     *
     * @dataProvider getData
     */
    public function testFirst($i, $x, $y)
    {
        $a = $x + $y;
        $this->assertEquals($i, $a);
    }

    public function getData()
    {
        return [
            [10, 5, 5],
            [11, 5, 6],
        ];
    }
}
