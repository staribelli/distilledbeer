<?php

namespace BeerMarket\BeerService;

use BeerMarket\BeerService\Adapter\AdapterInterface;
use BeerMarket\BeerService\Adapter\BeerAdapter;
use BeerMarket\BeerService\Adapter\BreweryAdapter;
use BeerMarket\BeerService\DTO\SearchParams;
use BeerMarket\BeerService\Model\Collection;
use BeerMarket\BeerService\Model\Item;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BeerService
 *
 * @package BeerMarket\BeerService
 */
class BeerService
{
    /** @var Client */
    private $client;

    /** @var array */
    private $config;

    public function __construct(Client $client, array $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * Fetches a random beer from the API.
     *
     * @return Item Model hydrated with relevant information.
     */
    public function getRandomBeer(AdapterInterface $adapter)
    {
        $params = ['withBreweries' => 'Y'];
        $data = $this->makeRequest('beer/random', $params);
        $collection = $adapter->populateCollection($data);

        return $collection->current();
    }

    /**
     * Gets the adapter to be used.
     *
     * @param $type
     *
     * @return BeerAdapter|BreweryAdapter
     */
    // NOTE: this could go in a factory.
    public function getAdapter($type)
    {
        switch ($type) {
            case Item::TYPE_BEER:
                return new BeerAdapter();
            case Item::TYPE_BREWERY:
                return new BreweryAdapter();
            default:
                throw new \InvalidArgumentException('Type not supported');
        }
    }

    /**
     * Makes a search for the given type, for the query given.
     *
     * @param SearchParams     $params
     * @param AdapterInterface $adapter
     *
     * @return Collection
     * @throws \Exception
     */
    public function search(SearchParams $params, AdapterInterface $adapter)
    {
        $queryParams = [
            'type' => $params->type,
            'q'    => $params->query
        ];
        $data = $this->makeRequest('search', $queryParams);

        // No data returned
        if (empty($data->data)) {
            return new Collection();
        }

        $collection = $adapter->populateCollection($data->data);

        return $collection;
    }

    /**
     * Calls the API with the given params.
     *
     * @param       $url
     * @param array $params
     *
     * @return \stdClass Json response
     * @throws \Exception
     */
    private function makeRequest($url, array $params = [], $method = 'GET')
    {
        $defaultParams = ['key' => $this->config['apiKey']];
        $queryParams = array_merge($params, $defaultParams);

        try {
            $response = $this->client->request($method, $url, [
                'query' => $queryParams
            ]);
        } catch (ServerException $e) {
            // log something
            return null;
        }

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new \Exception('Unable to fetch beer data');
        }

        $data = \json_decode($response->getBody());

        if (is_null($data)) {
            throw new \Exception('Invalid JSon response.');
        }

        return $data;
    }
}


