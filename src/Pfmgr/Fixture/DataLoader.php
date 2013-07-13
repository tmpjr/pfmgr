<?php

namespace Pfmgr\Fixture;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Pfmgr\Entity\User;
use Pfmgr\Entity\Account;
use Pfmgr\Entity\Currency;
use Pfmgr\Entity\AccountTransaction;

class DataLoader implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $encoder = new BCryptPasswordEncoder(10);
        $user = new User;
        $user->setEmail('el.toro@thebull.com');
        $user->setPassword($encoder->encodePassword('nhy6&UJM', null));
        $user->setEnabled(1);
        $user->setRoles('ROLE_USER');
        $user->setCreated(new \Datetime);
        $manager->persist($user);
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
        $account2->setOwner($user);
        $account2->setName('Checking');
        $account2->setCurrency($currencyEuro);
        $manager->persist($account2);
        $manager->flush();
    }
}