<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Controller;

use Mindy\Bundle\MindyBundle\Controller\Controller;
use Mindy\Bundle\UserBundle\Model\User;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends Controller
{
    public function loginAction(Request $request, User $user = null)
    {
        if ($user && $user->is_active) {
            return $this->redirect('/');
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('user/auth/login.html', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    public function logoutAction()
    {
        return $this->redirect('/');
    }
}
