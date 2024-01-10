<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240110151350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_PRODUCT_ID');
        $this->addSql('DROP INDEX FK_PRODUCT_ID ON feedback');
        $this->addSql('ALTER TABLE feedback DROP id_product');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedback ADD id_product INT NOT NULL');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_PRODUCT_ID FOREIGN KEY (id_product) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX FK_PRODUCT_ID ON feedback (id_product)');
    }
}
