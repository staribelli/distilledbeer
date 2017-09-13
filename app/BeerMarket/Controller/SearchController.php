<?php

namespace BeerMarket\Controller;

use BeerMarket\BeerService\BeerServiceFactory;
use BeerMarket\BeerService\DTO\SearchParams;
use BeerMarket\BeerService\Model\Item;
use BeerMarket\Form\SearchForm;
use SFramework\BaseController;

/**
 * Class SearchController
 * Provides methods to search for beers/breweries.
 *
 * @package BeerMarket\Controller
 */
class SearchController extends BaseController
{
    /**
     * @var \BeerMarket\BeerService\BeerService
     */
    private $beerService;

    public function __construct()
    {
        $this->beerService = (new BeerServiceFactory())->createService();
    }

    public function index()
    {
        $form = new SearchForm();
        $params = $this->getRequestParams();
        $data = [];
        $errors = [];

        if ($this->isPostRequest()) {
            $form->setData((array)$params);
            $form->validate();
            $errors = $form->getErrors();

            if ($form->isValid()) {
                $searchParams = new SearchParams();
                $searchParams->query = $query = $params['search'];
                $searchParams->type = $query = $params['type'];
                $adapter = $this->beerService->getAdapter($searchParams->type);
                $data = $this->beerService->search($searchParams, $adapter);
            }

        }

        $view = $this->getView('index.html');

        return $view->render([
            'data'         => $data,
            'beerValue'    => Item::TYPE_BEER,
            'breweryValue' => Item::TYPE_BREWERY,
            'errors'       => $errors,
            'searchParams' => $params
        ]);
    }
}