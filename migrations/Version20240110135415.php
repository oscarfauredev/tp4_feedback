<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240110135415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD disponibility TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE feedback ADD id_product INT NOT NULL');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_PRODUCT_ID FOREIGN KEY (id_product) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product DROP disponibility');
        $this->addSql('ALTER TABLE feedback DROP id_product');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_PRODUCT_ID');
    }
}
