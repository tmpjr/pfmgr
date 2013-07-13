<?php

namespace Pfmgr\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pfmgr\Exception\UserNotFoundException;
use Pfmgr\Exception;

/**
 * User Controller to handle RESTful routes
 *
 * @copyright 2013 Tom Ploskina Jr. <tploskina@gmail.com>
 * @author Tom Ploskina Jr. <tploskina@gmail.com>
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
class User
{
    /**
     * RESTful GET route to fetch a user by its ID
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @param Silex\Application $app
     * @throws Pfmgr\Exception
     * @return object Symfony\Component\HttpFoundation\JsonResponse
     */
    public function fetchAction(Request $request, Application $app)
    {
        $id = intval($request->get('id'));
        $user = $app['orm.em']->find('Pfmgr\Entity\User', $id);
        if ($user === null) {
            throw new UserNotFoundException('User not found');
        } else {
            return $app->json($user);
        }
    }

    /**
     * RESTful POST route to create a new user
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @param Silex\Application $app
     * @return object Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createAction(Request $request, Application $app)
    {
        try {
            $pwd_hash = $app['security.encoder.digest']->encodePassword($request->get('inputPassword'), null);
            $user = new \Pfmgr\Entity\User;
            $user->setEmail($request->get('inputEmail'));
            $user->setPassword($pwd_hash);
            $user->setRoles('ROLE_USER');
            $user->setEnabled(0);
            $user->setCreated(new \DateTime());
            $app['orm.em']->persist($user);
            $app['orm.em']->flush();
            $app['monolog']->addInfo(sprintf('A new user (id : %s) was created.', $user->getId()));
            return $app->json($user, 201);
        } catch (\Exception $e) {
            $app['monolog']->addError($e->getMessage());
            throw new Exception('User could not be created.');
        }
    }
}