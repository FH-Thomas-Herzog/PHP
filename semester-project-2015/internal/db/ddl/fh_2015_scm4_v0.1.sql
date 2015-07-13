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
-- Table `fh_2015_scm4_s1310307011`.`LOCALE`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fh_2015_scm4_s1310307011`.`LOCALE` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `fh_2015_scm4_s1310307011`.`LOCALE` (
  `id` VARCHAR(5) NOT NULL,
  `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `resource_key` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE UNIQUE INDEX `UNQ_IDX_LOCALE_KEY` ON `fh_2015_scm4_s1310307011`.`LOCALE` (`resource_key` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `fh_2015_scm4_s1310307011`.`USER_TYPE`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fh_2015_scm4_s1310307011`.`USER_TYPE` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `fh_2015_scm4_s1310307011`.`USER_TYPE` (
  `id` VARCHAR(20) NOT NULL,
  `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `fh_2015_scm4_s1310307011`.`USER`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fh_2015_scm4_s1310307011`.`USER` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `fh_2015_scm4_s1310307011`.`USER` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `creation_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_date` TIMESTAMP NULL,
  `blocked_date` TIMESTAMP NULL,
  `firstname` VARCHAR(20) NOT NULL,
  `lastname` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `deleted_flag` TINYINT(1) NOT NULL DEFAULT 0,
  `blocked_flag` TINYINT(1) NOT NULL DEFAULT 0,
  `locale_id` VARCHAR(5) NOT NULL,
  `user_type_id` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_LOCALE_ID`
    FOREIGN KEY (`locale_id`)
    REFERENCES `fh_2015_scm4_s1310307011`.`LOCALE` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_USER_USER_TYPE_ID`
    FOREIGN KEY (`user_type_id`)
    REFERENCES `fh_2015_scm4_s1310307011`.`USER_TYPE` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `IDX_FK_USER_LOCALE_ID` ON `fh_2015_scm4_s1310307011`.`USER` (`locale_id` ASC);

SHOW WARNINGS;
CREATE INDEX `IDX_FK_USER_USER_TYPE_ID` ON `fh_2015_scm4_s1310307011`.`USER` (`user_type_id` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `fh_2015_scm4_s1310307011`.`CHANNEL`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fh_2015_scm4_s1310307011`.`CHANNEL` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `fh_2015_scm4_s1310307011`.`CHANNEL` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `creation_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `fh_2015_scm4_s1310307011`.`THREAD`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fh_2015_scm4_s1310307011`.`THREAD` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `fh_2015_scm4_s1310307011`.`THREAD` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `creation_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` VARCHAR(255) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `channel_id` INT NOT NULL,
  `owner_user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_THREAD_CHANNEL_ID`
    FOREIGN KEY (`channel_id`)
    REFERENCES `fh_2015_scm4_s1310307011`.`CHANNEL` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_THREAD_USER_ID`
    FOREIGN KEY (`owner_user_id`)
    REFERENCES `fh_2015_scm4_s1310307011`.`USER` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `IDX_FK_THREAD_CHANNEL_ID` ON `fh_2015_scm4_s1310307011`.`THREAD` (`channel_id` ASC);

SHOW WARNINGS;
CREATE INDEX `IDX_FK_THREAD_USER_ID` ON `fh_2015_scm4_s1310307011`.`THREAD` (`owner_user_id` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `fh_2015_scm4_s1310307011`.`COMMENT`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fh_2015_scm4_s1310307011`.`COMMENT` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `fh_2015_scm4_s1310307011`.`COMMENT` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_comment` TEXT NOT NULL,
  `user_id` INT NOT NULL,
  `theme_id` INT NOT NULL,
  `thread_id` INT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_COMMENT_USER_ID`
    FOREIGN KEY (`user_id`)
    REFERENCES `fh_2015_scm4_s1310307011`.`USER` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_COMMENT_THREAD_ID`
    FOREIGN KEY (`theme_id`)
    REFERENCES `fh_2015_scm4_s1310307011`.`THREAD` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_COMMENT_COMMENT_ID`
    FOREIGN KEY (`thread_id`)
    REFERENCES `fh_2015_scm4_s1310307011`.`COMMENT` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `IDX_FK_COMMENT_USER_ID` ON `fh_2015_scm4_s1310307011`.`COMMENT` (`user_id` ASC);

SHOW WARNINGS;
CREATE INDEX `IDX_FK_COMMENT_THREAD_ID` ON `fh_2015_scm4_s1310307011`.`COMMENT` (`theme_id` ASC);

SHOW WARNINGS;
CREATE INDEX `IDX_FK_COMMENT_COMMENT_ID` ON `fh_2015_scm4_s1310307011`.`COMMENT` (`thread_id` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `fh_2015_scm4_s1310307011`.`COMMENT_USER_ENTRY`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fh_2015_scm4_s1310307011`.`COMMENT_USER_ENTRY` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `fh_2015_scm4_s1310307011`.`COMMENT_USER_ENTRY` (
  `comment_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `important_flag` TINYINT(1) NOT NULL DEFAULT 0,
  `read_flag` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`comment_id`, `user_id`),
  CONSTRAINT `FK_COMMENT_USER_ENTRY_COMMENT_ID`
    FOREIGN KEY (`comment_id`)
    REFERENCES `fh_2015_scm4_s1310307011`.`COMMENT` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_COMMENT_USER_ENTRY_USER_ID`
    FOREIGN KEY (`user_id`)
    REFERENCES `fh_2015_scm4_s1310307011`.`USER` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `IDX_FK_COMMENT_USER_ENTRY_COMMENT_ID` ON `fh_2015_scm4_s1310307011`.`COMMENT_USER_ENTRY` (`comment_id` ASC);

SHOW WARNINGS;
CREATE INDEX `IDX_FK_COMMENT_USER_ENTRY_USER_ID` ON `fh_2015_scm4_s1310307011`.`COMMENT_USER_ENTRY` (`user_id` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `fh_2015_scm4_s1310307011`.`CHANNEL_USER_ENTRY`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fh_2015_scm4_s1310307011`.`CHANNEL_USER_ENTRY` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `fh_2015_scm4_s1310307011`.`CHANNEL_USER_ENTRY` (
  `user_id` INT NOT NULL,
  `channel_id` INT NOT NULL,
  `favorite_flag` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`user_id`, `channel_id`),
  CONSTRAINT `FK_CHANNEL_USER_ENTRY_CHANNEL_ID`
    FOREIGN KEY (`user_id`)
    REFERENCES `fh_2015_scm4_s1310307011`.`USER` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_CHANNEL_USER_ENTRY_CHANNEL1`
    FOREIGN KEY (`channel_id`)
    REFERENCES `fh_2015_scm4_s1310307011`.`CHANNEL` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `IDX_FK_CHANNEL_USER_ENTRY_USER_ID` ON `fh_2015_scm4_s1310307011`.`CHANNEL_USER_ENTRY` (`user_id` ASC);

SHOW WARNINGS;
CREATE INDEX `IDX_FK_CHANNEL_USER_ENTRY_CHANNEL_ID` ON `fh_2015_scm4_s1310307011`.`CHANNEL_USER_ENTRY` (`channel_id` ASC);

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `fh_2015_scm4_s1310307011`.`THREAD_USER_ENTRY`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fh_2015_scm4_s1310307011`.`THREAD_USER_ENTRY` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `fh_2015_scm4_s1310307011`.`THREAD_USER_ENTRY` (
  `thread_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `favorite_flag` TINYINT(1) NOT NULL DEFAULT 0,
  `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`thread_id`, `user_id`),
  CONSTRAINT `FK_THEME_USER_ENTRY_THEME_ID`
    FOREIGN KEY (`thread_id`)
    REFERENCES `fh_2015_scm4_s1310307011`.`THREAD` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_THEME_USER_ENTRY_USER_ID`
    FOREIGN KEY (`user_id`)
    REFERENCES `fh_2015_scm4_s1310307011`.`USER` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `IDX_FK_THREAD_USER_ENTRY_THREAD_ID` ON `fh_2015_scm4_s1310307011`.`THREAD_USER_ENTRY` (`thread_id` ASC);

SHOW WARNINGS;
CREATE INDEX `FK_THREAD_USER_ENTRY_USER_ID` ON `fh_2015_scm4_s1310307011`.`THREAD_USER_ENTRY` (`user_id` ASC);

SHOW WARNINGS;
USE `fh_2015_scm4_s1310307011`;

DELIMITER $$

USE `fh_2015_scm4_s1310307011`$$
DROP TRIGGER IF EXISTS `fh_2015_scm4_s1310307011`.`LOCALE_BEFORE_UPDATE` $$
SHOW WARNINGS$$
USE `fh_2015_scm4_s1310307011`$$
CREATE DEFINER = CURRENT_USER TRIGGER `fh_2015_scm4_s1310307011`.`LOCALE_BEFORE_UPDATE` BEFORE UPDATE ON `LOCALE` FOR EACH ROW
BEGIN
	SET NEW.updated_date = CURRENT_TIMESTAMP;
END
$$

SHOW WARNINGS$$

USE `fh_2015_scm4_s1310307011`$$
DROP TRIGGER IF EXISTS `fh_2015_scm4_s1310307011`.`USER_BEFORE_UPDATE` $$
SHOW WARNINGS$$
USE `fh_2015_scm4_s1310307011`$$
CREATE DEFINER = CURRENT_USER TRIGGER `fh_2015_scm4_s1310307011`.`USER_BEFORE_UPDATE` BEFORE UPDATE ON `USER` FOR EACH ROW
BEGIN
 SET NEW.updated_date = CURRENT_TIMESTAMP;   
END
$$

SHOW WARNINGS$$

USE `fh_2015_scm4_s1310307011`$$
DROP TRIGGER IF EXISTS `fh_2015_scm4_s1310307011`.`CHANNEL_BEFORE_UPDATE` $$
SHOW WARNINGS$$
USE `fh_2015_scm4_s1310307011`$$
CREATE DEFINER = CURRENT_USER TRIGGER `fh_2015_scm4_s1310307011`.`CHANNEL_BEFORE_UPDATE` BEFORE UPDATE ON `CHANNEL` FOR EACH ROW
BEGIN
	SET NEW.updated_date = CURRENT_TIMESTAMP;
END
$$

SHOW WARNINGS$$

USE `fh_2015_scm4_s1310307011`$$
DROP TRIGGER IF EXISTS `fh_2015_scm4_s1310307011`.`THREAD_BEFORE_UPDATE` $$
SHOW WARNINGS$$
USE `fh_2015_scm4_s1310307011`$$
CREATE DEFINER = CURRENT_USER TRIGGER `fh_2015_scm4_s1310307011`.`THREAD_BEFORE_UPDATE` BEFORE UPDATE ON `THREAD` FOR EACH ROW
BEGIN
	SET NEW.updated_date = CURRENT_TIMESTAMP;
END
$$

SHOW WARNINGS$$

USE `fh_2015_scm4_s1310307011`$$
DROP TRIGGER IF EXISTS `fh_2015_scm4_s1310307011`.`COMMENT_BEFORE_UPDATE` $$
SHOW WARNINGS$$
USE `fh_2015_scm4_s1310307011`$$
CREATE DEFINER = CURRENT_USER TRIGGER `fh_2015_scm4_s1310307011`.`COMMENT_BEFORE_UPDATE` BEFORE UPDATE ON `COMMENT` FOR EACH ROW
BEGIN
	SET NEW.updated_date = CURRENT_TIMESTAMP;
END
$$

SHOW WARNINGS$$

USE `fh_2015_scm4_s1310307011`$$
DROP TRIGGER IF EXISTS `fh_2015_scm4_s1310307011`.`COMMENT_USER_ENTRY_BEFORE_UPDATE` $$
SHOW WARNINGS$$
USE `fh_2015_scm4_s1310307011`$$
CREATE DEFINER = CURRENT_USER TRIGGER `fh_2015_scm4_s1310307011`.`COMMENT_USER_ENTRY_BEFORE_UPDATE` BEFORE UPDATE ON `COMMENT_USER_ENTRY` FOR EACH ROW
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

-- end attached script 'script'
