<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506214203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE currency (code VARCHAR(10) NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(code))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE currency_pair (id SERIAL NOT NULL, base_currency_code VARCHAR(10) NOT NULL, quote_currency_code VARCHAR(10) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_83ED5D1D75194655 ON currency_pair (base_currency_code)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_83ED5D1D14763786 ON currency_pair (quote_currency_code)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX base_quote_unique ON currency_pair (base_currency_code, quote_currency_code)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE exchange_rate (id SERIAL NOT NULL, currency_pair_id INT NOT NULL, rate NUMERIC(18, 8) NOT NULL, timestamp TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E9521FABA311484C ON exchange_rate (currency_pair_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX pair_time_unique ON exchange_rate (currency_pair_id, timestamp)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN exchange_rate.timestamp IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE currency_pair ADD CONSTRAINT FK_83ED5D1D75194655 FOREIGN KEY (base_currency_code) REFERENCES currency (code) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE currency_pair ADD CONSTRAINT FK_83ED5D1D14763786 FOREIGN KEY (quote_currency_code) REFERENCES currency (code) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exchange_rate ADD CONSTRAINT FK_E9521FABA311484C FOREIGN KEY (currency_pair_id) REFERENCES currency_pair (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE currency_pair DROP CONSTRAINT FK_83ED5D1D75194655
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE currency_pair DROP CONSTRAINT FK_83ED5D1D14763786
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exchange_rate DROP CONSTRAINT FK_E9521FABA311484C
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE currency
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE currency_pair
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE exchange_rate
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
