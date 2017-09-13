<?php

namespace SFrameworkTest;

use SFramework\Config\ConfigInterface;
use SFramework\RouteMatch;
use SFramework\RouteMatchResource;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface as SymfonyUrlMatcherInterface;
use Symfony\Component\Routing\RequestContext as SymfonyRequestContext;

class RouteMatchTest extends \PHPUnit_Framework_TestCase
{
    public function testMatchSuccess()
    {
        $expected = new RouteMatchResource();
        $expected->controller = 'c';
        $expected->action = 'a';
        $configMock = $this->getMockBuilder(ConfigInterface::class)
            ->getMock();
        $requestMock = $this->getMockBuilder(SymfonyRequest::class)
            ->getMock();
        $requestContextMock
            = $this->getMockBuilder(SymfonyRequestContext::class,
            ['setBaseUrl'])
            ->getMock();
        $urlMatcherMock
            = $this->getMockBuilder(SymfonyUrlMatcherInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $urlMatcherMock->expects($this->once())
            ->method('match')
            ->willReturn([
                '_controller' => 'c',
                '_action'     => 'a'
            ]);
        $routeMatch = new RouteMatch($configMock);
        $routeMatch->setUrlMatcher($urlMatcherMock);
        $result = $routeMatch->match($requestMock, $requestContextMock);
        $this->assertEquals($expected, $result);
    }

    public function testMatchThrowsException()
    {
        $configMock = $this->getMockBuilder(ConfigInterface::class)
            ->getMock();
        $requestMock = $this->getMockBuilder(SymfonyRequest::class)
            ->getMock();
        $requestContextMock
            = $this->getMockBuilder(SymfonyRequestContext::class,
            ['setBaseUrl'])
            ->getMock();
        $urlMatcherMock
            = $this->getMockBuilder(SymfonyUrlMatcherInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $urlMatcherMock->expects($this->once())
            ->method('match')
            ->willThrowException(new ResourceNotFoundException());
        $routeMatch = new RouteMatch($configMock);
        $routeMatch->setUrlMatcher($urlMatcherMock);
        $result = $routeMatch->match($requestMock, $requestContextMock);
        $this->assertNull($result);
    }
}