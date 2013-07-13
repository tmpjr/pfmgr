<?php

namespace Pfmgr\Tests\Controller;

use Silex\WebTestCase;
use Pfmgr\Tests\Controller\TestBase;

/**
 * Test the main application's Currency controller
 *
 * @copyright 2013 Tom Ploskina Jr. <tploskina@gmail.com>
 * @author Tom Ploskina Jr. <tploskina@gmail.com>
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
class CurrencyTest extends TestBase
{
    public function testFetchAllActionIsValid()
    {
        $client = $this->createClient();
        $client->request('GET', '/currency');
        $response = $client->getResponse();
        $data = $response->getContent();

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $this->assertJson($data);
    }
}