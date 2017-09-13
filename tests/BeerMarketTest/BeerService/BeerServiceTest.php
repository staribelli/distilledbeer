<?php

namespace BeerMarketTest\BeerService;

use BeerMarket\BeerService\BeerService;
use BeerMarket\BeerService\Model\Collection;
use BeerMarket\BeerService\Model\Item;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use BeerMarket\BeerService\Adapter\AdapterInterface;

class BeerServiceTest extends \PHPUnit_Framework_TestCase
{
	public function testGetRandomBeerSuccess()
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
		$expected = new Item();
		$expected->name = 'Black Betty';
		$expected->description = 'Porter';
		$expected->brewery = 'Sabrina Microbrewery';
        $collection = new Collection();
        $collection->add($expected);
		$responseMock = $this->getMockBuilder(ResponseInterface::class)
			->getMock();
		$responseMock->expects($this->once())
			->method('getStatusCode')
			->willReturn(Response::HTTP_OK);
		$responseMock->expects($this->once())
			->method('getBody')
			->willReturn($json);
		$clientMock = $this->getClientMock($responseMock);
        $adapterMock = $this->getAdapterMock($collection);
		$service = new BeerService($clientMock, $config);
		$result = $service->getRandomBeer($adapterMock);
		$this->assertEquals($expected, $result);
	}

	public function testGetRandomBeerNotOK()
	{
		$config = ['apiKey' => 'abc'];
		$responseMock = $this->getMockBuilder(ResponseInterface::class)
			->getMock();
		$responseMock->expects($this->once())
			->method('getStatusCode')
			->willReturn(Response::HTTP_NOT_FOUND);
		$clientMock = $this->getClientMock($responseMock);
		$this->expectException(\Exception::class);
		$this->expectExceptionMessage('Unable to fetch beer data');
		$service = new BeerService($clientMock, $config);
        $adapterMock = $adapterMock = $this->getMockBuilder(AdapterInterface::class)
            ->getMock();
		$service->getRandomBeer($adapterMock);
	}

	public function testGetRandomBeerInvalidJson()
	{
		$config = ['apiKey' => 'abc'];
		$json = 'invalid: one';
		$expected = new Item();
		$expected->name = 'Black Betty';
		$expected->description = 'Porter';
		$expected->brewery = 'Sabrina Microbrewery';
		$responseMock = $this->getMockBuilder(ResponseInterface::class)
			->getMock();
		$responseMock->expects($this->once())
			->method('getStatusCode')
			->willReturn(Response::HTTP_OK);
		$responseMock->expects($this->once())
			->method('getBody')
			->willReturn($json);
		$clientMock = $this->getClientMock($responseMock);
		$this->expectException(\Exception::class);
		$this->expectExceptionMessage('Invalid JSon response.');
		$service = new BeerService($clientMock, $config);
        $adapterMock = $adapterMock = $this->getMockBuilder(AdapterInterface::class)
            ->getMock();
		$result = $service->getRandomBeer($adapterMock);
		$this->assertEquals($expected, $result);
	}

	private function getClientMock($responseMock)
	{
		$clientMock = $this->getMockBuilder(Client::class)
			->getMock();
		$clientMock->expects($this->once())
			->method('request')
			->with('GET', 'beer/random', [
				'query' => [
					'withBreweries' => 'Y',
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