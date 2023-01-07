<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221222175034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE regime (id INT AUTO_INCREMENT NOT NULL, nom_regime VARCHAR(255) NOT NULL, duree INT NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_favorite TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regime_plat (regime_id INT NOT NULL, plat_id INT NOT NULL, INDEX IDX_4C654DA35E7D534 (regime_id), INDEX IDX_4C654DAD73DB560 (plat_id), PRIMARY KEY(regime_id, plat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE regime_plat ADD CONSTRAINT FK_4C654DA35E7D534 FOREIGN KEY (regime_id) REFERENCES regime (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE regime_plat ADD CONSTRAINT FK_4C654DAD73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE regime_plat DROP FOREIGN KEY FK_4C654DA35E7D534');
        $this->addSql('ALTER TABLE regime_plat DROP FOREIGN KEY FK_4C654DAD73DB560');
        $this->addSql('DROP TABLE regime');
        $this->addSql('DROP TABLE regime_plat');
    }
}
