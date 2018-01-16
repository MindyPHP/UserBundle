<?php
/**
 * Created by IntelliJ IDEA.
 * User: max
 * Date: 16/01/2018
 * Time: 21:30
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
