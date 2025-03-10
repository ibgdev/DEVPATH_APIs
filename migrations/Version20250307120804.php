<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250307120804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE progression (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, cours_id_id INT NOT NULL, statut VARCHAR(255) NOT NULL, progression_pourc INT NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D5B250739D86650F (user_id_id), INDEX IDX_D5B250734F221781 (cours_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roadmap_cours (id INT AUTO_INCREMENT NOT NULL, roadmap_id_id INT NOT NULL, cours_id_id INT NOT NULL, ord INT NOT NULL, INDEX IDX_5D794698DFF75453 (roadmap_id_id), INDEX IDX_5D7946984F221781 (cours_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roadmaps (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, progression_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1483A5E9AF229C18 (progression_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE videos (id INT AUTO_INCREMENT NOT NULL, cours_id_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, url_video VARCHAR(255) NOT NULL, ordre INT NOT NULL, INDEX IDX_29AA64324F221781 (cours_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE progression ADD CONSTRAINT FK_D5B250739D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE progression ADD CONSTRAINT FK_D5B250734F221781 FOREIGN KEY (cours_id_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE roadmap_cours ADD CONSTRAINT FK_5D794698DFF75453 FOREIGN KEY (roadmap_id_id) REFERENCES roadmaps (id)');
        $this->addSql('ALTER TABLE roadmap_cours ADD CONSTRAINT FK_5D7946984F221781 FOREIGN KEY (cours_id_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9AF229C18 FOREIGN KEY (progression_id) REFERENCES progression (id)');
        $this->addSql('ALTER TABLE videos ADD CONSTRAINT FK_29AA64324F221781 FOREIGN KEY (cours_id_id) REFERENCES cours (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE progression DROP FOREIGN KEY FK_D5B250739D86650F');
        $this->addSql('ALTER TABLE progression DROP FOREIGN KEY FK_D5B250734F221781');
        $this->addSql('ALTER TABLE roadmap_cours DROP FOREIGN KEY FK_5D794698DFF75453');
        $this->addSql('ALTER TABLE roadmap_cours DROP FOREIGN KEY FK_5D7946984F221781');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9AF229C18');
        $this->addSql('ALTER TABLE videos DROP FOREIGN KEY FK_29AA64324F221781');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE progression');
        $this->addSql('DROP TABLE roadmap_cours');
        $this->addSql('DROP TABLE roadmaps');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE videos');
    }
}
