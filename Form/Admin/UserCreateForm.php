<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Form\Admin;

use Mindy\Bundle\UserBundle\Form\Transformer\ToLowerCaseTransformer;
use Mindy\Bundle\UserBundle\Form\Type\RepeatedPasswordType;
use Mindy\Bundle\UserBundle\Model\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserCreateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $instance = $builder->getData();

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Электронная почта',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new Assert\Callback(function ($value, ExecutionContextInterface $context) use ($instance) {
                        $qs = User::objects()->filter(['email' => $value]);

                        if ($instance && false == $instance->getIsNewRecord()) {
                            $qs->exclude(['id' => $instance->id]);
                        }

                        if ($qs->count() > 0) {
                            $context
                                ->buildViolation('Пользователь с данным электронным адресом уже есть в базе')
                                ->addViolation();
                        }
                    }),
                ],
            ])
            ->add('is_active', CheckboxType::class, [
                'label' => 'Аккаунт активирован',
                'required' => false,
            ])
            ->add('is_superuser', CheckboxType::class, [
                'label' => 'Администратор',
                'required' => false,
            ])
            ->add('password', RepeatedPasswordType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Сохранить',
            ]);

        $builder
            ->get('email')
            ->addModelTransformer(new ToLowerCaseTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
