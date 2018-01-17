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

/**
 * Class UserRegisteredEvent
 */
class UserRegisteredEvent extends Event
{
    const EVENT_NAME = 'user.registered';

    /**
     * @var User
     */
    protected $user;

    /**
     * UserRegisteredEvent constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
