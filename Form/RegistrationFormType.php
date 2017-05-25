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
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Ваше имя',
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Электронная почта',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new Assert\Callback(function ($value, ExecutionContextInterface $context, $payload) {
                        $qs = User::objects()->filter(['email' => $value]);
                        if ($qs->count() > 0) {
                            $context->buildViolation('Указанный электронный адрес почты уже используется на сайте')
                                ->addViolation();
                        }
                    })
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Пароли не совпадают',
                'required' => true,
                'first_options' => ['label' => 'Пароль'],
                'second_options' => ['label' => 'Повтор пароля'],
                'constraints' => [
                    new Assert\Length([
                        'min' => 6,
                        'max' => 20,
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Зарегистрироваться',
            ]);
    }
}
