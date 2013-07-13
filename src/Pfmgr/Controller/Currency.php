<?php

namespace Pfmgr\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Pfmgr\Exception;

/**
 * Currrency Controller to handle RESTful routes
 *
 * @copyright 2013 Tom Ploskina Jr. <tploskina@gmail.com>
 * @author Tom Ploskina Jr. <tploskina@gmail.com>
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
class Currency
{
    /**
     * RESTful GET route to fetch all currencies
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @param Silex\Application $app
     * @throws Pfmgr\Exception
     * @return object Symfony\Component\HttpFoundation\JsonResponse
     */
    public function fetchAllAction(Request $request, Application $app)
    {
        $results = $app['orm.em']->getRepository('Pfmgr\Entity\Currency')->findAll();
        if (count($results) < 1) {
            throw new Exception("Currency data is empty.");
        } else {
            return $app->json($results);
        }
    }
}