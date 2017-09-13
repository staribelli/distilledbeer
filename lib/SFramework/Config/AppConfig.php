<?php

namespace SFramework\Config;

use SFramework\Config\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Class AppConfig
 *
 * @package SFramework\Config
 */
class AppConfig implements ConfigInterface
{
    private static $routesFile = 'routes.yml';
    private static $appConfigDir = __DIR__ . '/../../../config/';
    private static $appConfigFile = 'application.yml';
    private $loader;

    public function __construct(LoaderInterface $loader = null)
    {
        $this->loader = $loader;

        if (is_null($this->loader)) {
            $locator = new FileLocator([self::getAppConfigDir()]);
            $this->loader = new YamlFileLoader($locator);
        }
    }

    public function load()
    {
        return $this->loader->load(self::$appConfigFile);
    }

    public static function getAppConfigDir()
    {
        return self::$appConfigDir;
    }
}