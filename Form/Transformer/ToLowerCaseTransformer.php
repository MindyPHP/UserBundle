<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class ToLowerCaseTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        return mb_strtolower((string)$value, 'UTF-8');
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        return mb_strtolower((string)$value, 'UTF-8');
    }
}
