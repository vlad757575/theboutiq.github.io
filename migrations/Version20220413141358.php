<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220413141358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse_facuration (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, numero_rue INT NOT NULL, rue VARCHAR(255) NOT NULL, codepostal INT NOT NULL, ville VARCHAR(50) NOT NULL, pays VARCHAR(50) NOT NULL, infocomplementaire LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adresse_livraison (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, numero_rue INT NOT NULL, rue VARCHAR(255) NOT NULL, codepostal INT NOT NULL, ville VARCHAR(50) NOT NULL, pays VARCHAR(50) NOT NULL, infocomplementaire LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE adresse_facuration');
        $this->addSql('DROP TABLE adresse_livraison');
    }
}
