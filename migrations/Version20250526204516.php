<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250526204516 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE followers (user_source INT NOT NULL, user_target INT NOT NULL, INDEX IDX_8408FDA73AD8644E (user_source), INDEX IDX_8408FDA7233D34C1 (user_target), PRIMARY KEY(user_source, user_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE followers ADD CONSTRAINT FK_8408FDA73AD8644E FOREIGN KEY (user_source) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE followers ADD CONSTRAINT FK_8408FDA7233D34C1 FOREIGN KEY (user_target) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_profile CHANGE date_of_birth date_of_birth DATETIME DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE followers DROP FOREIGN KEY FK_8408FDA73AD8644E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE followers DROP FOREIGN KEY FK_8408FDA7233D34C1
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE followers
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_profile CHANGE date_of_birth date_of_birth VARCHAR(255) DEFAULT NULL
        SQL);
    }
}
