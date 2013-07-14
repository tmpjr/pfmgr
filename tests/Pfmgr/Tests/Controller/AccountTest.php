<?php

namespace Pfmgr\Tests\Controller;

use Silex\WebTestCase;
use Pfmgr\Tests\Controller\TestBase;

/**
 * Test the main application's Account controller
 *
 * @copyright 2013 Tom Ploskina Jr. <tploskina@gmail.com>
 * @author Tom Ploskina Jr. <tploskina@gmail.com>
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
class AccountTest extends TestBase
{
    public function testAccountCreateActionSuccess()
    {
        $client = $this->createClient();
        $client->request('POST', '/account/create',
            array(
                'userId' => 1,
                'currencyId' => 1,
                'inputName' => "Cash Money Yo"
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
        $client->request('GET', '/account/1');
        $response = $client->getResponse();
        $data = $response->getContent();

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $this->assertJson($data);
    }

    public function testFetchActionErrorReturns403()
    {
        $client = $this->createClient();
        $client->request('GET', '/account/0');
        $response = $client->getResponse();
        $data = $response->getContent();

        $this->assertTrue(403 === $client->getResponse()->getStatusCode());
    }
}