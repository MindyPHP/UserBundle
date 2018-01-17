<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Tests;

use Mindy\Bundle\UserBundle\ArgumentValueResolver\UserArgumentValueResolver;
use Mindy\Bundle\UserBundle\Model\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserArgumentValueResolverTest extends TestCase
{
    public function testValueResolver()
    {
        $tokenStorage = $this
            ->getMockBuilder(TokenStorageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $user = $this
            ->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();

        $token = $this
            ->getMockBuilder(TokenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $token->method('getUser')->willReturn($user);

        $tokenStorage->method('getToken')->willReturn($token);

        $metadata = $this
            ->getMockBuilder(ArgumentMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();
        $metadata->method('getType')->willReturn($user);

        $v = new UserArgumentValueResolver($tokenStorage);
        $r = new Request();

        $this->assertTrue($v->supports($r, $metadata));
    }

    public function testValueResolverTokenNull()
    {
        $tokenStorage = $this
            ->getMockBuilder(TokenStorageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $tokenStorage->method('getToken')->willReturn(null);

        $metadata = $this
            ->getMockBuilder(ArgumentMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();
        $user = $this
            ->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        $metadata->method('getType')->willReturn($user);

        $v = new UserArgumentValueResolver($tokenStorage);
        $r = new Request();

        $this->assertFalse($v->supports($r, $metadata));
    }

    public function testValueResolverIncorrectArgument()
    {
        $tokenStorage = $this
            ->getMockBuilder(TokenStorageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $tokenStorage->method('getToken')->willReturn(null);

        $metadata = $this
            ->getMockBuilder(ArgumentMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();
        $user = new \stdClass();
        $metadata->method('getType')->willReturn($user);

        $v = new UserArgumentValueResolver($tokenStorage);
        $r = new Request();

        $this->assertFalse($v->supports($r, $metadata));
    }

    public function testResolve()
    {
        $tokenStorage = $this
            ->getMockBuilder(TokenStorageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $metadata = $this
            ->getMockBuilder(ArgumentMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();
        $token = $this
            ->getMockBuilder(TokenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $token->method('getUser')->willReturn(null);
        $tokenStorage->method('getToken')->willReturn($token);

        $v = new UserArgumentValueResolver($tokenStorage);
        $r = new Request();

        $generator = $v->resolve($r, $metadata);
        $this->assertInstanceOf(\Generator::class, $generator);
        $this->assertCount(1, $generator);
    }
}
