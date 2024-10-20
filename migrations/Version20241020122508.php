<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241020122508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__loan AS SELECT id, customer_id, book_id, loandate FROM loan');
        $this->addSql('DROP TABLE loan');
        $this->addSql('CREATE TABLE loan (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, customer_id INTEGER NOT NULL, book_id INTEGER NOT NULL, loandate DATETIME NOT NULL, CONSTRAINT FK_C5D30D039395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C5D30D0316A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO loan (id, customer_id, book_id, loandate) SELECT id, customer_id, book_id, loandate FROM __temp__loan');
        $this->addSql('DROP TABLE __temp__loan');
        $this->addSql('CREATE INDEX IDX_C5D30D0316A2B381 ON loan (book_id)');
        $this->addSql('CREATE INDEX IDX_C5D30D039395C3F3 ON loan (customer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__loan AS SELECT id, customer_id, book_id, loandate FROM loan');
        $this->addSql('DROP TABLE loan');
        $this->addSql('CREATE TABLE loan (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, customer_id INTEGER NOT NULL, book_id INTEGER NOT NULL, loandate DATETIME NOT NULL, CONSTRAINT FK_C5D30D039395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C5D30D0316A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO loan (id, customer_id, book_id, loandate) SELECT id, customer_id, book_id, loandate FROM __temp__loan');
        $this->addSql('DROP TABLE __temp__loan');
        $this->addSql('CREATE INDEX IDX_C5D30D039395C3F3 ON loan (customer_id)');
        $this->addSql('CREATE INDEX IDX_C5D30D0316A2B381 ON loan (book_id)');
    }
}
