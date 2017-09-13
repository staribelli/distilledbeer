<?php

namespace BeerMarket\BeerService\Adapter;

use BeerMarket\BeerService\Model\Collection;
use BeerMarket\BeerService\Model\Item;

class BreweryAdapter implements AdapterInterface
{
    public function populateCollection($data)
    {
        $collection = new Collection();
        foreach ($data as $item) {
            if (!empty($item->description)) {
                $item = $this->hydrateModel($item);
                $collection->add($item);
            }
        }

        return $collection;
    }

    private function hydrateModel($data)
    {
        $model = new Item();
        $model->name = $data->name;
        $model->description = $data->description;

        if (!empty($data->images)) {
            $model->imgPath = $data->images->squareMedium;
        }

        return $model;
    }
}