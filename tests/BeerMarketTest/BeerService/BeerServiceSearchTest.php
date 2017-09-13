<?php

namespace BeerMarketTest\BeerService;

use BeerMarket\BeerService\BeerService;
use BeerMarket\BeerService\DTO\SearchParams;
use BeerMarket\BeerService\Model\Collection;
use BeerMarket\BeerService\Model\Item;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use BeerMarket\BeerService\Adapter\AdapterInterface;

class BeerServiceSearchTest extends \PHPUnit_Framework_TestCase
{
	public function testSearchSuccess()
	{
		$config = ['apiKey' => 'abc'];
		$json = json_encode([
			'data' => [
				'nameDisplay' => 'Black Betty',
				'description' => 'Porter',
				'breweries' => [
					[
						'name' => 'Sabrina Microbrewery'
					]
				]
			]
		]);
        $searchParams = new SearchParams();
        $searchParams->type = 'some';
        $searchParams->query = 'Porter';
		$item = new Item();
        $item->name = 'Black Betty';
        $item->description = 'Porter';
        $item->brewery = 'Sabrina Microbrewery';
        $collection = new Collection();
        $collection->add($item);

		$responseMock = $this->getMockBuilder(ResponseInterface::class)
			->getMock();
		$responseMock->expects($this->once())
			->method('getStatusCode')
			->willReturn(Response::HTTP_OK);
		$responseMock->expects($this->once())
			->method('getBody')
			->willReturn($json);
		$clientMock = $this->getClientMock($searchParams, $responseMock);
        $adapterMock = $this->getAdapterMock($collection);
		$service = new BeerService($clientMock, $config);
		$result = $service->search($searchParams, $adapterMock);
		$this->assertEquals($collection, $result);
	}

    public function testSearchNoResults()
    {
        $config = ['apiKey' => 'abc'];
        $json = json_encode([]);
        $searchParams = new SearchParams();
        $searchParams->type = 'some';
        $searchParams->query = 'Porter';
        $collection = new Collection();

        $responseMock = $this->getMockBuilder(ResponseInterface::class)
            ->getMock();
        $responseMock->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_OK);
        $responseMock->expects($this->once())
            ->method('getBody')
            ->willReturn($json);
        $clientMock = $this->getClientMock($searchParams, $responseMock);
        $adapterMock = $this->getMockBuilder(AdapterInterface::class)
            ->getMock();
        $service = new BeerService($clientMock, $config);
        $result = $service->search($searchParams, $adapterMock);
        $this->assertEquals($collection, $result);
    }

    private function getClientMock(SearchParams $searchParams, $responseMock)
	{
		$clientMock = $this->getMockBuilder(Client::class)
			->getMock();
		$clientMock->expects($this->once())
			->method('request')
			->with('GET', 'search', [
				'query' => [
					'type' => $searchParams->type,
                    'q' => $searchParams->query,
					'key' => 'abc'
				]])
			->willReturn($responseMock);
		return $clientMock;
	}

    public function getAdapterMock($collection)
    {
        $adapterMock = $this->getMockBuilder(AdapterInterface::class)
            ->getMock();
        $adapterMock->expects($this->once())
            ->method('populateCollection')
            ->willReturn($collection);

        return $adapterMock;
    }
}