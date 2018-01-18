<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Command;

use Mindy\Bundle\UserBundle\Model\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UserPasswordCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'user:password';

    protected function configure()
    {
        $this
            ->setDescription('Update user password')
            ->setDefinition(
                new InputDefinition([
                    new InputOption('email', null, InputOption::VALUE_REQUIRED),
                    new InputOption('password', null, InputOption::VALUE_REQUIRED),
                ])
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getOption('email');
        $password = $input->getOption('password');
        if (empty($email) || empty($password)) {
            $output->writeln('<error>Email and password cannot be empty</error>');

            return 1;
        }

        $user = User::objects()->get(['email' => $email]);
        if (null === $user) {
            $output->writeln('<error>User not found</error>');

            return 1;
        }

        $user->password = $this->getContainer()->get('security.password_encoder')
            ->encodePassword($user, $password);

        if (false === $user->save()) {
            throw new \RuntimeException('Could not create user');
        }

        $output->writeln('User saved');
    }
}
