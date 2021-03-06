-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema fh_2015_scm4_s1310307011
-- -----------------------------------------------------
-- This is the databaase schema for the semester project in SCM4.
-- This schema holds all the table for the sslack like forum.
DROP SCHEMA IF EXISTS `fh_2015_scm4_s1310307011` ;

-- -----------------------------------------------------
-- Schema fh_2015_scm4_s1310307011
--
-- This is the databaase schema for the semester project in SCM4.
-- This schema holds all the table for the sslack like forum.
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `fh_2015_scm4_s1310307011` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
SHOW WARNINGS;
USE `fh_2015_scm4_s1310307011` ;

-- -----------------------------------------------------
-- Table `fh_2015_scm4_s1310307011`.`USER`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fh_2015_scm4_s1310307011`.`USER` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `fh_2015_scm4_s1310307011`.`USER` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `creation_date` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) COMMENT '',
  `updated_date` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) COMMENT '',
  `deleted_date` TIMESTAMP(6) NULL COMMENT '',
  `firstname` VARCHAR(20) NOT NULL COMMENT '',
  `lastname` VARCHAR(20) NOT NULL COMMENT '',
  `email` VARCHAR(100) NOT NULL COMMENT '',
  `username` VARCHAR(50) NOT NULL COMMENT '',
  `password` VARCHAR(255) NOT NULL COMMENT '',
  `deleted_flag` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE UNIQUE INDEX `UNQ_EMAIL` ON `fh_2015_scm4_s1310307011`.`USER` (`email` ASC)  COMMENT '';

SHOW WARNINGS;
CREATE UNIQUE INDEX `UNQ_USERNAME` ON `fh_2015_scm4_s1310307011`.`USER` (`username` ASC)  COMMENT '';

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `fh_2015_scm4_s1310307011`.`CHANNEL`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fh_2015_scm4_s1310307011`.`CHANNEL` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `fh_2015_scm4_s1310307011`.`CHANNEL` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `creation_date` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) COMMENT '',
  `updated_date` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) COMMENT '',
  `title` VARCHAR(255) NOT NULL COMMENT '',
  `description` TEXT NOT NULL COMMENT '',
  `deleted_flag` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '',
  `user_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  CONSTRAINT `FK_USER_ID`
    FOREIGN KEY (`user_id`)
    REFERENCES `fh_2015_scm4_s1310307011`.`USER` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `IDX_FK_USER_ID` ON `fh_2015_scm4_s1310307011`.`CHANNEL` (`user_id` ASC)  COMMENT '';

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `fh_2015_scm4_s1310307011`.`CHANNEL_USER_ENTRY`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fh_2015_scm4_s1310307011`.`CHANNEL_USER_ENTRY` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `fh_2015_scm4_s1310307011`.`CHANNEL_USER_ENTRY` (
  `user_id` INT NOT NULL COMMENT '',
  `channel_id` INT NOT NULL COMMENT '',
  `favorite_flag` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '',
  PRIMARY KEY (`user_id`, `channel_id`)  COMMENT '',
  CONSTRAINT `FK_CHANNEL_USER_ENTRY_USER_ID`
    FOREIGN KEY (`user_id`)
    REFERENCES `fh_2015_scm4_s1310307011`.`USER` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_CHANNEL_USER_ENTRY_CHANNEL_ID`
    FOREIGN KEY (`channel_id`)
    REFERENCES `fh_2015_scm4_s1310307011`.`CHANNEL` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `IDX_FK_CHANNEL_USER_ENTRY_USER_ID` ON `fh_2015_scm4_s1310307011`.`CHANNEL_USER_ENTRY` (`user_id` ASC)  COMMENT '';

SHOW WARNINGS;
CREATE INDEX `IDX_FK_CHANNEL_USER_ENTRY_CHANNEL_ID` ON `fh_2015_scm4_s1310307011`.`CHANNEL_USER_ENTRY` (`channel_id` ASC)  COMMENT '';

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `fh_2015_scm4_s1310307011`.`CHANNEL_MESSAGE`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fh_2015_scm4_s1310307011`.`CHANNEL_MESSAGE` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `fh_2015_scm4_s1310307011`.`CHANNEL_MESSAGE` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `creation_date` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) COMMENT '',
  `updated_date` TIMESTAMP(6) NULL DEFAULT CURRENT_TIMESTAMP(6) COMMENT '',
  `message` VARCHAR(2048) NOT NULL COMMENT '',
  `channel_id` INT NOT NULL COMMENT '',
  `user_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  CONSTRAINT `FK_CHANNEL_MESSAGE_CHANNEL_ID`
    FOREIGN KEY (`channel_id`)
    REFERENCES `fh_2015_scm4_s1310307011`.`CHANNEL` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_CHANNEL_MESSAGE_USER_ID`
    FOREIGN KEY (`user_id`)
    REFERENCES `fh_2015_scm4_s1310307011`.`USER` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `IDX_FK_CHANNEL_MESSAGE_CHANNEL_ID` ON `fh_2015_scm4_s1310307011`.`CHANNEL_MESSAGE` (`channel_id` ASC)  COMMENT '';

