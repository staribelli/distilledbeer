<?php

namespace BeerMarket\Controller;

use BeerMarket\BeerService\BeerService;
use BeerMarket\BeerService\BeerServiceFactory;
use BeerMarket\BeerService\Model\Item;
use SFramework\BaseController;
use SFramework\JsonModel;

/**
 * Class RandomController
 * Provides a random beer.
 *
 * @package BeerMarket\Controller
 */
class RandomController extends BaseController
{
    /** @var \BeerMarket\BeerService\BeerService */
    private $beerService;
    protected $loader;

    public function __construct(BeerService $beerService = null)
    {
        $this->beerService = $beerService;

        if (is_null($this->beerService)) {
            $this->beerService = (new BeerServiceFactory())->createService();
        }
    }

    public function index()
    {
        $adapter = $this->beerService->getAdapter(Item::TYPE_BEER);

        // Not very nice as this blocks the page to be loaded.
        do {
            $data = $this->beerService->getRandomBeer($adapter);
        } while (empty($data));

        if ($this->isAjaxRequest()) {
            return $this->jsonResponse($data);
        }

        return $this->htmlResponse($data);
    }

    private function htmlResponse($data)
    {
        $view = $this->getView('index.html');

        return $view->render(['beer' => $data]);
    }

    private function jsonResponse($data)
    {
        $view = new JsonModel();
        $view->setVariable('data', $data);

        return $view;
    }
}