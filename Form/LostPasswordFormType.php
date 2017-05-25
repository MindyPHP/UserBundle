<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Form;

use Mindy\Bundle\UserBundle\Model\User;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class LostPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Электронная почта',
                'constraints' => [
                    new Assert\Callback(function ($value, ExecutionContextInterface $context, $payload) {
                        $qs = User::objects()->filter(['email' => $value]);
                        if ($qs->count() == 0) {
                            $context->buildViolation('Пользователь с таким адресом электронной почты не зарегистрирован на сайте')
                                ->addViolation();
                        }
                    }),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Восстановить пароль',
            ]);
    }
}
