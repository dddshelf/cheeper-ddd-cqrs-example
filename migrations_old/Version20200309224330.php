<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200309224330 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE follow_relationships (id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', followee_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', followed_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', id_id_as_string CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', followee_id_as_string CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', followed_id_as_string CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', PRIMARY KEY(id_id, followee_id, followed_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE follow_relationships');
    }
}
