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
use Mindy\Bundle\UserBundle\EventListener\UserRegisteredEvent;
use Mindy\Bundle\UserBundle\Form\RegistrationFormType;
use Mindy\Bundle\UserBundle\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegistrationController extends Controller
{
    public function registrationAction(Request $request)
    {
        $form = $this->createForm(RegistrationFormType::class, [], [
            'method' => 'POST',
        ]);

        if ($request->getMethod() === 'POST') {
            if ($form->handleRequest($request)->isValid()) {
                $data = $form->getData();

                $token = random_int(10000, 99999);

                $user = new User([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'token' => $token,
                    'salt' => base_convert(sha1(uniqid(mt_rand(), true)), 16, 36),
                ]);

                $user->password = $this->get('security.password_encoder')
                    ->encodePassword($user, $data['password']);

                if (false == $user->save()) {
                    throw new \RuntimeException('Failed to save user');
                }

                $this->get('event_dispatcher')->dispatch(
                    UserRegisteredEvent::EVENT_NAME,
                    new UserRegisteredEvent($user)
                );

                return $this->redirectToRoute('user_registration_success');
            }
        }

        return $this->render('user/registration/registration.html', [
            'form' => $form->createView(),
        ]);
    }

    public function successAction(Request $request)
    {
        return $this->render('user/registration/success.html');
    }

    public function confirmAction(Request $request, $token)
    {
        $user = User::objects()->get(['token' => $token]);
        if (null === $user) {
            throw new NotFoundHttpException();
        }

        if ($user->is_active) {
            $this->addFlash('success', 'Учетная запись уже активирована');

            return $this->redirect('/');
        }

        $user->token = null;
        $user->is_active = true;

        if (false === $user->save()) {
            throw new \RuntimeException('Fail to save user');
        }

        return $this->redirect('/');
    }
}
