<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Utils;

class TokenGenerator
{
    /**
     * Simple random token generator
     *
     * @return string
     */
    public function generate(): string
    {
        $uniqueId = uniqid((string)rand(), true);
        return (string) md5($uniqueId);
    }
}
