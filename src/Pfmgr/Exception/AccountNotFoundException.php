<?php

namespace Pfmgr\Exception;

class AccountNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('The requested account was not found');
    }
}