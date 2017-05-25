<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\EventListener;

use Mindy\Bundle\MailBundle\Helper\Mail;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class UserListener
 */
class UserListener
{
    /**
     * @var Mail
     */
    protected $email;

    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * UserListener constructor.
     *
     * @param Mail                  $mail
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(Mail $mail, UrlGeneratorInterface $urlGenerator)
    {
        $this->mail = $mail;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param UserLostPasswordEvent $event
     */
    public function onUserLostPassword(UserLostPasswordEvent $event)
    {
        $user = $event->getUser();

        $this->mail->send('Восстановление пароля', $user->email, 'user/mail/lost_password', [
            'token' => $user->token,
            'lost_url' => $this->urlGenerator->generate('user_lost_password_confirm', [
                'token' => $user->token
            ], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }

    /**
     * @param UserRegisteredEvent $event
     */
    public function onUserRegistered(UserRegisteredEvent $event)
    {
        $user = $event->getUser();

        $this->mail->send('Подтверждение регистрации', $user->email, 'user/mail/registration_confirm', [
            'token' => $user->token,
            'confirm_url' => $this->urlGenerator->generate('user_registration_confirm', [
                'token' => $user->token
            ], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }
}
