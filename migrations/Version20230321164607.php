<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230321164607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE description');
        $this->addSql('DROP TABLE start_date');
        $this->addSql('ALTER TABLE tbl_formation DROP FOREIGN KEY FK_5C84E2C47E01813');
        $this->addSql('DROP INDEX IDX_5C84E2C47E01813 ON tbl_formation');
        $this->addSql('ALTER TABLE tbl_formation ADD start_date_time DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD end_date_time DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE create_byt_id create_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tbl_formation ADD CONSTRAINT FK_5C84E2C9E085865 FOREIGN KEY (create_by_id) REFERENCES tbl_user (id)');
        $this->addSql('CREATE INDEX IDX_5C84E2C9E085865 ON tbl_formation (create_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE description (id INT AUTO_INCREMENT NOT NULL, text LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE start_date (id INT AUTO_INCREMENT NOT NULL, date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tbl_formation DROP FOREIGN KEY FK_5C84E2C9E085865');
        $this->addSql('DROP INDEX IDX_5C84E2C9E085865 ON tbl_formation');
        $this->addSql('ALTER TABLE tbl_formation DROP start_date_time, DROP end_date_time, CHANGE create_by_id create_byt_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tbl_formation ADD CONSTRAINT FK_5C84E2C47E01813 FOREIGN KEY (create_byt_id) REFERENCES tbl_user (id)');
        $this->addSql('CREATE INDEX IDX_5C84E2C47E01813 ON tbl_formation (create_byt_id)');
    }
}
