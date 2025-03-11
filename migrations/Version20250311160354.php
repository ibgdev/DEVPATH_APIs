<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250311160354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE progression (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, cours_id INT DEFAULT NULL, statut VARCHAR(255) NOT NULL, prog_pourcentage INT NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D5B25073FB88E14F (utilisateur_id), INDEX IDX_D5B250737ECF78B0 (cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roadmap_cours (id INT AUTO_INCREMENT NOT NULL, roadmap_id INT DEFAULT NULL, cours_id INT DEFAULT NULL, ord INT NOT NULL, INDEX IDX_5D794698135345B2 (roadmap_id), INDEX IDX_5D7946987ECF78B0 (cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roadmaps (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE videos (id INT AUTO_INCREMENT NOT NULL, cours_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, ord INT NOT NULL, INDEX IDX_29AA64327ECF78B0 (cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE progression ADD CONSTRAINT FK_D5B25073FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE progression ADD CONSTRAINT FK_D5B250737ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE roadmap_cours ADD CONSTRAINT FK_5D794698135345B2 FOREIGN KEY (roadmap_id) REFERENCES roadmaps (id)');
        $this->addSql('ALTER TABLE roadmap_cours ADD CONSTRAINT FK_5D7946987ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE videos ADD CONSTRAINT FK_29AA64327ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE progression DROP FOREIGN KEY FK_D5B25073FB88E14F');
        $this->addSql('ALTER TABLE progression DROP FOREIGN KEY FK_D5B250737ECF78B0');
        $this->addSql('ALTER TABLE roadmap_cours DROP FOREIGN KEY FK_5D794698135345B2');
        $this->addSql('ALTER TABLE roadmap_cours DROP FOREIGN KEY FK_5D7946987ECF78B0');
        $this->addSql('ALTER TABLE videos DROP FOREIGN KEY FK_29AA64327ECF78B0');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE progression');
        $this->addSql('DROP TABLE roadmap_cours');
        $this->addSql('DROP TABLE roadmaps');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE videos');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
