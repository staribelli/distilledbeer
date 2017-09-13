<?php

namespace BeerMarket\BeerService\Model;

/**
 * Class Item
 *
 * @package BeerMarket\BeerService\Model
 */
class Item implements \JsonSerializable
{
    const TYPE_BEER = 'beer';
    const TYPE_BREWERY = 'brewery';

    public $name;
    public $description;
    public $imgPath = '/img/beer_placeholder.png';
    public $brewery;

    function jsonSerialize()
    {
        return [
            'name'        => $this->name,
            'description' => $this->description,
            'imgPath'     => $this->imgPath,
            'brewery'     => $this->brewery
        ];
    }
}
