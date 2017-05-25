<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Admin;

use Mindy\Bundle\AdminBundle\Admin\AbstractModelAdmin;
use Mindy\Bundle\UserBundle\Form\Admin\UserForm;
use Mindy\Bundle\UserBundle\Model\User;

class UserAdmin extends AbstractModelAdmin
{
    public $columns = ['name', 'email', 'is_active', 'is_manager'];

    public function getFormType()
    {
        return UserForm::class;
    }

    public function getModelClass()
    {
        return User::class;
    }
}
