<?php

namespace Pfmgr\Tests\Controller;

use Silex\WebTestCase;
use Pfmgr\Fixture\DataLoader;

/**
 * Base class for web functional tests
 *  - setup app
 *  - load up fixtures
 *
 * @copyright 2013 Tom Ploskina Jr. <tploskina@gmail.com>
 * @author Tom Ploskina Jr. <tploskina@gmail.com>
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
class TestBase extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../../../../app/app.php';
        $app['debug'] = true;
        $app['exception_handler']->disable();
        $app['session.test'] = true;
        return $app;
    }

    public function setUp()
    {
        parent::setUp();

        // truncate tables
        $this->app['orm.em']->getConnection()->query('START TRANSACTION;
            SET FOREIGN_KEY_CHECKS=0;
            TRUNCATE user;
            TRUNCATE account;
            TRUNCATE account_transaction;
            TRUNCATE currency;
            SET FOREIGN_KEY_CHECKS=1; COMMIT;');

        // Run fixtures to populate database
        $loader = new DataLoader($this->app);
        $loader->load($this->app['orm.em']);
    }
}