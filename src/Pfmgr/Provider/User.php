<?php

namespace Pfmgr\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User as SecurityCoreUser;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class User implements UserProviderInterface
{
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function loadUserByUsername($username)
    {
        $userRepo = $this->app['orm.em']->getRepository('\Pfmgr\Entity\User');
        $user = $userRepo->findUserByEmail($username);

        if ($user === null) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        $this->app['monolog']->addDebug('getPassword: ' . $user->getPassword());

        return new SecurityCoreUser($user->getEmail(), $user->getPassword(), explode(',', $user->getRoles()), true, true, true, true);
        //return new SecurityCoreUser('el.toro@thebull.com', '$2y$10$VNTJ5YbXe2nVmcTAZR4d0e1FFqHpqmKIIYlAdcM/eXw3Z1ALa1c/W', array('ROLE_USER'), true, true, true, true);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof SecurityCoreUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }
}