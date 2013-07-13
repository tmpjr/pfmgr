<?php

namespace Pfmgr\Tests\Controller;

use Silex\WebTestCase;
use Pfmgr\Tests\Controller\TestBase;

/**
 * Test the main application
 *
 * @copyright 2013 Tom Ploskina Jr. <tploskina@gmail.com>
 * @author Tom Ploskina Jr. <tploskina@gmail.com>
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
class UserTest extends TestBase
{
	public function testFetchActionIsValid()
	{
		$client = $this->createClient();
		$client->request('GET', '/user/1');
		$response = $client->getResponse();
		$data = $response->getContent();

		$json = '{"id":1,"email":"el.toro@thebull.com","enabled":1,"roles":"ROLE_USER"}';

		$this->assertTrue($client->getResponse()->isOk());
		$this->assertTrue(200 === $client->getResponse()->getStatusCode());
		$this->assertJson($data);
		$this->assertJsonStringEqualsJsonString($data, $json);
	}

	public function testFetchActionErrorReturns403()
	{
		$client = $this->createClient();
		$client->request('GET', '/user/0');
		$response = $client->getResponse();
		$data = $response->getContent();

		//$this->assertTrue($client->getResponse()->isOk());
		$this->assertTrue(403 === $client->getResponse()->getStatusCode());
	}

	public function testCreateActionSuccess()
	{
		$client = $this->createClient();
		$client->request('POST', '/user/create', array('inputEmail' => 'webtestcase@webtest.com', 'inputPassword' => 'test'));
		$response = $client->getResponse();
		$data = $response->getContent();

		$this->assertJson($data);
		$this->assertTrue(201 === $client->getResponse()->getStatusCode());
	}
}
