<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221217010803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE new_user_notification ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE new_user_notification ADD CONSTRAINT FK_53BAD8087C2D807B FOREIGN KEY (new_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE new_user_notification ADD CONSTRAINT FK_53BAD808BF396750 FOREIGN KEY (id) REFERENCES notification (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_53BAD8087C2D807B ON new_user_notification (new_user_id)');
        $this->addSql('ALTER TABLE user ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE id id BIGINT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messenger_messages MODIFY id BIGINT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON messenger_messages');
        $this->addSql('DROP INDEX IDX_75EA56E0FB7336F0 ON messenger_messages');
        $this->addSql('DROP INDEX IDX_75EA56E0E3BD61CE ON messenger_messages');
        $this->addSql('DROP INDEX IDX_75EA56E016BA31DB ON messenger_messages');
        $this->addSql('ALTER TABLE messenger_messages CHANGE id id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE new_user_notification DROP FOREIGN KEY FK_53BAD8087C2D807B');
        $this->addSql('ALTER TABLE new_user_notification DROP FOREIGN KEY FK_53BAD808BF396750');
        $this->addSql('DROP INDEX UNIQ_53BAD8087C2D807B ON new_user_notification');
        $this->addSql('DROP INDEX `primary` ON new_user_notification');
        $this->addSql('ALTER TABLE user DROP updated_at');
    }
}
