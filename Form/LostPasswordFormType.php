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
use Mindy\Bundle\UserBundle\Model\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class LostPasswordFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Электронная почта',
                'constraints' => [
                    new Assert\Callback(function ($value, ExecutionContextInterface $context, $payload) {
                        if (0 == User::objects()->filter(['email' => $value])->count()) {
                            $context->buildViolation('Пользователь с таким адресом электронной почты не существует')
                                ->addViolation();
                        }
                    }),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Отправить код',
            ]);

        $builder
            ->get('email')
            ->addModelTransformer(new ToLowerCaseTransformer());
    }
}
