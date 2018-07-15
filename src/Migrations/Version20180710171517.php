<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180710171517 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_order DROP FOREIGN KEY FK_5475E8C48D9F6D38');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, total_price DOUBLE PRECISION NOT NULL, user_name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phone INT NOT NULL, status INT NOT NULL, created_at DATETIME DEFAULT NULL, city VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP INDEX IDX_5475E8C48D9F6D38 ON product_order');
        $this->addSql('ALTER TABLE product_order CHANGE order_id orders_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_order ADD CONSTRAINT FK_5475E8C4CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('CREATE INDEX IDX_5475E8C4CFFE9AD6 ON product_order (orders_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_order DROP FOREIGN KEY FK_5475E8C4CFFE9AD6');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, status INT NOT NULL, created_at DATETIME DEFAULT NULL, total_price DOUBLE PRECISION NOT NULL, user_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, address VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, phone INT NOT NULL, city VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP INDEX IDX_5475E8C4CFFE9AD6 ON product_order');
        $this->addSql('ALTER TABLE product_order CHANGE orders_id order_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_order ADD CONSTRAINT FK_5475E8C48D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_5475E8C48D9F6D38 ON product_order (order_id)');
    }
}
