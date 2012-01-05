CREATE TABLE album (id BIGINT AUTO_INCREMENT, user_id BIGINT, name TEXT, INDEX user_id_idx (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE user (id BIGINT AUTO_INCREMENT, email VARCHAR(32) DEFAULT 'default username', password VARCHAR(255), birthday DATE, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
ALTER TABLE album ADD CONSTRAINT album_user_id_user_id FOREIGN KEY (user_id) REFERENCES user(id);
