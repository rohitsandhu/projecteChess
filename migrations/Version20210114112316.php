<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210114112316 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE info_jugador_per_ronda (id INT AUTO_INCREMENT NOT NULL, jugador_id INT DEFAULT NULL, ronda_id INT DEFAULT NULL, elo INT NOT NULL, posicio INT NOT NULL, INDEX IDX_346B23B8B8A54D43 (jugador_id), INDEX IDX_346B23B8B27F466B (ronda_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partida (id INT AUTO_INCREMENT NOT NULL, peces_blanques_id INT DEFAULT NULL, peces_negres_id INT DEFAULT NULL, ronda_id INT DEFAULT NULL, punts_blanques DOUBLE PRECISION NOT NULL, punts_negres DOUBLE PRECISION NOT NULL, numero_taula INT NOT NULL, bye TINYINT(1) NOT NULL, INDEX IDX_A9C1580C111D345F (peces_blanques_id), INDEX IDX_A9C1580C40EFBD95 (peces_negres_id), INDEX IDX_A9C1580CB27F466B (ronda_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ronda (id INT AUTO_INCREMENT NOT NULL, numero_de_ronda INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE info_jugador_per_ronda ADD CONSTRAINT FK_346B23B8B8A54D43 FOREIGN KEY (jugador_id) REFERENCES jugador (id)');
        $this->addSql('ALTER TABLE info_jugador_per_ronda ADD CONSTRAINT FK_346B23B8B27F466B FOREIGN KEY (ronda_id) REFERENCES ronda (id)');
        $this->addSql('ALTER TABLE partida ADD CONSTRAINT FK_A9C1580C111D345F FOREIGN KEY (peces_blanques_id) REFERENCES jugador (id)');
        $this->addSql('ALTER TABLE partida ADD CONSTRAINT FK_A9C1580C40EFBD95 FOREIGN KEY (peces_negres_id) REFERENCES jugador (id)');
        $this->addSql('ALTER TABLE partida ADD CONSTRAINT FK_A9C1580CB27F466B FOREIGN KEY (ronda_id) REFERENCES ronda (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE info_jugador_per_ronda DROP FOREIGN KEY FK_346B23B8B27F466B');
        $this->addSql('ALTER TABLE partida DROP FOREIGN KEY FK_A9C1580CB27F466B');
        $this->addSql('DROP TABLE info_jugador_per_ronda');
        $this->addSql('DROP TABLE partida');
        $this->addSql('DROP TABLE ronda');
    }
}
