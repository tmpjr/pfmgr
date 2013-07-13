<?php

namespace Pfmgr\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use InvalidArgumentException;
use Pfmgr\Exception;
use Pfmgr\Entity\User;
use Pfmgr\Entity\Currency;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class Account
{
    /**
     * RESTful POST route to create a new account
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @param Silex\Application $app
     * @throws InvalidArgumentException
     * @return object Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createAction(Request $request, Application $app)
    {
        $userId = $request->get('userId');
        $user = $app['orm.em']->find('Pfmgr\Entity\User', $userId);
        if (!$user instanceof User) {
            throw new InvalidArgumentException("Invalid user provided");
        }

        $currencyId = $request->get('currencyId');
        $currency = $app['orm.em']->find('Pfmgr\Entity\Currency', $currencyId);
        if (!$currency instanceof Currency) {
            throw new InvalidArgumentException("Invalid currency provided");
        }

        $accountName = $request->get('inputName');
        if (empty($accountName)) {
            throw new InvalidArgumentException("Currency name not provied");
        }

        try {
            $account = new \Pfmgr\Entity\Account;
            $account->setOwner($user);
            $account->setCurrency($currency);
            $account->setName($accountName);
            $app['orm.em']->persist($account);
            $app['orm.em']->flush();
            $app['monolog']->addInfo(sprintf('A new account (id : %s) was created.', $account->getId()));
            return $app->json($account, 201);
        } catch (\Exception $e) {
            $app['monolog']->addError($e->getMessage());
            throw new Exception('Account could not be created.');
        }
    }
}