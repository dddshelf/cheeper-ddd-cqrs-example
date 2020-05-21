<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200315170724 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cheeps (cheep_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', author_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', cheep_id_id_as_string CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', author_id_id_as_string CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', cheep_message_message VARCHAR(260) NOT NULL, cheep_date_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(cheep_id_id, author_id_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE authors (author_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', name VARCHAR(100) NOT NULL, biography LONGTEXT NOT NULL, location VARCHAR(100) NOT NULL, author_id_id_as_string CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_name_user_name VARCHAR(100) NOT NULL, website_uri VARCHAR(255) NOT NULL, birth_date_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(author_id_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE tweets');
        $this->addSql('DROP TABLE users');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tweets (user_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', tweet_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', user_id_id_as_string CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\', tweet_id_id_as_string CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\', tweet_message_message VARCHAR(260) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, tweet_date_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(user_id_id, tweet_id_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users (user_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, biography LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, location VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, user_id_id_as_string CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\', user_name_user_name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, website_uri VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, birth_date_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(user_id_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE cheeps');
        $this->addSql('DROP TABLE authors');
    }
}
