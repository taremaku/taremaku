<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210718130940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE episode_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE following_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE genre_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE network_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE season_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tvshow_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE web_channel_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE episode (id INT NOT NULL, season_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, number INT NOT NULL, runtime INT DEFAULT NULL, summary TEXT DEFAULT NULL, airstamp TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, airdate TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, airtime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DDAA1CDA4EC001D1 ON episode (season_id)');
        $this->addSql('COMMENT ON COLUMN episode.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE following (id INT NOT NULL, user_id INT DEFAULT NULL, episode_id INT DEFAULT NULL, season_id INT DEFAULT NULL, tv_show_id INT DEFAULT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, status INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_71BF8DE3A76ED395 ON following (user_id)');
        $this->addSql('CREATE INDEX IDX_71BF8DE3362B62A0 ON following (episode_id)');
        $this->addSql('CREATE INDEX IDX_71BF8DE34EC001D1 ON following (season_id)');
        $this->addSql('CREATE INDEX IDX_71BF8DE35E3A35BB ON following (tv_show_id)');
        $this->addSql('COMMENT ON COLUMN following.start_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN following.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE genre (id INT NOT NULL, name VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN genre.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE network (id INT NOT NULL, name VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN network.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE role (id INT NOT NULL, name VARCHAR(32) NOT NULL, code VARCHAR(12) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN role.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE season (id INT NOT NULL, tv_show_id INT DEFAULT NULL, number INT NOT NULL, poster VARCHAR(255) DEFAULT NULL, episode_count INT DEFAULT NULL, premiere_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F0E45BA95E3A35BB ON season (tv_show_id)');
        $this->addSql('COMMENT ON COLUMN season.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE tvshow (id INT NOT NULL, type_id INT DEFAULT NULL, network_id INT DEFAULT NULL, web_channel_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, summary TEXT DEFAULT NULL, status INT NOT NULL, poster VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, rating DOUBLE PRECISION DEFAULT NULL, language VARCHAR(16) NOT NULL, slug VARCHAR(255) DEFAULT NULL, runtime INT DEFAULT NULL, premiered VARCHAR(255) DEFAULT NULL, id_tvmaze INT DEFAULT NULL, id_imdb VARCHAR(12) DEFAULT NULL, id_the_tv_db INT DEFAULT NULL, api_update INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_44A9FD06C54C8C93 ON tvshow (type_id)');
        $this->addSql('CREATE INDEX IDX_44A9FD0634128B91 ON tvshow (network_id)');
        $this->addSql('CREATE INDEX IDX_44A9FD06F3951BA6 ON tvshow (web_channel_id)');
        $this->addSql('COMMENT ON COLUMN tvshow.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE show_genre (show_id INT NOT NULL, genre_id INT NOT NULL, PRIMARY KEY(show_id, genre_id))');
        $this->addSql('CREATE INDEX IDX_81E15724D0C1FC64 ON show_genre (show_id)');
        $this->addSql('CREATE INDEX IDX_81E157244296D31F ON show_genre (genre_id)');
        $this->addSql('CREATE TABLE type (id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN type.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, role_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, plain_password VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8D93D649D60322AC ON "user" (role_id)');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE web_channel (id INT NOT NULL, name VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN web_channel.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE3A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE3362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE34EC001D1 FOREIGN KEY (season_id) REFERENCES season (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE35E3A35BB FOREIGN KEY (tv_show_id) REFERENCES tvshow (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_F0E45BA95E3A35BB FOREIGN KEY (tv_show_id) REFERENCES tvshow (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tvshow ADD CONSTRAINT FK_44A9FD06C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tvshow ADD CONSTRAINT FK_44A9FD0634128B91 FOREIGN KEY (network_id) REFERENCES network (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tvshow ADD CONSTRAINT FK_44A9FD06F3951BA6 FOREIGN KEY (web_channel_id) REFERENCES web_channel (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE show_genre ADD CONSTRAINT FK_81E15724D0C1FC64 FOREIGN KEY (show_id) REFERENCES tvshow (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE show_genre ADD CONSTRAINT FK_81E157244296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE following DROP CONSTRAINT FK_71BF8DE3362B62A0');
        $this->addSql('ALTER TABLE show_genre DROP CONSTRAINT FK_81E157244296D31F');
        $this->addSql('ALTER TABLE tvshow DROP CONSTRAINT FK_44A9FD0634128B91');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649D60322AC');
        $this->addSql('ALTER TABLE episode DROP CONSTRAINT FK_DDAA1CDA4EC001D1');
        $this->addSql('ALTER TABLE following DROP CONSTRAINT FK_71BF8DE34EC001D1');
        $this->addSql('ALTER TABLE following DROP CONSTRAINT FK_71BF8DE35E3A35BB');
        $this->addSql('ALTER TABLE season DROP CONSTRAINT FK_F0E45BA95E3A35BB');
        $this->addSql('ALTER TABLE show_genre DROP CONSTRAINT FK_81E15724D0C1FC64');
        $this->addSql('ALTER TABLE tvshow DROP CONSTRAINT FK_44A9FD06C54C8C93');
        $this->addSql('ALTER TABLE following DROP CONSTRAINT FK_71BF8DE3A76ED395');
        $this->addSql('ALTER TABLE tvshow DROP CONSTRAINT FK_44A9FD06F3951BA6');
        $this->addSql('DROP SEQUENCE episode_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE following_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE genre_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE network_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE role_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE season_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tvshow_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE web_channel_id_seq CASCADE');
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE following');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE network');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE season');
        $this->addSql('DROP TABLE tvshow');
        $this->addSql('DROP TABLE show_genre');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE web_channel');
    }
}
