<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221130103303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9F373DCF');
        $this->addSql('DROP INDEX IDX_1483A5E9F373DCF ON users');
        $this->addSql('ALTER TABLE users ADD roles JSON NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE groups_id groupe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E97A45358C FOREIGN KEY (groupe_id) REFERENCES `groups` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('CREATE INDEX IDX_1483A5E97A45358C ON users (groupe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E97A45358C');
        $this->addSql('DROP INDEX UNIQ_1483A5E9E7927C74 ON users');
        $this->addSql('DROP INDEX IDX_1483A5E97A45358C ON users');
        $this->addSql('ALTER TABLE users DROP roles, CHANGE email email VARCHAR(255) NOT NULL, CHANGE groupe_id groups_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9F373DCF FOREIGN KEY (groups_id) REFERENCES `groups` (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E9F373DCF ON users (groups_id)');
    }
}
