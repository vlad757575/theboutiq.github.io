<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220524114901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse_facturation ADD nom_prenom VARCHAR(255) NOT NULL, ADD societe VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE adresse_livraison ADD telephone VARCHAR(255) NOT NULL, ADD nom_prenom VARCHAR(255) NOT NULL, ADD societe VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE adresse_livraison ADD CONSTRAINT FK_B0B09C9FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_B0B09C9FB88E14F ON adresse_livraison (utilisateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse_facturation DROP nom_prenom, DROP societe');
        $this->addSql('ALTER TABLE adresse_livraison DROP FOREIGN KEY FK_B0B09C9FB88E14F');
        $this->addSql('DROP INDEX IDX_B0B09C9FB88E14F ON adresse_livraison');
        $this->addSql('ALTER TABLE adresse_livraison DROP telephone, DROP nom_prenom, DROP societe');
    }
}
