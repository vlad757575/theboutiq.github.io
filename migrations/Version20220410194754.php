<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220410194754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE historique_commande_commande (historique_commande_id INT NOT NULL, commande_id INT NOT NULL, INDEX IDX_F6BB8C485B442032 (historique_commande_id), INDEX IDX_F6BB8C4882EA2E54 (commande_id), PRIMARY KEY(historique_commande_id, commande_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE historique_commande_commande ADD CONSTRAINT FK_F6BB8C485B442032 FOREIGN KEY (historique_commande_id) REFERENCES historique_commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE historique_commande_commande ADD CONSTRAINT FK_F6BB8C4882EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur ADD historique_commande_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B35B442032 FOREIGN KEY (historique_commande_id) REFERENCES historique_commande (id)');
        $this->addSql('CREATE INDEX IDX_1D1C63B35B442032 ON utilisateur (historique_commande_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE historique_commande_commande');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B35B442032');
        $this->addSql('DROP INDEX IDX_1D1C63B35B442032 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP historique_commande_id');
    }
}
