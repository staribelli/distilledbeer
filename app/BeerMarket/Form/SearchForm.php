<?php

namespace BeerMarket\Form;

use BeerMarket\BeerService\Model\Item;
use SFramework\Form\AbstractForm;

/**
 * Class SearchForm
 *
 * @package BeerMarket\Form
 */
class SearchForm extends AbstractForm
{
    public function rules()
    {
        return [
            'search' => [
                'type'     => 'text',
                'required' => true,
                'regex'    => '/^([A-Za-z]*[0-9]*\s*-*)*$/'
            ],
            'type'   => [
                'type'          => 'radio',
                'required'      => true,
                'allowedValues' => [
                    Item::TYPE_BEER,
                    Item::TYPE_BREWERY
                ]
            ]
        ];
    }
}