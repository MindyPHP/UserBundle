<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Form\Admin;

use Symfony\Component\Form\FormBuilderInterface;

class UserUpdateForm extends UserCreateForm
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('password');
    }

    public function getParent()
    {
        return UserCreateForm::class;
    }
}
