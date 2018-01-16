<?php
/**
 * Created by IntelliJ IDEA.
 * User: max
 * Date: 16/01/2018
 * Time: 22:05
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
