<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220413131431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande ADD historique_commande_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D5B442032 FOREIGN KEY (historique_commande_id) REFERENCES historique_commande (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67D5B442032 ON commande (historique_commande_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D5B442032');
        $this->addSql('DROP INDEX IDX_6EEAA67D5B442032 ON commande');
        $this->addSql('ALTER TABLE commande DROP historique_commande_id');
    }
}
