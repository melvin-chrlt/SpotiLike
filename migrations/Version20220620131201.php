<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220620131201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD label VARCHAR(255) NOT NULL, DROP pop, DROP hip_hop, DROP rock, DROP electro, DROP reggae, DROP chill, DROP vacances');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD hip_hop VARCHAR(255) NOT NULL, ADD rock VARCHAR(255) NOT NULL, ADD electro VARCHAR(255) NOT NULL, ADD reggae VARCHAR(255) NOT NULL, ADD chill VARCHAR(255) NOT NULL, ADD vacances VARCHAR(255) NOT NULL, CHANGE label pop VARCHAR(255) NOT NULL');
    }
}
