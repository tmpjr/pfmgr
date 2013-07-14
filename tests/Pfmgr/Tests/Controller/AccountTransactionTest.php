<?php

namespace Pfmgr\Tests\Controller;

use Silex\WebTestCase;
use Pfmgr\Tests\Controller\TestBase;

/**
 * Test the main application's Account Transaction controller
 *
 * @copyright 2013 Tom Ploskina Jr. <tploskina@gmail.com>
 * @author Tom Ploskina Jr. <tploskina@gmail.com>
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
class AccountTransactionTest extends TestBase
{
    public function testCreateActionSuccess()
    {
        $client = $this->createClient();
        $client->request('POST', '/transaction/create',
            array(
                'inputAccountId' => 1,
                'inputDescription' => 'Contract work misc',
                'inputAmount' => 7500
            )
        );
        $response = $client->getResponse();
        $data = $response->getContent();

        $this->assertJson($data);
        $this->assertTrue(201 === $client->getResponse()->getStatusCode());
    }

    public function testFetchActionIsValid()
    {
        $client = $this->createClient();
        $client->request('GET', '/transaction/1');
        $response = $client->getResponse();
        $data = $response->getContent();

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $this->assertJson($data);
    }

    public function testFetchActionErrorReturns403()
    {
        $client = $this->createClient();
        $client->request('GET', '/transaction/0');
        $response = $client->getResponse();
        $data = $response->getContent();

        $this->assertTrue(403 === $client->getResponse()->getStatusCode());
    }

    public function testFetchByAccountActionIsValid()
    {
        $client = $this->createClient();
        $client->request('GET', '/transaction/account/1');
        $response = $client->getResponse();
        $data = $response->getContent();

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $this->assertJson($data);
    }

    public function testFetchByAccountActionErrorReturns403()
    {
        $client = $this->createClient();
        $client->request('GET', '/transaction/account/0');
        $response = $client->getResponse();
        $data = $response->getContent();

        $this->assertTrue(403 === $client->getResponse()->getStatusCode());
    }

    public function testFetchByUserActionIsValid()
    {
        $client = $this->createClient();
        $client->request('GET', '/transaction/user/1');
        $response = $client->getResponse();
        $data = $response->getContent();

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $this->assertJson($data);
    }
}