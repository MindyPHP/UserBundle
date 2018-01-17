<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Mindy\Bundle\UserBundle\Model\User;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170504142113 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $userTable = $schema->createTable(User::tableName());
        $userTable->addColumn('id', 'integer', ['autoincrement' => true, 'unsigned' => true, 'length' => 11]);
        $userTable->addColumn('name', 'string', ['length' => 255]);
        $userTable->addColumn('phone', 'string', ['length' => 255, 'notnull' => false]);
        $userTable->addColumn('email', 'string', ['length' => 255]);
        $userTable->addColumn('password', 'string', ['length' => 255]);
        $userTable->addColumn('salt', 'string', ['length' => 255]);
        $userTable->addColumn('token', 'string', ['length' => 255, 'notnull' => false]);
        $userTable->addColumn('is_active', 'smallint', ['length' => 1, 'default' => 0]);
        $userTable->addColumn('is_superuser', 'smallint', ['length' => 1, 'default' => 0]);
        $userTable->addColumn('phone', 'string', ['length' => 255, 'notnull' => false]);
        $userTable->setPrimaryKey(['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable(User::tableName());
    }
}
