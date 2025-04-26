<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250426130633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE micro_post_user (micro_post_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_19DCF74D11E37CEA (micro_post_id), INDEX IDX_19DCF74DA76ED395 (user_id), PRIMARY KEY(micro_post_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE micro_post_user ADD CONSTRAINT FK_19DCF74D11E37CEA FOREIGN KEY (micro_post_id) REFERENCES micro_post (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE micro_post_user ADD CONSTRAINT FK_19DCF74DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE micro_post_user DROP FOREIGN KEY FK_19DCF74D11E37CEA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE micro_post_user DROP FOREIGN KEY FK_19DCF74DA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE micro_post_user
        SQL);
    }
}
