<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Tests;

use Mindy\Bundle\UserBundle\Utils\TokenGenerator;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{
    public function testTokenGenerator()
    {
        $g = new TokenGenerator();
        $this->assertSame(strlen($g->generate()), 32);
    }
}
