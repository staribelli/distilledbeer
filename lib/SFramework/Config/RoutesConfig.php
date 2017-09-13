<?php

namespace SFramework\Config;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Routing\Loader\YamlFileLoader as RouteYamlFileLoader;

/**
 * Class RouteConfig
 *
 * @package SFramework\Config
 */
class RoutesConfig implements ConfigInterface
{
    private static $routesFile = 'routes.yml';
    private $loader;

    public function __construct(LoaderInterface $loader = null)
    {
        $this->loader = $loader;

        if (is_null($this->loader)) {
            $fileLocator = new FileLocator([AppConfig::getAppConfigDir()]);
            $this->loader = new RouteYamlFileLoader($fileLocator);
        }
    }

    public function load()
    {
        return $this->loader->load(self::$routesFile);
    }
}