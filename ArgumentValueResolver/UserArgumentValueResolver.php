<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\ArgumentValueResolver;

use Mindy\Bundle\UserBundle\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserArgumentValueResolver implements ArgumentValueResolverInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * UserArgumentValueResolver constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        if (false === ($argument->getType() instanceof User)) {
            return false;
        }

        $token = $this->tokenStorage->getToken();

        if (false === ($token instanceof TokenInterface)) {
            return false;
        }

        return $token->getUser() instanceof UserInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield $this->tokenStorage->getToken()->getUser();
    }
}
