<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220620091615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist DROP FOREIGN KEY FK_D782112D9D86650F');
        $this->addSql('DROP INDEX IDX_D782112D9D86650F ON playlist');
        $this->addSql('ALTER TABLE playlist CHANGE user_id_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE playlist ADD CONSTRAINT FK_D782112DF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D782112DF675F31B ON playlist (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist DROP FOREIGN KEY FK_D782112DF675F31B');
        $this->addSql('DROP INDEX IDX_D782112DF675F31B ON playlist');
        $this->addSql('ALTER TABLE playlist CHANGE author_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE playlist ADD CONSTRAINT FK_D782112D9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D782112D9D86650F ON playlist (user_id_id)');
    }
}
