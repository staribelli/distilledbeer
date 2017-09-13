<?php

namespace BeerMarket\BeerService\Adapter;

use BeerMarket\BeerService\Model\Collection;

/**
 * Interface AdapterInterface
 *
 * @package BeerMarket\BeerService\Adapter
 */
interface AdapterInterface
{
    /**
     * Populates a collection with objects of type
     * \BeerMarket\BeerService\Model\Item.
     *
     * @param $data
     *
     * @return Collection
     */
    public function populateCollection($data);
}