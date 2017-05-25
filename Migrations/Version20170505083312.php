<?php

namespace Mindy\Bundle\UserBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Mindy\Bundle\UserBundle\Model\User;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170505083312 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $userTable = $schema->getTable(User::tableName());
        $userTable->addColumn('phone', 'string', ['length' => 255]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $userTable = $schema->getTable(User::tableName());
        $userTable->dropColumn('phone');
    }
}
