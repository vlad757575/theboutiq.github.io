<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220413072328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commande_etat');
        $this->addSql('DROP TABLE panier_produit');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_etat (commande_id INT NOT NULL, etat_id INT NOT NULL, INDEX IDX_3EA6F681D5E86FF (etat_id), INDEX IDX_3EA6F68182EA2E54 (commande_id), PRIMARY KEY(commande_id, etat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE panier_produit (panier_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_D31F28A6F347EFB (produit_id), INDEX IDX_D31F28A6F77D927C (panier_id), PRIMARY KEY(panier_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commande_etat ADD CONSTRAINT FK_3EA6F681D5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_etat ADD CONSTRAINT FK_3EA6F68182EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A6F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A6F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
    }
}
