<?php

namespace Pfmgr\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Pfmgr\Exception;
use Pfmgr\Repository\AccountTransaction as Repository;

class AccountTransaction
{
    /**
     * RESTful GET route to fetch an account transaction by its ID
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @param Silex\Application $app
     * @throws Pfmgr\Exception
     * @return object Symfony\Component\HttpFoundation\JsonResponse
     */
    public function fetchAction(Request $request, Application $app)
    {
        $id = intval($request->get('id'));
        $transaction = $app['orm.em']->find('Pfmgr\Entity\AccountTransaction', $id);
        if ($transaction === null) {
            throw new Exception('Transaction not found');
        } else {
            return $app->json($transaction);
        }
    }

    /**
     * RESTful GET route to fetch all transactions for a given account
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @param Silex\Application $app
     * @throws Pfmgr\Exception
     * @return object Symfony\Component\HttpFoundation\JsonResponse
     */
    public function fetchByAccountAction(Request $request, Application $app)
    {
        $accountId = $request->get('id');
        $account = $app['orm.em']->find('Pfmgr\Entity\Account', $accountId);
        if (!$account instanceof \Pfmgr\Entity\Account) {
            throw new \InvalidArgumentException("Invalid account provided");
        }

        $results = $app['orm.em']->getRepository('Pfmgr\Entity\AccountTransaction')
                        ->findBy(array('account' => $account));

        return $app->json($results);
    }

    /**
     * RESTful POST route to create a new transaction
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @param Silex\Application $app
     * @throws InvalidArgumentException
     * @return object Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createAction(Request $request, Application $app)
    {
        $amount = (float) $request->get('inputAmount');

        $description = $request->get('inputDescription');
        if (empty($description)) {
            throw new \InvalidArgumentException("Transaction description not valid input");
        }

        $accountId = $request->get('inputAccountId');
        $account = $app['orm.em']->find('Pfmgr\Entity\Account', $accountId);
        if (!$account instanceof \Pfmgr\Entity\Account) {
            throw new \InvalidArgumentException("Invalid account provided");
        }

        try {
            $account->addTransaction($amount, $description, new \DateTime);
            $app['orm.em']->persist($account);
            $app['orm.em']->flush();
            return $app->json($account, 201);
        } catch (Exception $e) {
            $app['monolog']->addError($e->getMessage());
            throw new Exception('Transaction could not be created.');
        }
    }

    /**
     * RESTful GET route to get all transactions for a given user
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @param Silex\Application $app
     * @throws InvalidArgumentException
     * @return object Symfony\Component\HttpFoundation\JsonResponse
     */
    public function fetchByUserAction(Request $request, Application $app)
    {
        $id = $request->get('id');
        $repository = $app['orm.em']->getRepository('\Pfmgr\Entity\AccountTransaction');
        $results = $repository->findTransactionsByUser($id);
        return $app->json($results);
    }
}