<?php

namespace App\tests;

use App\main\App;
use App\services\BasketService;
use App\services\Request;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BasketServiceTest extends TestCase
{

    /**
     * @param $price
     * @param $result
     *
     * @dataProvider getDataForGetCurrency
     */
    public function testGetCurrency($price, $result)
    {
        $service = new BasketService();
        $currency = $service->getCurrency($price);

        $this->assertEquals($currency, $result);
        $this->assertNotEmpty($currency);
    }

    public function testGetBasket()
    {
        $mockRequest = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockRequest->method('getSession')
            ->willReturn(11);

        $mockApp = $this->getMockBuilder(App::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockApp->request = $mockRequest;

        $service = new BasketService();
        $result = $service->getBasket($mockApp);
        $this->assertEquals(11, $result);
    }

    public function testGetBasket2()
    {
        $mockRequest = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockRequest->method('getSession')
            ->willReturn(0);

        /**
         * @var MockObject|App $mockApp
         */
        $mockApp = $this->getMockBuilder(App::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockApp->request = $mockRequest;

        $service = new BasketService();
        $result = $service->getBasket($mockApp);
        $this->assertIsArray($result);
    }


    public function getDataForGetCurrency()
    {
        return [
            [10, 350],
            [20, 700],
            [3, 105],
        ];
    }
}
