<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220413123512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F0816FB88E14F');
        $this->addSql('DROP INDEX IDX_C35F0816FB88E14F ON adresse');
        $this->addSql('ALTER TABLE adresse DROP utilisateur_id');
        $this->addSql('ALTER TABLE commande ADD etat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DD5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DD5E86FF ON commande (etat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse ADD utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F0816FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_C35F0816FB88E14F ON adresse (utilisateur_id)');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DD5E86FF');
        $this->addSql('DROP INDEX IDX_6EEAA67DD5E86FF ON commande');
        $this->addSql('ALTER TABLE commande DROP etat_id');
    }
}
