<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241103000930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE guest DROP FOREIGN KEY FK_ACB79A35ECFF285C');
        $this->addSql('ALTER TABLE guest_list DROP FOREIGN KEY FK_6072A545ECFF285C');
        $this->addSql('CREATE TABLE tables (id INT AUTO_INCREMENT NOT NULL, num INT NOT NULL, description VARCHAR(255) DEFAULT NULL, max_guests INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE `table`');
        $this->addSql('DROP INDEX IDX_ACB79A35ECFF285C ON guest');
        $this->addSql('ALTER TABLE guest DROP table_id, DROP created_at');
        $this->addSql('ALTER TABLE guest_list DROP FOREIGN KEY FK_6072A5459A4AA658');
        $this->addSql('ALTER TABLE guest_list DROP FOREIGN KEY FK_6072A545ECFF285C');
        $this->addSql('ALTER TABLE guest_list ADD CONSTRAINT FK_6072A5459A4AA658 FOREIGN KEY (guest_id) REFERENCES guest (id)');
        $this->addSql('ALTER TABLE guest_list ADD CONSTRAINT FK_6072A545ECFF285C FOREIGN KEY (table_id) REFERENCES tables (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE guest_list DROP FOREIGN KEY FK_6072A545ECFF285C');
        $this->addSql('CREATE TABLE `table` (id INT AUTO_INCREMENT NOT NULL, num INT NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, max_guests INT DEFAULT NULL, guests_def INT DEFAULT NULL, guests_now INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE tables');
        $this->addSql('ALTER TABLE guest ADD table_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE guest ADD CONSTRAINT FK_ACB79A35ECFF285C FOREIGN KEY (table_id) REFERENCES `table` (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_ACB79A35ECFF285C ON guest (table_id)');
        $this->addSql('ALTER TABLE guest_list DROP FOREIGN KEY FK_6072A5459A4AA658');
        $this->addSql('ALTER TABLE guest_list DROP FOREIGN KEY FK_6072A545ECFF285C');
        $this->addSql('ALTER TABLE guest_list ADD CONSTRAINT FK_6072A5459A4AA658 FOREIGN KEY (guest_id) REFERENCES guest (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE guest_list ADD CONSTRAINT FK_6072A545ECFF285C FOREIGN KEY (table_id) REFERENCES `table` (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
