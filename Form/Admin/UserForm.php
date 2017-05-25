<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Form\Admin;

use Mindy\Bundle\AdminBundle\Form\Type\ButtonsType;
use Mindy\Bundle\FormBundle\Form\Type\FileType;
use Mindy\Bundle\UserBundle\Model\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Имя',
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Электронная почта',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                ],
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Фото / аватар пользователя',
                'required' => false,
                'constraints' => [
                    new Assert\Image(),
                ],
            ])
            ->add('is_active', CheckboxType::class, [
                'label' => 'Активирован',
                'required' => false,
            ])
            ->add('is_manager', CheckboxType::class, [
                'label' => 'Права менеджера',
                'required' => false,
            ])
            ->add('buttons', ButtonsType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
