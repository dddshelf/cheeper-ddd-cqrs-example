<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200309230710 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tweets (user_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', tweet_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', user_id_id_as_string CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', tweet_id_id_as_string CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', tweet_message_message VARCHAR(260) NOT NULL, PRIMARY KEY(user_id_id, tweet_id_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tweets');
    }
}
