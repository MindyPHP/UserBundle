<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
                'label' => 'Создать пароль и войти',
                'attr' => ['class' => 'b-button'],
            ]);
    }
}
