<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Ramsey\Uuid\Doctrine\UuidBinaryType;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200526065527 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Creates a join table to store users who are followed by a user';
    }

    public function up(Schema $schema) : void
    {
        $table = $schema->createTable('user_followers');
        $table->addColumn('user_id', UuidBinaryType::NAME, ['notnull' => true]);
        $table->addColumn('followed_id', UuidBinaryType::NAME, ['notnull' => true]);
        $table->setPrimaryKey(['user_id', 'followed_id']);
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('user_followers');
    }
}
