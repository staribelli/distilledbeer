<?php
/**
 * Created by PhpStorm.
 * User: sab
 * Date: 11/09/17
 * Time: 22:44
 */

namespace SFramework;

use SFramework\Request\Params;

/**
 * Class BaseController
 * Provides common functions to be used in controllers.
 *
 * @package SFramework
 */
abstract class BaseController
{
    private static $commonFolder = 'common';
    protected $renderer;

    /**
     * Fetches the given template.
     *
     * @param $name
     *
     * @return \Twig_TemplateWrapper
     */
    public function getView($name)
    {
        return $this->getViewRenderer()->load($name);
    }

    /**
     * Retrieves params from GET or POST
     * escaping them.
     *
     * @return Params
     */
    public function getRequestParams()
    {
        $params = new Params();

        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                $params->offsetSet($key, htmlspecialchars($value));
            }
        }

        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $params->offsetSet($key, htmlspecialchars($value));
            }
        }

        return $params;
    }

    /**
     * Checks if the current request is a GET.
     *
     * @return bool
     */
    public function isGetRequest()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Checks if the current request is a POST.
     *
     * @return bool
     */
    public function isPostRequest()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Checks if the current request is ajax.
     *
     * @return bool
     */
    public function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    // NOTE: The view renderer could be configured at application level.
    private function getViewRenderer()
    {
        if (is_null($this->renderer)) {
            $reflect = new \ReflectionClass($this);
            $templateFolder = strtolower(str_replace('Controller', '',
                $reflect->getShortName()));
            $currentFolder = dirname($reflect->getFileName());
            $loader = new \Twig_Loader_Filesystem(
                [
                    $currentFolder . '/../template/' . $templateFolder,
                    $currentFolder . '/../template/' . self::$commonFolder
                ]);
            $twig = new \Twig_Environment($loader);
            $this->renderer = $twig;
        }

        return $this->renderer;
    }
}