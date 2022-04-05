<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220405145855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331D4C006C8');
        $this->addSql('DROP INDEX IDX_CBE5A331D4C006C8 ON book');
        $this->addSql('ALTER TABLE book DROP borrow_id');
        $this->addSql('ALTER TABLE borrow ADD books_id INT DEFAULT NULL, ADD users_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE borrow ADD CONSTRAINT FK_55DBA8B07DD8AC20 FOREIGN KEY (books_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE borrow ADD CONSTRAINT FK_55DBA8B067B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_55DBA8B07DD8AC20 ON borrow (books_id)');
        $this->addSql('CREATE INDEX IDX_55DBA8B067B3B43D ON borrow (users_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D4C006C8');
        $this->addSql('DROP INDEX IDX_8D93D649D4C006C8 ON user');
        $this->addSql('ALTER TABLE user DROP borrow_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD borrow_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331D4C006C8 FOREIGN KEY (borrow_id) REFERENCES borrow (id)');
        $this->addSql('CREATE INDEX IDX_CBE5A331D4C006C8 ON book (borrow_id)');
        $this->addSql('ALTER TABLE borrow DROP FOREIGN KEY FK_55DBA8B07DD8AC20');
        $this->addSql('ALTER TABLE borrow DROP FOREIGN KEY FK_55DBA8B067B3B43D');
        $this->addSql('DROP INDEX IDX_55DBA8B07DD8AC20 ON borrow');
        $this->addSql('DROP INDEX IDX_55DBA8B067B3B43D ON borrow');
        $this->addSql('ALTER TABLE borrow DROP books_id, DROP users_id');
        $this->addSql('ALTER TABLE user ADD borrow_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D4C006C8 FOREIGN KEY (borrow_id) REFERENCES borrow (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649D4C006C8 ON user (borrow_id)');
    }
}
