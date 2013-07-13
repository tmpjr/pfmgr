<?php

namespace Pfmgr\Exception;

class UserNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('The requested user was not found');
    }
}