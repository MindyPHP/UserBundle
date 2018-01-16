<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Form;

use Mindy\Bundle\UserBundle\Form\Transformer\ToLowerCaseTransformer;
use Mindy\Bundle\UserBundle\Form\Type\RepeatedPasswordType;
use Mindy\Bundle\UserBundle\Model\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Электронная почта',
                'constraints' => [
                    new Assert\Email(),
                    new Assert\NotBlank(),
                    new Assert\Callback(function ($value, ExecutionContextInterface $context, $payload) {
                        if (0 === User::objects()->filter(['email' => $value])->count()) {
                            $context->buildViolation('Пользователь с таким адресом электронной почты уже существует')
                                ->addViolation();
                        }
                    }),
                ],
            ])
            ->add('password', RepeatedPasswordType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Зарегистрироваться',
            ]);

        $builder
            ->get('email')
            ->addModelTransformer(new ToLowerCaseTransformer());
    }
}
