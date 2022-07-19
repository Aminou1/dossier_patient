<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220701151214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dossier (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_3D48E037FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', nom VARCHAR(30) DEFAULT NULL, prenom VARCHAR(30) DEFAULT NULL, telephone VARCHAR(15) DEFAULT NULL, addresse VARCHAR(40) DEFAULT NULL, age VARCHAR(15) DEFAULT NULL, sexe VARCHAR(50) DEFAULT NULL, qualification VARCHAR(100) DEFAULT NULL, specialite_medicale VARCHAR(120) DEFAULT NULL, UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), INDEX IDX_957A6479FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prescription (id INT AUTO_INCREMENT NOT NULL, dossiers_id INT DEFAULT NULL, prescriptiontype_id INT DEFAULT NULL, structures_id INT DEFAULT NULL, libelle_prescrip VARCHAR(255) NOT NULL, INDEX IDX_1FBFB8D9651855E8 (dossiers_id), INDEX IDX_1FBFB8D997638311 (prescriptiontype_id), INDEX IDX_1FBFB8D99D3ED38D (structures_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prestation (id INT AUTO_INCREMENT NOT NULL, prestationtype_id INT DEFAULT NULL, libelle_presta VARCHAR(255) NOT NULL, INDEX IDX_51C88FADA60253ED (prestationtype_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prestation_prescription (prestation_id INT NOT NULL, prescription_id INT NOT NULL, INDEX IDX_4E33043E9E45C554 (prestation_id), INDEX IDX_4E33043E93DB413D (prescription_id), PRIMARY KEY(prestation_id, prescription_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE structure (id INT AUTO_INCREMENT NOT NULL, nom_structure VARCHAR(50) NOT NULL, addresse_structure VARCHAR(255) DEFAULT NULL, contact VARCHAR(20) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_prescription (id INT AUTO_INCREMENT NOT NULL, libelletypeprescription VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_prestation (id INT AUTO_INCREMENT NOT NULL, libelletypepresta VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_utilisateur (id INT AUTO_INCREMENT NOT NULL, libellestype_utilisat VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dossier ADD CONSTRAINT FK_3D48E037FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES type_utilisateur (id)');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D9651855E8 FOREIGN KEY (dossiers_id) REFERENCES dossier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D997638311 FOREIGN KEY (prescriptiontype_id) REFERENCES type_prescription (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D99D3ED38D FOREIGN KEY (structures_id) REFERENCES structure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FADA60253ED FOREIGN KEY (prestationtype_id) REFERENCES type_prestation (id)');
        $this->addSql('ALTER TABLE prestation_prescription ADD CONSTRAINT FK_4E33043E9E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prestation_prescription ADD CONSTRAINT FK_4E33043E93DB413D FOREIGN KEY (prescription_id) REFERENCES prescription (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D9651855E8');
        $this->addSql('ALTER TABLE dossier DROP FOREIGN KEY FK_3D48E037FB88E14F');
        $this->addSql('ALTER TABLE prestation_prescription DROP FOREIGN KEY FK_4E33043E93DB413D');
        $this->addSql('ALTER TABLE prestation_prescription DROP FOREIGN KEY FK_4E33043E9E45C554');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D99D3ED38D');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D997638311');
        $this->addSql('ALTER TABLE prestation DROP FOREIGN KEY FK_51C88FADA60253ED');
        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A6479FB88E14F');
        $this->addSql('DROP TABLE dossier');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE prescription');
        $this->addSql('DROP TABLE prestation');
        $this->addSql('DROP TABLE prestation_prescription');
        $this->addSql('DROP TABLE structure');
        $this->addSql('DROP TABLE type_prescription');
        $this->addSql('DROP TABLE type_prestation');
        $this->addSql('DROP TABLE type_utilisateur');
    }
}
