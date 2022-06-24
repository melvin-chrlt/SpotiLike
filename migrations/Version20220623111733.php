<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220623111733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist_like ADD playlist_like_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE playlist_like ADD CONSTRAINT FK_C7A77D13AA4ED28 FOREIGN KEY (playlist_like_id) REFERENCES playlist (id)');
        $this->addSql('CREATE INDEX IDX_C7A77D13AA4ED28 ON playlist_like (playlist_like_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist_like DROP FOREIGN KEY FK_C7A77D13AA4ED28');
        $this->addSql('DROP INDEX IDX_C7A77D13AA4ED28 ON playlist_like');
        $this->addSql('ALTER TABLE playlist_like DROP playlist_like_id');
    }
}
