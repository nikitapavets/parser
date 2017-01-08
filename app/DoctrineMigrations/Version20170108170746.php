<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170108170746 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tblproductdata ADD intProductStock INT NOT NULL, ADD intProductCost DOUBLE PRECISION NOT NULL');
        $this->addSql('DROP INDEX strproductcode ON tblproductdata');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2C11248662F10A58 ON tblproductdata (strProductCode)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tblProductData DROP intProductStock, DROP intProductCost');
        $this->addSql('DROP INDEX uniq_2c11248662f10a58 ON tblProductData');
        $this->addSql('CREATE UNIQUE INDEX strProductCode ON tblProductData (strProductCode)');
    }
}
