<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Tests;

use Mindy\Bundle\UserBundle\Form\Transformer\ToLowerCaseTransformer;
use PHPUnit\Framework\TestCase;

class TransformerTest extends TestCase
{
    public function testTransformer()
    {
        $t = new ToLowerCaseTransformer();
        $this->assertSame('foo', $t->reverseTransform('FOO'));
        $this->assertSame('foo', $t->transform('FOO'));
    }
}
