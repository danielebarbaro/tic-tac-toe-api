<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240718065543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id UUID NOT NULL, status VARCHAR(255) NOT NULL, level VARCHAR(255) NOT NULL, board TEXT NOT NULL, winner BOOLEAN DEFAULT NULL, players SMALLINT NOT NULL, game_completed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN game.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game.board IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN game.game_completed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN game.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE move (id UUID NOT NULL, game_id UUID NOT NULL, position SMALLINT NOT NULL, player SMALLINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EF3E3778E48FD905 ON move (game_id)');
        $this->addSql('COMMENT ON COLUMN move.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN move.game_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN move.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE move ADD CONSTRAINT FK_EF3E3778E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE move DROP CONSTRAINT FK_EF3E3778E48FD905');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE move');
    }
}
