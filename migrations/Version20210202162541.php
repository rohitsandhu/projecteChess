<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210202162541 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE byes_jugador_torneig (id INT AUTO_INCREMENT NOT NULL, id_torneig_id INT DEFAULT NULL, id_jugador_id INT DEFAULT NULL, byes INT NOT NULL, INDEX IDX_B79D4CDDFC6E7A2E (id_torneig_id), INDEX IDX_B79D4CDD1D2FCD94 (id_jugador_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE byes_jugador_torneig ADD CONSTRAINT FK_B79D4CDDFC6E7A2E FOREIGN KEY (id_torneig_id) REFERENCES torneig (id)');
        $this->addSql('ALTER TABLE byes_jugador_torneig ADD CONSTRAINT FK_B79D4CDD1D2FCD94 FOREIGN KEY (id_jugador_id) REFERENCES jugador (id)');
        $this->addSql('ALTER TABLE partida ADD resultat VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE byes_jugador_torneig');
        $this->addSql('ALTER TABLE partida DROP resultat');
    }
}
