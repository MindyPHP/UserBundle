<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Statement;
use Mindy\Bundle\UserBundle\Form\LostPasswordFormType;
use Mindy\Bundle\UserBundle\Form\SetPasswordFormType;
use Mindy\Orm\Orm;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class FormTest extends TypeTestCase
{
    protected function setUp()
    {
        parent::setUp();

        Orm::setDefaultConnection($this->getMockedConnection());
    }

    protected function getExtensions()
    {
        $validator = Validation::createValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|Connection
     */
    protected function getMockedConnection()
    {
        $connection = $this
            ->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $statement = $this
            ->getMockBuilder(Statement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $statement->method('fetch')->willReturn([]);
        $connection->method('query')->willReturn($statement);
        $driver = $this
            ->getMockBuilder(Driver::class)
            ->getMock();
        $driver->method('getName')->willReturn('pdo_sqlite');
        $connection->method('getDriver')->willReturn($driver);

        return $connection;
    }

    public function testLostPasswordFormType()
    {
        $formData = [
            'email' => 'foobar',
        ];

        $form = $this->factory->create(LostPasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertFalse($form->isValid());
        $this->assertSame([
            'email' => 'foobar',
        ], $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (['email', 'submit'] as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function testSetPasswordFormType()
    {
        $formData = [
            'password' => 'foobar',
            'password_repeat' => 'foobar',
        ];

        $form = $this->factory->create(SetPasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertFalse($form->isValid());
        $this->assertSame([
            'password' => 'foobar',
        ], $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (['email', 'submit'] as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
