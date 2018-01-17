<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\EventListener;

use Mindy\Bundle\UserBundle\Model\User;
use Symfony\Component\EventDispatcher\Event;

class UserAutoRegisteredEvent extends Event
{
    const EVENT_NAME = 'user.auto_registered';

    /**
     * @var User
     */
    protected $user;
    /**
     * @var string
     */
    protected $rawPassword;

    /**
     * UserRegisteredEvent constructor.
     *
     * @param User   $user
     * @param string $rawPassword
     */
    public function __construct(User $user, string $rawPassword)
    {
        $this->user = $user;
        $this->rawPassword = $rawPassword;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getRawPassword(): string
    {
        return $this->rawPassword;
    }
}
