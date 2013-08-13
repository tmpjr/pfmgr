<?php

namespace Pfmgr\Security;

use Symfony\Component\Security\Http\Logout\DefaultLogoutSuccessHandler;
use Symfony\Component\HttpFoundation\Response;

/**
 * {@inheritDoc}
 */
class LogoutSuccessHandler extends DefaultLogoutSuccessHandler
{
    /**
     * {@inheritDoc}
     */
    public function onLogoutSuccess(Request $request)
    {
        return new Response('Logged out', 401);
    }
}