<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Tests;

use Mindy\Bundle\UserBundle\Model\User;
use Mindy\Bundle\UserBundle\Provider\UserProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProviderTest extends TestCase
{
    public function testUserProvider()
    {
        /** @var UserProvider|MockObject $provider */
        $provider = $this
            ->getMockBuilder(UserProvider::class)
            ->setMethods(['fetchUser'])
            ->getMock();
        $user = $this
            ->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        $provider->method('fetchUser')->willReturn($user);

        $this->assertTrue($provider->supportsClass(User::class));
        $this->assertFalse($provider->supportsClass(\stdClass::class));

        $user = $provider->loadUserByUsername('foo@bar.com');
        $this->assertInstanceOf(UserInterface::class, $user);
        $this->assertInstanceOf(UserInterface::class, $provider->refreshUser($user));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testUserProviderException()
    {
        /** @var UserProvider|MockObject $provider */
        $provider = $this
            ->getMockBuilder(UserProvider::class)
            ->setMethods(['fetchUser'])
            ->getMock();
        $provider->method('fetchUser')->willReturn(null);

        $provider->loadUserByUsername('foo@bar.com');
    }
}
