<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FloatTripleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAttributes()
    {
        $floatTriple = new \DataCollection\FloatTriple();
        $data = [-8.784, 11.123, 9.786];
        $floatTriple->setCompressedData($data);
        $compressedData = $floatTriple->getCompressedData();
        $this->assertEquals(0b0010100101010111001000000001101100100111111000010111111001000100, $compressedData);
        $firstValue = $floatTriple->getFirstValue();
        $secondValue = $floatTriple->getSecondValue();
        $thirdValue = $floatTriple->getThirdValue();
        $this->assertEquals($data[0], $firstValue);
    }
}
