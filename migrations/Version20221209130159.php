<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221209130159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, creation_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', watched TINYINT(1) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification_user (notification_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_35AF9D73EF1A9D84 (notification_id), INDEX IDX_35AF9D73A76ED395 (user_id), PRIMARY KEY(notification_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification_user ADD CONSTRAINT FK_35AF9D73EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_user ADD CONSTRAINT FK_35AF9D73A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE new_user_notification_user DROP FOREIGN KEY FK_5A7909B4A07815E');
        $this->addSql('ALTER TABLE new_user_notification_user DROP FOREIGN KEY FK_5A7909B4A76ED395');
        $this->addSql('ALTER TABLE user_blocked_notification_user DROP FOREIGN KEY FK_3239F78B95B7FCDB');
        $this->addSql('ALTER TABLE user_blocked_notification_user DROP FOREIGN KEY FK_3239F78BA76ED395');
        $this->addSql('DROP TABLE new_user_notification_user');
        $this->addSql('DROP TABLE user_blocked_notification_user');
        $this->addSql('ALTER TABLE new_user_notification ADD notification_id INT NOT NULL, DROP creation_date, DROP is_viewed');
        $this->addSql('ALTER TABLE new_user_notification ADD CONSTRAINT FK_53BAD808EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_53BAD808EF1A9D84 ON new_user_notification (notification_id)');
        $this->addSql('ALTER TABLE user_blocked_notification ADD notification_id INT NOT NULL, DROP creation_date, DROP is_viewed');
        $this->addSql('ALTER TABLE user_blocked_notification ADD CONSTRAINT FK_83992B0BEF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_83992B0BEF1A9D84 ON user_blocked_notification (notification_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE new_user_notification DROP FOREIGN KEY FK_53BAD808EF1A9D84');
        $this->addSql('ALTER TABLE user_blocked_notification DROP FOREIGN KEY FK_83992B0BEF1A9D84');
        $this->addSql('CREATE TABLE new_user_notification_user (new_user_notification_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5A7909B4A07815E (new_user_notification_id), INDEX IDX_5A7909B4A76ED395 (user_id), PRIMARY KEY(new_user_notification_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_blocked_notification_user (user_blocked_notification_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3239F78B95B7FCDB (user_blocked_notification_id), INDEX IDX_3239F78BA76ED395 (user_id), PRIMARY KEY(user_blocked_notification_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE new_user_notification_user ADD CONSTRAINT FK_5A7909B4A07815E FOREIGN KEY (new_user_notification_id) REFERENCES new_user_notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE new_user_notification_user ADD CONSTRAINT FK_5A7909B4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_blocked_notification_user ADD CONSTRAINT FK_3239F78B95B7FCDB FOREIGN KEY (user_blocked_notification_id) REFERENCES user_blocked_notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_blocked_notification_user ADD CONSTRAINT FK_3239F78BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_user DROP FOREIGN KEY FK_35AF9D73EF1A9D84');
        $this->addSql('ALTER TABLE notification_user DROP FOREIGN KEY FK_35AF9D73A76ED395');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE notification_user');
        $this->addSql('DROP INDEX UNIQ_53BAD808EF1A9D84 ON new_user_notification');
        $this->addSql('ALTER TABLE new_user_notification ADD creation_date DATETIME NOT NULL, ADD is_viewed TINYINT(1) NOT NULL, DROP notification_id');
        $this->addSql('DROP INDEX UNIQ_83992B0BEF1A9D84 ON user_blocked_notification');
        $this->addSql('ALTER TABLE user_blocked_notification ADD creation_date DATETIME NOT NULL, ADD is_viewed TINYINT(1) NOT NULL, DROP notification_id');
    }
}
