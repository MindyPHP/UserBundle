<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Controller;

use Mindy\Bundle\MindyBundle\Controller\Controller;
use Mindy\Bundle\UserBundle\Form\ChangePasswordFormType;
use Mindy\Bundle\UserBundle\Form\SetPasswordFormType;
use Mindy\Bundle\UserBundle\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PasswordController extends Controller
{
    public function set(Request $request, $token)
    {
        $user = User::objects()->get(['token' => $token]);
        if (null === $user) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(SetPasswordFormType::class, [], [
            'method' => 'POST',
        ]);

        if ($form->handleRequest($request)->isValid()) {
            $data = $form->getData();

            $user->salt = substr(md5(time().$user->pk), 0, 10);
            $user->password = $this->get('security.password_encoder')->encodePassword($user, $data['password']);
            $user->token = null;

            if (false === $user->save()) {
                throw new \RuntimeException('Failed to set user password');
            }

            return $this->redirectToRoute('user_login');
        }

        return $this->render('user/password/set.html', [
            'form' => $form->createView(),
        ]);
    }

    public function change(Request $request)
    {
        if (false === $this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->createAccessDeniedException();
        }

        $form = $this->createForm(ChangePasswordFormType::class, [], [
            'method' => 'POST',
        ]);

        $user = $this->getUser();
        if ($form->handleRequest($request)->isValid()) {
            $data = $form->getData();

            $user->salt = substr(md5(time().$user->phone), 0, 10);
            $user->password = $this->get('security.password_encoder')->encodePassword($user, $data['password']);
            $user->token = null;

            if (false === $user->save()) {
                throw new \RuntimeException('Failed to set user password');
            }

            return $this->redirectToRoute('user_login');
        }

        return $this->render('user/password/change.html', [
            'form' => $form->createView(),
        ]);
    }
}
