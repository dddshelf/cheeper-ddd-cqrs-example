<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210801205323 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE follows (follow_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', from_author_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', to_author_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', follow_id_id_as_string CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', from_author_id_id_as_string CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', to_author_id_id_as_string CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', PRIMARY KEY(follow_id_id, from_author_id_id, to_author_id_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE follows');
    }
}
