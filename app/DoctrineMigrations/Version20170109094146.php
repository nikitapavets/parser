<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170109094146 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tblProductData ADD intProductStock INT NOT NULL, ADD intProductCost DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE tblProductData RENAME INDEX strproductcode TO UNIQ_2C11248662F10A58');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tblProductData DROP intProductStock, DROP intProductCost');
        $this->addSql('ALTER TABLE tblProductData RENAME INDEX uniq_2c11248662f10a58 TO strProductCode');
    }
}
