<?php

namespace BeerMarket\BeerService;

use GuzzleHttp\Client as GuzzleClient;
use SFramework\Config\AppConfig;
use SFramework\Config\Config;
use SFramework\FactoryInterface;

/**
 * Class BeerServiceFactory
 *
 * @package BeerMarket\BeerService
 */
class BeerServiceFactory implements FactoryInterface
{
    public function createService()
    {
        // A bit messy, the app config is already loaded.
        $appConfig = new AppConfig();
        $config = $appConfig->load();
        $client = new GuzzleClient([
            'base_uri' => $config['api']['brewerydb']['url']
        ]);
        $service = new BeerService($client, $config['api']['brewerydb']);

        return $service;
    }
}