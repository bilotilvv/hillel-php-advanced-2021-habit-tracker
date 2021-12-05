<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211205093644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added tables "habit" and "track_point"';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            CREATE TABLE habit (
                habit_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                user_id INT UNSIGNED NOT NULL,
                name VARCHAR(30) NOT NULL,
                point_icon VARCHAR(20) NOT NULL,
                point_color VARCHAR(20) NOT NULL,
                PRIMARY KEY(habit_id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('
            CREATE TABLE track_point (
                track_point_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                habit_id INT UNSIGNED NOT NULL,
                occurred_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\',
                INDEX IDX_C7525183E7AEB3B2 (habit_id),
                PRIMARY KEY(track_point_id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE track_point
            ADD CONSTRAINT FK_C7525183E7AEB3B2 FOREIGN KEY (habit_id) REFERENCES habit (habit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE track_point DROP FOREIGN KEY FK_C7525183E7AEB3B2');
        $this->addSql('DROP TABLE habit');
        $this->addSql('DROP TABLE track_point');
    }
}
