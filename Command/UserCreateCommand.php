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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UserCreateCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'user:create';

    protected function configure()
    {
        $this
            ->setDefinition([
                new InputOption('name', null, InputOption::VALUE_REQUIRED, 'Username'),
                new InputOption('email', null, InputOption::VALUE_REQUIRED, 'Email'),
                new InputOption('password', null, InputOption::VALUE_REQUIRED, 'Password'),
                new InputOption('is_superuser', null, InputOption::VALUE_REQUIRED, 'Is superuser', false),
            ])
            ->setDescription('Create user');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getOption('name');
        $email = $input->getOption('email');
        $password = $input->getOption('password');
        if (empty($name) || empty($email) || empty($password)) {
            $output->writeln('<error>Name, email and password cannot be empty</error>');

            return 1;
        }

        if (User::objects()->filter(['email' => $email])->count() > 0) {
            $output->writeln(sprintf(
                '<error>User with %s email address already exists</error>',
                $email
            ));

            return 1;
        }

        $user = new User([
            'is_active' => true,
            'name' => $name,
            'email' => $email,
            'is_superuser' => (bool) $input->getOption('is_superuser'),
        ]);

        $user->password = $this->getContainer()->get('security.password_encoder')
            ->encodePassword($user, $password);

        if (false === $user->save()) {
            throw new \RuntimeException('Could not create user');
        }

        $output->writeln('User created');
    }
}
