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
use Mindy\Orm\Fields\EmailField;
use Mindy\Orm\Model;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Class AbstractUser
 *
 * @property string $email
 * @property string $password
 * @property string $salt
 * @property string $token
 * @property bool $is_active
 */
abstract class AbstractUser extends Model implements AdvancedUserInterface
{
    public static function getFields()
    {
        return [
            'email' => [
                'class' => EmailField::class,
            ],
            'password' => [
                'class' => CharField::class,
                'null' => true,
            ],
            'salt' => [
                'class' => CharField::class,
                'null' => true,
            ],
            'token' => [
                'class' => CharField::class,
                'null' => true,
            ],
            'is_active' => [
                'class' => BooleanField::class,
                'default' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($owner, $isNew)
    {
        if ($this->isNewRecord) {
            $this->salt = base64_encode(random_bytes(10));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        $this->password = null;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->is_active;
    }
}
