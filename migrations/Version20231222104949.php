<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231222104949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account_type (id INT AUTO_INCREMENT NOT NULL, act_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bank_account (id INT AUTO_INCREMENT NOT NULL, fk_usr_id_id INT DEFAULT NULL, fk_act_id_id INT DEFAULT NULL, fk_frc_id_id INT DEFAULT NULL, bnk_balance DOUBLE PRECISION NOT NULL, bnk_debit TINYINT(1) NOT NULL, INDEX IDX_53A23E0A20F2C1BD (fk_usr_id_id), INDEX IDX_53A23E0ACEB5D6D1 (fk_act_id_id), UNIQUE INDEX UNIQ_53A23E0AB8BC28ED (fk_frc_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, cat_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forecast (id INT AUTO_INCREMENT NOT NULL, frc_amounts JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, fk_trt_id_id INT DEFAULT NULL, fk_cat_id_id INT DEFAULT NULL, fk_bnk_id_id INT DEFAULT NULL, trs_date DATETIME NOT NULL, trs_amount DOUBLE PRECISION NOT NULL, trs_debit TINYINT(1) NOT NULL, INDEX IDX_723705D198F3E05A (fk_trt_id_id), INDEX IDX_723705D1A266F92A (fk_cat_id_id), INDEX IDX_723705D1FDAB13E2 (fk_bnk_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction_type (id INT AUTO_INCREMENT NOT NULL, trt_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0A20F2C1BD FOREIGN KEY (fk_usr_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0ACEB5D6D1 FOREIGN KEY (fk_act_id_id) REFERENCES account_type (id)');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0AB8BC28ED FOREIGN KEY (fk_frc_id_id) REFERENCES forecast (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D198F3E05A FOREIGN KEY (fk_trt_id_id) REFERENCES transaction_type (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A266F92A FOREIGN KEY (fk_cat_id_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1FDAB13E2 FOREIGN KEY (fk_bnk_id_id) REFERENCES bank_account (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0A20F2C1BD');
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0ACEB5D6D1');
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0AB8BC28ED');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D198F3E05A');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1A266F92A');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1FDAB13E2');
        $this->addSql('DROP TABLE account_type');
        $this->addSql('DROP TABLE bank_account');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE forecast');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE transaction_type');
        $this->addSql('DROP TABLE `user`');
    }
}
