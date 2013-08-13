<?php

namespace Pfmgr\Fixture;

use Silex\Application;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Pfmgr\Entity\User;
use Pfmgr\Entity\Account;
use Pfmgr\Entity\Currency;
use Pfmgr\Entity\AccountTransaction;

/**
 *  Data fixture is to populate the database with some intial values. Its
 *  main use case is for unit testing
 *
 * @copyright 2013 Tom Ploskina Jr. <tploskina@gmail.com>
 * @author Tom Ploskina Jr. <tploskina@gmail.com>
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @todo Break out into classes for each entity
 **/
class DataLoader implements FixtureInterface
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $encoder = $this->app['security.encoder.digest'];
        $user = new User;
        $user->setUsername('el.toro@thebull.com');
        $user->setPassword($encoder->encodePassword('test', null));
        $user->setEnabled(1);
        $user->setRoles('ROLE_USER');
        $user->setCreated(new \Datetime);
        $manager->persist($user);
        $manager->flush();

        $user2 = new User;
        $user2->setUsername('troy@fortythree.com');
        $user2->setPassword($encoder->encodePassword('nhy6&UJM', null));
        $user2->setEnabled(1);
        $user2->setRoles('ROLE_USER');
        $user2->setCreated(new \Datetime);
        $manager->persist($user2);
        $manager->flush();

        $currencyDollar = new Currency;
        $currencyDollar->setName('United States dollar');
        $currencyDollar->setCode('USD');
        $currencyDollar->setSymbol('$');
        $manager->persist($currencyDollar);
        $manager->flush();

        $currencyEuro = new Currency;
        $currencyEuro->setName('Euro');
        $currencyEuro->setCode('EUR');
        $currencyEuro->setSymbol('€');
        $manager->persist($currencyEuro);
        $manager->flush();

        $currencySol = new Currency;
        $currencySol->setName('Peruvian Nuevo Sol');
        $currencySol->setCode('PEN');
        $currencySol->setSymbol('S/.');
        $manager->persist($currencySol);
        $manager->flush();

        $account1 = new Account;
        $account1->setOwner($user);
        $account1->setName('Checking');
        $account1->setCurrency($currencyDollar);
        $account1->addTransaction(10000, 'Initial Deposit', new \DateTime);
        $account1->addTransaction(2500, 'July Paycheck', new \DateTime);
        $manager->persist($account1);
        $manager->flush();
        $accId = $account1->getId();
        $acc1 = $manager->find('Pfmgr\Entity\Account', $accId);
        $acc2 = $manager->find('Pfmgr\Entity\Account', $accId);
        $acc1->addTransaction(-1650, 'Rent', new \DateTime);
        $acc2->addTransaction(-845.45, 'Audi A8 Payment', new \DateTime);
        $manager->persist($acc1);
        $manager->persist($acc2);
        $manager->flush();

        $account2 = new Account;
        $account2->setOwner($user2);
        $account2->setName('Checking');
        $account2->setCurrency($currencyDollar);
        $account2->addTransaction(25000, 'Initial Deposit', new \DateTime);
        $account2->addTransaction(1500, 'May Paycheck', new \DateTime);
        $manager->persist($account2);
        $manager->flush();
        $accId = $account2->getId();
        $acc1 = $manager->find('Pfmgr\Entity\Account', $accId);
        $acc2 = $manager->find('Pfmgr\Entity\Account', $accId);
        $acc1->addTransaction(-8800, 'Mortgage', new \DateTime);
        $acc2->addTransaction(-750.45, 'BMW Payment', new \DateTime);
        $manager->persist($acc1);
        $manager->persist($acc2);
        $manager->flush();

    }
}