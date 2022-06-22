<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220620130937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, pop VARCHAR(255) NOT NULL, hip_hop VARCHAR(255) NOT NULL, rock VARCHAR(255) NOT NULL, electro VARCHAR(255) NOT NULL, reggae VARCHAR(255) NOT NULL, chill VARCHAR(255) NOT NULL, vacances VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_playlist (category_id INT NOT NULL, playlist_id INT NOT NULL, INDEX IDX_60F8955612469DE2 (category_id), INDEX IDX_60F895566BBD148 (playlist_id), PRIMARY KEY(category_id, playlist_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_playlist ADD CONSTRAINT FK_60F8955612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_playlist ADD CONSTRAINT FK_60F895566BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_playlist DROP FOREIGN KEY FK_60F8955612469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_playlist');
    }
}
