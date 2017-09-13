<?php

namespace SFrameworkTest\Config\Loader;

use SFramework\Config\Config;
use SFramework\Config\RoutesConfig;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Routing\RouteCollection;

class RoutesConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadRoutes()
    {
        $expected = new RouteCollection();
        $loaderMock = $this->getMockBuilder(LoaderInterface::class)
            ->getMock();
        $loaderMock->expects($this->once())
            ->method('load')
            ->willReturn($expected);
        $config = new RoutesConfig($loaderMock);
        $result = $config->load();
        $this->assertEquals($expected, $result);
    }

}