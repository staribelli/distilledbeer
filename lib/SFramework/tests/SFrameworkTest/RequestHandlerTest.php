<?php

namespace SFrameworkTest;

use SFramework\BaseController;
use SFramework\JsonModel;
use SFramework\RequestHandler;
use SFramework\RouteMatch;
use SFramework\RouteMatchResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response;

class RequestHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleResourceFound()
    {
        $content = 'some content';
        $request = new SymfonyRequest();
        $routeMatchResource = new RouteMatchResource();
        $controllerMock = $this->getMockBuilder(BaseController::class)
            ->getMock();
        $controllerMock->expects($this->once())
            ->method('isGetRequest')
            ->willReturn($content);
        $routeMatchResource->controller = $controllerMock;
        $routeMatchResource->action = 'isGetRequest';

        $routeMatchMock = $this->getRouteMatchMock($request,
            $routeMatchResource);
        $requestHandler = new RequestHandler($routeMatchMock);

        $result = $requestHandler->handle($request);
        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals($content, $result->getContent());
    }

    public function testHandleJsonResponse()
    {
        $content = 'some json data';
        $response = new JsonModel();
        $response->setVariable('data', $content);
        $request = new SymfonyRequest();
        $routeMatchResource = new RouteMatchResource();
        $controllerMock = $this->getMockBuilder(BaseController::class)
            ->getMock();
        $controllerMock->expects($this->once())
            ->method('isGetRequest')
            ->willReturn($response);
        $routeMatchResource->controller = $controllerMock;
        $routeMatchResource->action = 'isGetRequest';

        $routeMatchMock = $this->getRouteMatchMock($request,
            $routeMatchResource);
        $requestHandler = new RequestHandler($routeMatchMock);

        $result = $requestHandler->handle($request);
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(json_encode($content), $result->getContent());
    }

    public function testHandleResourceNotFound()
    {
        $content = 'some json data';
        $response = new JsonModel();
        $response->setVariable('data', $content);
        $request = new SymfonyRequest();

        $routeMatchMock = $this->getRouteMatchMock($request, null);
        $requestHandler = new RequestHandler($routeMatchMock);

        $result = $requestHandler->handle($request);
        $this->assertInstanceOf(Response::class, $result);
        $this->assertTrue(is_string($result->getContent()));
        $this->assertEquals(Response::HTTP_NOT_FOUND, $result->getStatusCode());
    }

    /**
     * @param $request
     * @param $routeMatchResource
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getRouteMatchMock($request, $routeMatchResource)
    {
        $routeMatchMock = $this->getMockBuilder(RouteMatch::class)
            ->getMock();
        $routeMatchMock->expects($this->once())
            ->method('init')
            ->with($request);
        $routeMatchMock->expects($this->once())
            ->method('match')
            ->willReturn($routeMatchResource);

        return $routeMatchMock;
    }

}