SHOW WARNINGS;
CREATE INDEX `IDX_FK_CHANNEL_MESSAGE_USER_ID` ON `fh_2015_scm4_s1310307011`.`CHANNEL_MESSAGE` (`user_id` ASC)  COMMENT '';

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `fh_2015_scm4_s1310307011`.`CHANNEL_MESSAGE_USER_ENTRY`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fh_2015_scm4_s1310307011`.`CHANNEL_MESSAGE_USER_ENTRY` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `fh_2015_scm4_s1310307011`.`CHANNEL_MESSAGE_USER_ENTRY` (
  `user_id` INT NOT NULL COMMENT '',
  `channel_message_id` INT NOT NULL COMMENT '',
  `read_date` TIMESTAMP(6) NULL COMMENT '',
  `read_flag` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '',
  `important_flag` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '',
  PRIMARY KEY (`user_id`, `channel_message_id`)  COMMENT '',
  CONSTRAINT `FK_CHANNEL_MESSAGE_USER_ENTRY_USER_ID`
    FOREIGN KEY (`user_id`)
    REFERENCES `fh_2015_scm4_s1310307011`.`USER` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_CHANNEL_MESSAGE_USER_ENTRY_CHANNEL_MESSAGE_ID`
    FOREIGN KEY (`channel_message_id`)
    REFERENCES `fh_2015_scm4_s1310307011`.`CHANNEL_MESSAGE` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `IDX_FK_CHANNEL_MESSAGE_USER_ENTRY_USER_ID` ON `fh_2015_scm4_s1310307011`.`CHANNEL_MESSAGE_USER_ENTRY` (`user_id` ASC)  COMMENT '';

SHOW WARNINGS;
CREATE INDEX `IDX_FK_CHANNEL_MESSAGE_USER_ENTRY_CHANNEL_MESSAGE_ID` ON `fh_2015_scm4_s1310307011`.`CHANNEL_MESSAGE_USER_ENTRY` (`channel_message_id` ASC)  COMMENT '';

SHOW WARNINGS;
USE `fh_2015_scm4_s1310307011`;

DELIMITER $$

USE `fh_2015_scm4_s1310307011`$$
DROP TRIGGER IF EXISTS `fh_2015_scm4_s1310307011`.`SET_MODIFICATION_DATE` $$
SHOW WARNINGS$$
USE `fh_2015_scm4_s1310307011`$$
CREATE DEFINER = CURRENT_USER TRIGGER `fh_2015_scm4_s1310307011`.`SET_MODIFICATION_DATE` BEFORE UPDATE ON `USER` FOR EACH ROW
BEGIN
 SET NEW.updated_date = CURRENT_TIMESTAMP;   
END
$$

SHOW WARNINGS$$

USE `fh_2015_scm4_s1310307011`$$
DROP TRIGGER IF EXISTS `fh_2015_scm4_s1310307011`.`SET_UPDATED_DATE` $$
SHOW WARNINGS$$
USE `fh_2015_scm4_s1310307011`$$
CREATE DEFINER = CURRENT_USER TRIGGER `fh_2015_scm4_s1310307011`.`SET_UPDATED_DATE` BEFORE UPDATE ON `CHANNEL_MESSAGE` FOR EACH ROW
BEGIN
 SET NEW.updated_date = CURRENT_TIMESTAMP;   
END
$$

SHOW WARNINGS$$

USE `fh_2015_scm4_s1310307011`$$
DROP TRIGGER IF EXISTS `fh_2015_scm4_s1310307011`.`SET_UPDATED_DATE` $$
SHOW WARNINGS$$
USE `fh_2015_scm4_s1310307011`$$
CREATE DEFINER = CURRENT_USER TRIGGER `fh_2015_scm4_s1310307011`.`SET_UPDATED_DATE` BEFORE UPDATE ON `CHANNEL_MESSAGE` FOR EACH ROW
BEGIN
 SET NEW.updated_date = CURRENT_TIMESTAMP;   
END
$$

SHOW WARNINGS$$

DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
-- begin attached script 'script'

-- begin Create default channels and users (username: 'het', password: '123')
INSERT INTO `fh_2015_scm4_s1310307011`.`USER`(firstname, lastname, email, username, password)
VALUES('Thomas', 'Herzog', 'S1310307011@students.fh-hagenberg.at', 'het', '$2y$10$kfWLqqpc1JwcNksba5DRKe7Ro9enS0S.jDPidJIyUp/mH34CZWMly');

SET @user_id = LAST_INSERT_ID();

INSERT INTO `fh_2015_scm4_s1310307011`.`CHANNEL`(title, description, user_id)
VALUES('Java', 'The java channel', @user_id);

SET @java_channel_id = LAST_INSERT_ID();

INSERT INTO `fh_2015_scm4_s1310307011`.`CHANNEL`(title, description, user_id)
VALUES('PHP', 'The php channel', @user_id);

SET @php_channel_id = LAST_INSERT_ID();

INSERT INTO `fh_2015_scm4_s1310307011`.`CHANNEL_USER_ENTRY`(channel_id, user_id, favorite_flag)
VALUES(@php_channel_id, @user_id, 1);

INSERT INTO `fh_2015_scm4_s1310307011`.`CHANNEL_USER_ENTRY`(channel_id, user_id, favorite_flag)
VALUES(@java_channel_id, @user_id, 0);

-- begin Create default channels and users

-- end attached script 'script'
