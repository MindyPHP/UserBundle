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
use Mindy\Bundle\UserBundle\EventListener\UserLostPasswordEvent;
use Mindy\Bundle\UserBundle\Form\LostPasswordFormType;
use Mindy\Bundle\UserBundle\Model\User;
use Mindy\Bundle\UserBundle\Utils\TokenGenerator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LostPasswordController extends Controller
{
    public function lost(Request $request, TokenGenerator $tokenGenerator, EventDispatcher $eventDispatcher)
    {
        $form = $this->createForm(LostPasswordFormType::class, [], [
            'method' => 'POST',
            'action' => $this->generateUrl('user_lost_password'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user = User::objects()->get([
                'email' => $data['email'],
            ]);
            $user->token = $tokenGenerator->generate();
            $user->save(['token']);

            $eventDispatcher->dispatch(
                UserLostPasswordEvent::EVENT_NAME,
                new UserLostPasswordEvent($user)
            );

            $this->addFlash('success', 'Инструкция по восстановлению пароля выслана на вашу электронную почту');

            return $this->redirectToRoute('user_lost_password_success');
        }

        return $this->render('user/lost/form.html', [
            'form' => $form->createView(),
        ]);
    }

    public function success(Request $request)
    {
        return $this->render('user/lost/success.html');
    }

    public function confirm(Request $request, $token)
    {
        $user = User::objects()->get(['token' => $token]);
        if (null === $user) {
            throw new NotFoundHttpException();
        }

        return $this->redirectToRoute('user_set_password', [
            'token' => $user->token,
        ]);
    }
}
