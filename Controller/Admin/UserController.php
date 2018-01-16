<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Controller\Admin;

use Mindy\Bundle\MindyBundle\Controller\Controller;
use Mindy\Bundle\UserBundle\Form\Admin\UserCreateForm;
use Mindy\Bundle\UserBundle\Form\Admin\UserPasswordForm;
use Mindy\Bundle\UserBundle\Form\Admin\UserUpdateForm;
use Mindy\Bundle\UserBundle\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    public function list(Request $request)
    {
        $qs = User::objects();
        $pager = $this->createPagination($qs);

        return $this->render('admin/user/user/list.html', [
            'users' => $pager->paginate(),
            'pager' => $pager->createView(),
        ]);
    }

    public function create(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserCreateForm::class, $user, [
            'method' => 'POST',
            'action' => $this->generateUrl('admin_user_user_create'),
        ]);
        if ($form->handleRequest($request) && $form->isValid()) {
            $user = $form->getData();

            $user->password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->password);

            if (false === $user->save()) {
                throw new \RuntimeException('Error while save user');
            }

            $this->addFlash('success', 'Пользователь успешно создан');

            return $this->redirectToRoute('admin_user_user_list');
        }

        return $this->render('admin/user/user/create.html', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    public function view(Request $request, $id)
    {
        $user = User::objects()->get(['id' => $id]);
        if (null === $user) {
            throw new NotFoundHttpException();
        }

        return $this->render('admin/user/user/view.html', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::objects()->get(['id' => $id]);
        if (null === $user) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(UserUpdateForm::class, $user, [
            'method' => 'POST',
            'action' => $this->generateUrl('admin_user_user_update', ['id' => $id]),
        ]);
        if ($form->handleRequest($request) && $form->isValid()) {
            $user = $form->getData();
            if (false === $user->save()) {
                throw new \RuntimeException('Error while save user');
            }

            $this->addFlash('success', 'Пользователь успешно обновлен');

            return $this->redirectToRoute('admin_user_user_list');
        }

        return $this->render('admin/user/user/update.html', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    public function password(Request $request, $id)
    {
        $user = User::objects()->get(['id' => $id]);
        if (null === $user) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(UserPasswordForm::class, $user, [
            'method' => 'POST',
            'action' => $this->generateUrl('admin_user_user_password', ['id' => $id]),
        ]);
        if ($form->handleRequest($request) && $form->isValid()) {
            $user = $form->getData();

            $user->password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->password);

            if (false === $user->save()) {
                throw new \RuntimeException('Error while save user');
            }

            $this->addFlash('success', 'Пароль пользователя изменен');

            return $this->redirectToRoute('admin_user_user_list');
        }

        return $this->render('admin/user/user/password.html', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    public function remove(Request $request, $id)
    {
        $user = User::objects()->get(['id' => $id]);
        if (null === $user) {
            throw new NotFoundHttpException();
        }

        $user->delete();

        $this->addFlash('success', 'Пользователь успешно удален');

        return $this->redirectToRoute('admin_user_user_list');
    }
}
