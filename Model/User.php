<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Model;

use Mindy\Orm\Fields\BooleanField;
use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\EmailField;
use Mindy\Orm\Fields\ImageField;
use Mindy\Orm\Model;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Class User.
 *
 * @property string $name
 * @property string $email
 * @property string $avatar
 * @property string $phone
 * @property string $password
 * @property string $salt
 * @property bool $is_manager
 * @property bool $is_active
 * @property string $token
 */
class User extends Model implements AdvancedUserInterface
{
    public static function getFields()
    {
        return [
            'name' => [
                'class' => CharField::class,
            ],
            'avatar' => [
                'class' => ImageField::class,
                'null' => true,
            ],
            'email' => [
                'class' => EmailField::class,
            ],
            'phone' => [
                'class' => CharField::class,
            ],
            'password' => [
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
            'salt' => [
                'class' => CharField::class,
                'null' => true,
            ],
            'is_manager' => [
                'class' => BooleanField::class,
                'default' => false,
            ],
        ];
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        if ($this->is_manager) {
            return ['ROLE_MEMBER', 'ROLE_MANAGER'];
        }

        return ['ROLE_MEMBER'];
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->password = null;
    }

    public function __toString()
    {
        return (string) $this->name;
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
