<?php

namespace Pfmgr\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Pfmgr\Exception\AccountNotFoundException;
use Pfmgr\Exception;
use Pfmgr\Entity\User;
use Pfmgr\Entity\Currency;

class Account
{
    /**
     * RESTful GET route to fetch an account by its ID
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @param Silex\Application $app
     * @throws Pfmgr\Exception
     * @return object Symfony\Component\HttpFoundation\JsonResponse
     */
    public function fetchAction(Request $request, Application $app)
    {
        $id = intval($request->get('id'));
        $account = $app['orm.em']->find('Pfmgr\Entity\Account', $id);
        if ($account === null) {
            throw new Exception('Account not found');
        } else {
            return $app->json($account);
        }
    }

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

        $startingBalance = 0;
        if ($request->get('inputStartingBalance') > 0) {
            $startingBalance = $request->get('inputStartingBalance');
        }

        try {
            $account = new \Pfmgr\Entity\Account;
            $account->setOwner($user);
            $account->setCurrency($currency);
            $account->setName($accountName);
            if ($startingBalance > 0) {
                $account->addTransaction($startingBalance, 'Starting Balance', new \DateTime);
            }
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