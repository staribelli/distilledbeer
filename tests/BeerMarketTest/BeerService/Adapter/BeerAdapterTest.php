<?php

namespace BeerMarketTest\BeerService\Adapter;

use BeerMarket\BeerService\Adapter\BeerAdapter;
use BeerMarket\BeerService\Adapter\BreweryAdapter;
use BeerMarket\BeerService\Model\Collection;
use BeerMarket\BeerService\Model\Item;

class BeerAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testPopulateCollection()
    {
        $data = (object)[
            (object)[
                'nameDisplay' => 'Item with description',
                'description' => 'Super tasty beer',
                'labels' => (object)[
                    'medium' => 'imgMedium.png'
                ]
            ],
            (object)[
                'nameDisplay' => 'Item without description',
                'images' => (object)[
                    'squareMedium' => 'imgMedium.png'
                ]
            ],
            (object)[
                'nameDisplay' => 'Item without image',
                'description' => 'THE tasty beer'
            ]
        ];
        $expected = $this->buildCollection();
        $adapter = new BeerAdapter();
        $result = $adapter->populateCollection($data);
        $this->assertEquals($expected, $result);
    }

    private function buildCollection()
    {
        $collection = new Collection();
        $item = new Item();
        $item->name = 'Item with description';
        $item->description = 'Super tasty beer';
        $item->imgPath = 'imgMedium.png';
        $collection->add($item);
        return $collection;
    }
}