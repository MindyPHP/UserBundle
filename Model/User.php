<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Model;

use Mindy\Orm\Fields\BooleanField;
use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\ImageField;

/**
 * Class User.
 *
 * @property string $name
 * @property string $avatar
 * @property string $phone
 * @property bool $is_superuser
 *
 * @method static UserManager objects($instance = null)
 */
class User extends AbstractUser
{
    public static function getFields()
    {
        return array_merge(parent::getFields(), [
            'name' => [
                'class' => CharField::class,
            ],
            'avatar' => [
                'class' => ImageField::class,
                'null' => true,
            ],
            'phone' => [
                'class' => CharField::class,
            ],
            'is_superuser' => [
                'class' => BooleanField::class,
                'default' => false,
            ],
        ]);
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        if ($this->is_superuser) {
            return ['ROLE_ADMIN'];
        }

        return [];
    }
}
