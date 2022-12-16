<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221209161931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE new_user_notification DROP FOREIGN KEY FK_53BAD808EF1A9D84');
        $this->addSql('DROP INDEX UNIQ_53BAD808EF1A9D84 ON new_user_notification');
        $this->addSql('ALTER TABLE new_user_notification DROP notification_id');
        $this->addSql('ALTER TABLE user_blocked_notification DROP FOREIGN KEY FK_83992B0BEF1A9D84');
        $this->addSql('DROP INDEX UNIQ_83992B0BEF1A9D84 ON user_blocked_notification');
        $this->addSql('ALTER TABLE user_blocked_notification DROP notification_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE new_user_notification ADD notification_id INT NOT NULL');
        $this->addSql('ALTER TABLE new_user_notification ADD CONSTRAINT FK_53BAD808EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_53BAD808EF1A9D84 ON new_user_notification (notification_id)');
        $this->addSql('ALTER TABLE user_blocked_notification ADD notification_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_blocked_notification ADD CONSTRAINT FK_83992B0BEF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_83992B0BEF1A9D84 ON user_blocked_notification (notification_id)');
    }
}
