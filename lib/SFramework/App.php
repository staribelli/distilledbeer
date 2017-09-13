<?php

namespace SFramework;

use SFramework\Config\AppConfig;
use SFramework\Config\Config;
use SFramework\Config\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * Class App
 * Application class.
 *
 * @package SFramework
 */
final class App
{
    /** @var RequestHandler */
    private $requestHandler;
    private $config;
    private static $container;
    public static $appConfigFile = 'application.yml';

    public function __construct()
    {
        $this->requestHandler = new RequestHandler();
        $this->loadConfig();
    }

    public function run()
    {
        $request = Request::createFromGlobals();
        $this->requestHandler->handle($request)->send();
    }

    public function getConfig()
    {
        return $this->config;
    }

    private function loadConfig()
    {
        $locator = new FileLocator([AppConfig::getAppConfigDir()]);
        $loader = new YamlFileLoader($locator);
        $this->config = (new AppConfig($loader))->load();
    }
}
