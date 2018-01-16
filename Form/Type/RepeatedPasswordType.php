<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Form\Type;

use Mindy\Bundle\UserBundle\Form\Listener\FixRepeatedTypeListener;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RepeatedPasswordType extends RepeatedType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // TODO see https://github.com/symfony/symfony/issues/21242
        $builder->addEventSubscriber(new FixRepeatedTypeListener());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'type' => PasswordType::class,
            'invalid_message' => 'Пароли не совпадают',
            'first_options' => [
                'label' => 'Пароль',
            ],
            'second_options' => [
                'label' => 'Повтор пароля',
            ],
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Length([
                    'min' => 6,
                    'max' => 20,
                ]),
            ],
        ]);
    }
}
