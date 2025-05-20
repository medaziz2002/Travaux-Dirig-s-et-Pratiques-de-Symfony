<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250206075153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournoi DROP FOREIGN KEY FK_18AFD9DF362F47CF');
        $this->addSql('ALTER TABLE tournoi ADD CONSTRAINT FK_18AFD9DF362F47CF FOREIGN KEY (ev_id_id) REFERENCES evenement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournoi DROP FOREIGN KEY FK_18AFD9DF362F47CF');
        $this->addSql('ALTER TABLE tournoi ADD CONSTRAINT FK_18AFD9DF362F47CF FOREIGN KEY (ev_id_id) REFERENCES evenement (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
