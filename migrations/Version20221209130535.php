<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221209130535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE new_user_notification CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE new_user_notification ADD CONSTRAINT FK_53BAD808BF396750 FOREIGN KEY (id) REFERENCES notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_blocked_notification CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE user_blocked_notification ADD CONSTRAINT FK_83992B0BBF396750 FOREIGN KEY (id) REFERENCES notification (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE new_user_notification DROP FOREIGN KEY FK_53BAD808BF396750');
        $this->addSql('ALTER TABLE new_user_notification CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE user_blocked_notification DROP FOREIGN KEY FK_83992B0BBF396750');
        $this->addSql('ALTER TABLE user_blocked_notification CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
