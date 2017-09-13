<?php

namespace BeerMarket\BeerService\Adapter;

use BeerMarket\BeerService\Model\Collection;
use BeerMarket\BeerService\Model\Item;

class BeerAdapter implements AdapterInterface
{
    public function populateCollection($data)
    {
//        var_dump($data);
        $collection = new Collection();
        foreach ($data as $item) {
            if (!empty($item->description) && !empty($item->labels)) {
                $beer = $this->hydrateModel($item);
                $collection->add($beer);
            }
        }

        return $collection;
    }

    private function hydrateModel($data)
    {
        $model = new Item();
        $model->name = $data->nameDisplay;
        if (!empty($data->description)) {
            $model->description = $data->description;
        }
        if (!empty($data->breweries)) {
            $model->brewery = $data->breweries[0]->name;
        }

        if (!empty($data->labels)) {
            $model->imgPath = $data->labels->medium;
        }

        return $model;
    }
}