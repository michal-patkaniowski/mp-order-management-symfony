<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410145139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE order_item (id BLOB NOT NULL --(DC2Type:uuid)
            , order_id BLOB DEFAULT NULL --(DC2Type:uuid)
            , product_id VARCHAR(255) NOT NULL, product_name VARCHAR(255) NOT NULL, price INTEGER NOT NULL, quantity INTEGER NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES "order_table" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_52EA1F098D9F6D38 ON order_item (order_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "order_table" (id BLOB NOT NULL --(DC2Type:uuid)
            , created_at DATETIME NOT NULL, status VARCHAR(255) NOT NULL, total INTEGER NOT NULL, PRIMARY KEY(id))
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE order_item
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "order_table"
        SQL);
    }
}
