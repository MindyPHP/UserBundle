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
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Statement;
use Mindy\Bundle\UserBundle\Form\Admin\UserCreateForm;
use Mindy\Bundle\UserBundle\Form\Admin\UserPasswordForm;
use Mindy\Bundle\UserBundle\Form\Admin\UserUpdateForm;
use Mindy\Bundle\UserBundle\Form\ChangePasswordFormType;
use Mindy\Bundle\UserBundle\Form\LostPasswordFormType;
use Mindy\Bundle\UserBundle\Form\RegistrationFormType;
use Mindy\Bundle\UserBundle\Form\SetPasswordFormType;
use Mindy\Bundle\UserBundle\Model\User;
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
        $platform = $this
            ->getMockBuilder(AbstractPlatform::class)
            ->getMock();
        $statement = $this
            ->getMockBuilder(Statement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $statement->method('fetch')->willReturn([]);
        $connection->method('query')->willReturn($statement);
        $connection->method('getDatabasePlatform')->willReturn($platform);
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
            'password' => [
                'first' => 'foobar',
            ],
        ];

        $form = $this->factory->create(SetPasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertFalse($form->isValid());
        $this->assertCount(1, $form->getErrors(true, false));

        $view = $form->createView();
        $children = $view->children;

        foreach (['password', 'submit'] as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function testChangePasswordFormType()
    {
        $formData = [
            'password' => [
                'first' => 'foobar',
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertFalse($form->isValid());
        $this->assertCount(1, $form->getErrors(true, false));

        $view = $form->createView();
        $children = $view->children;

        foreach (['password', 'submit'] as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function testRegistrationFormType()
    {
        $formData = [
            'email' => 'foobar',
        ];

        $form = $this->factory->create(RegistrationFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertFalse($form->isValid());
        $this->assertCount(2, $form->getErrors(true, false));

        $view = $form->createView();
        $children = $view->children;

        foreach (['email', 'password', 'submit'] as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function testAdminUserCreateForm()
    {
        $formData = [
            'email' => 'foobar',
        ];

        $instance = $this
            ->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        $instance->id = 1;
        $instance->method('getIsNewRecord')->willReturn(false);

        $form = $this->factory->create(UserCreateForm::class, $instance);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertFalse($form->isValid());
        $this->assertCount(2, $form->getErrors(true, false));

        $view = $form->createView();
        $children = $view->children;

        foreach (['is_superuser', 'email', 'password', 'submit'] as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function testAdminUserUpdateForm()
    {
        $formData = [
            'email' => 'foobar',
        ];

        $form = $this->factory->create(UserUpdateForm::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertFalse($form->isValid());
        $this->assertCount(1, $form->getErrors(true, false));

        $view = $form->createView();
        $children = $view->children;

        $this->assertArrayNotHasKey('password', $children);
        foreach (['email', 'submit'] as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function testAdminUserPasswordForm()
    {
        $formData = [
            'password' => 'foobar',
        ];

        $form = $this->factory->create(UserPasswordForm::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isSubmitted());
        $this->assertFalse($form->isValid());
        $this->assertCount(1, $form->getErrors(true, false));

        $view = $form->createView();
        $children = $view->children;

        foreach (['password', 'submit'] as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
