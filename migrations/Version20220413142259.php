<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220413142259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse_facuration ADD utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE adresse_facuration ADD CONSTRAINT FK_F2CEAF61FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_F2CEAF61FB88E14F ON adresse_facuration (utilisateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse_facuration DROP FOREIGN KEY FK_F2CEAF61FB88E14F');
        $this->addSql('DROP INDEX IDX_F2CEAF61FB88E14F ON adresse_facuration');
        $this->addSql('ALTER TABLE adresse_facuration DROP utilisateur_id');
    }
}
