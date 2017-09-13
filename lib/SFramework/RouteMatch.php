<?php

namespace SFramework;

use SFramework\Config\Config;
use SFramework\Config\ConfigInterface;
use SFramework\Config\RoutesConfig;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher as SymfonyUrlMatcher;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface as SymfonyUrlMatcherInterface;
use Symfony\Component\Routing\RequestContext as SymfonyRequestContext;

/**
 * Class RouteMatch
 *
 * @package SFramework
 */
class RouteMatch
{
    protected $config;
    /** @var  SymfonyUrlMatcherInterface */
    protected $urlMatcher;
    protected $routes;
    protected $context;
    protected $pathInfo;

    public function __construct(
        ConfigInterface $config = null
    ) {
        $this->config = $config;

        if (is_null($this->config)) {
            $this->config = new RoutesConfig();
        }
    }

    /**
     * @param SymfonyRequest $request
     */
    public function init(
        SymfonyRequest $request
    ) {
        $this->routes = $this->config->load();
        $this->pathInfo = $request->getPathInfo();
        $this->context = new SymfonyRequestContext($this->pathInfo);
        $this->setUrlMatcher();
    }

    /**
     * Tries to match a url to a configured route.
     *
     * @param SymfonyRequest $request
     *
     * @return null|RouteMatchResource
     */
    public function match()
    {
        try {
            $parameters = $this->urlMatcher->match($this->pathInfo);
        } catch (ResourceNotFoundException $e) {
            return null;
        }

        $resource = new RouteMatchResource();
        $resource->controller = $parameters['_controller'];
        $resource->action = $parameters['_action'];

        return $resource;
    }

    /**
     * @param SymfonyUrlMatcherInterface $urlMatcher
     */
    public function setUrlMatcher(SymfonyUrlMatcherInterface $urlMatcher = null)
    {
        $this->urlMatcher = $urlMatcher;

        if (is_null($this->urlMatcher)) {
            $this->urlMatcher = new SymfonyUrlMatcher(
                $this->routes, $this->context
            );
        }
    }
}