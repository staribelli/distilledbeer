<?php

namespace BeerMarketTest\BeerService\Adapter;

use BeerMarket\BeerService\Adapter\BreweryAdapter;
use BeerMarket\BeerService\Model\Collection;
use BeerMarket\BeerService\Model\Item;

class BreweryAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testPopulateCollection()
    {
        $data = (object)[
            (object)[
                'name' => 'Item with description',
                'description' => 'Fancy brewery',
                'images' => (object)[
                    'squareMedium' => 'imgMedium.png'
                ]
            ],
            (object)[
                'name' => 'Item without description',
                'images' => (object)[
                    'squareMedium' => 'imgMedium.png'
                ]
            ],
            (object)[
                'name' => 'Item without image',
                'description' => 'THE brewery'
            ]
        ];
        $expected = $this->buildCollection();
        $adapter = new BreweryAdapter();
        $result = $adapter->populateCollection($data);
        $this->assertEquals($expected, $result);
    }

    private function buildCollection()
    {
        $collection = new Collection();
        $item = new Item();
        $item->name = 'Item with description';
        $item->description = 'Fancy brewery';
        $item->imgPath = 'imgMedium.png';
        $collection->add($item);

        $item = new Item();
        $item->name = 'Item without image';
        $item->description = 'THE brewery';
        $collection->add($item);
        return $collection;
    }
}