<?php

namespace Pfmgr\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Pfmgr\Entity\User as UserEntity;

class User implements UserProviderInterface
{
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function loadUserByUsername($username)
    {
        $userRepo = $this->app['orm.em']->getRepository('\Pfmgr\Entity\User');
        return $userRepo->findOneBy(array('username' => $username));
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof UserEntity) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Pfmgr\Entity\User';
    }
}