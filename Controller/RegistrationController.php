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
use Mindy\Bundle\UserBundle\EventListener\UserRegisteredEvent;
use Mindy\Bundle\UserBundle\Form\RegistrationFormType;
use Mindy\Bundle\UserBundle\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegistrationController extends Controller
{
    public function registration(Request $request)
    {
        $form = $this->createForm(RegistrationFormType::class, [], [
            'method' => 'POST',
        ]);

        if ('POST' === $request->getMethod()) {
            if ($form->handleRequest($request)->isValid()) {
                $data = $form->getData();

                $user = new User([
                    'email' => $data['email'],
                    'is_active' => false,
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

    public function success(Request $request)
    {
        return $this->render('user/registration/success.html');
    }

    public function confirm(Request $request, $token)
    {
        $user = User::objects()->get(['token' => $token]);
        if (null === $user) {
            throw new NotFoundHttpException();
        }

        if ($user->is_active) {
            $this->addFlash('success', 'Учетная запись уже активирована');

            return $this->redirect('/');
        }

        $user->is_active = true;
        $user->save(['is_active']);

        return $this->render('user/registration/confirm_success.html');
    }
}
