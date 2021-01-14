<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210114113941 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE torneig (id INT AUTO_INCREMENT NOT NULL, arbitre_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, numero_byes INT NOT NULL, pais VARCHAR(255) NOT NULL, num_rondes INT NOT NULL, INDEX IDX_DF78888943A5F0 (arbitre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE torneig_jugador (torneig_id INT NOT NULL, jugador_id INT NOT NULL, INDEX IDX_ED15598F59E4FAF9 (torneig_id), INDEX IDX_ED15598FB8A54D43 (jugador_id), PRIMARY KEY(torneig_id, jugador_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE torneig ADD CONSTRAINT FK_DF78888943A5F0 FOREIGN KEY (arbitre_id) REFERENCES arbitre (id)');
        $this->addSql('ALTER TABLE torneig_jugador ADD CONSTRAINT FK_ED15598F59E4FAF9 FOREIGN KEY (torneig_id) REFERENCES torneig (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE torneig_jugador ADD CONSTRAINT FK_ED15598FB8A54D43 FOREIGN KEY (jugador_id) REFERENCES jugador (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE info_jugador_per_ronda');
        $this->addSql('ALTER TABLE ronda ADD torneig_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ronda ADD CONSTRAINT FK_5F18BAA059E4FAF9 FOREIGN KEY (torneig_id) REFERENCES torneig (id)');
        $this->addSql('CREATE INDEX IDX_5F18BAA059E4FAF9 ON ronda (torneig_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ronda DROP FOREIGN KEY FK_5F18BAA059E4FAF9');
        $this->addSql('ALTER TABLE torneig_jugador DROP FOREIGN KEY FK_ED15598F59E4FAF9');
        $this->addSql('CREATE TABLE info_jugador_per_ronda (id INT AUTO_INCREMENT NOT NULL, jugador_id INT DEFAULT NULL, ronda_id INT DEFAULT NULL, elo INT NOT NULL, posicio INT NOT NULL, INDEX IDX_346B23B8B8A54D43 (jugador_id), INDEX IDX_346B23B8B27F466B (ronda_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE info_jugador_per_ronda ADD CONSTRAINT FK_346B23B8B27F466B FOREIGN KEY (ronda_id) REFERENCES ronda (id)');
        $this->addSql('ALTER TABLE info_jugador_per_ronda ADD CONSTRAINT FK_346B23B8B8A54D43 FOREIGN KEY (jugador_id) REFERENCES jugador (id)');
        $this->addSql('DROP TABLE torneig');
        $this->addSql('DROP TABLE torneig_jugador');
        $this->addSql('DROP INDEX IDX_5F18BAA059E4FAF9 ON ronda');
        $this->addSql('ALTER TABLE ronda DROP torneig_id');
    }
}
