<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Order and OrderItem tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE `order` (
            id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
            status VARCHAR(255) NOT NULL,
            total INT NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE order_item (
            id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
            product_id VARCHAR(255) NOT NULL,
            product_name VARCHAR(255) NOT NULL,
            price INT NOT NULL,
            quantity INT NOT NULL,
            order_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\',
            PRIMARY KEY(id),
            CONSTRAINT FK_ORDER_ITEM_ORDER FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE
        )');

        $this->addSql('CREATE INDEX IDX_ORDER_ITEM_ORDER ON order_item (order_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE `order`');
    }
}
