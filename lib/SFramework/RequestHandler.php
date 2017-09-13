<?php

namespace SFramework;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Class RequestHandler
 *
 * @package SFramework
 */
class RequestHandler implements HttpKernelInterface
{
    private static $notFoundPath = __DIR__ . '/../../public/404.html';
    protected $routeMatch;

    public function __construct(RouteMatch $routeMatch = null)
    {
        $this->routeMatch = $routeMatch;

        if (is_null($this->routeMatch)) {
            $this->routeMatch = new RouteMatch();
        }
    }

    /**
     * Handles an incoming request.
     * Renders a 404 page if no route has been matched.
     *
     * @param SymfonyRequest $request
     * @param int            $type
     * @param bool           $catch
     *
     * @return JsonResponse|Response
     */
    public function handle(
        SymfonyRequest $request,
        $type = self::MASTER_REQUEST,
        $catch = true
    ) {
        $this->routeMatch->init($request);
        $resource = $this->routeMatch->match();

        if (is_null($resource)) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $content = file_get_contents(self::$notFoundPath);
            $response->setContent($content);
        } else {
            /** @var BaseController $controller */
            if (is_string($resource->controller)) {
                $controller = new $resource->controller;
            } else {
                $controller = $resource->controller;
            }
            $controllerResponse = $controller->{$resource->action}();

            if ($controllerResponse instanceof JsonModel) {
                $response
                    = new JsonResponse($controllerResponse->getVariable('data'));
            } else {
                $response = new Response();
                $response->setContent($controllerResponse);
            }
        }

        return $response;
    }
}