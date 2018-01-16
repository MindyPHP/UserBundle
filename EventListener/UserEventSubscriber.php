<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\EventListener;

use Mindy\Bundle\MailBundle\Helper\Mail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class UserListener
 */
class UserEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var Mail
     */
    protected $mail;

    /**
     * @var UrlGeneratorInterface
     */
    protected $generator;

    /**
     * UserListener constructor.
     *
     * @param Mail                  $mail
     * @param UrlGeneratorInterface $generator
     */
    public function __construct(Mail $mail, UrlGeneratorInterface $generator)
    {
        $this->mail = $mail;
        $this->generator = $generator;
    }

    /**
     * @param UserLostPasswordEvent $event
     *
     * @throws \Exception
     */
    public function onUserLostPassword(UserLostPasswordEvent $event)
    {
        $model = $event->getUser();

        $this->mail->send('Восстановление пароля на сайте', $model->email, 'user/mail/lost_password', [
            'model' => $model,
            'confirm_url' => $this->generator->generate('user_lost_password_confirm', [
                'token' => $model->token,
            ], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    }

    /**
     * @param UserAutoRegisteredEvent $event
     *
     * @throws \Exception
     */
    public function onUserAutoRegistered(UserAutoRegisteredEvent $event)
    {
        $model = $event->getUser();

        $this->mail->send('Вы зарегистрированы на сайте', $model->email, 'user/mail/registration', [
            'model' => $model,
            'raw_password' => $event->getRawPassword(),
        ]);
    }

    /**
     * @param UserRegisteredEvent $event
     *
     * @throws \Exception
     */
    public function onUserRegistered(UserRegisteredEvent $event)
    {
        $model = $event->getUser();

        $this->mail->send('Подтверждение регистрации на сайте', $model->email, 'user/mail/registration', [
            'model' => $model,
            'confirm_url' => $this->generator->generate('user_registration_confirm', [
                'token' => $model->token,
            ], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            UserLostPasswordEvent::EVENT_NAME => 'onUserLostPassword',
            UserRegisteredEvent::EVENT_NAME => 'onUserRegistered',
            UserAutoRegisteredEvent::EVENT_NAME => 'onUserRegistered',
        ];
    }
}
