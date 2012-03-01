SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `CentralAccountDB` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `CentralAccountDB` ;

-- -----------------------------------------------------
-- Table `CentralAccountDB`.`vak`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`vak` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `vaknaam` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`persoon`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`persoon` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `type` INT UNSIGNED NOT NULL ,
  `account_gebruikersnaam` VARCHAR(45) NOT NULL ,
  `account_wachtwoord` VARCHAR(64) NOT NULL ,
  `account_actief` TINYINT(1) NOT NULL ,
  `account_actief_tot` VARCHAR(8) NULL ,
  `account_actief_van` VARCHAR(8) NULL ,
  `startdatum` DATETIME NULL ,
  `voornaam` VARCHAR(45) NOT NULL ,
  `familienaam` VARCHAR(45) NOT NULL ,
  `geslacht` CHAR NULL ,
  `geboortedatum` VARCHAR(8) NULL ,
  `geboorteplaats` VARCHAR(45) NULL ,
  `nationaliteit` VARCHAR(45) NULL ,
  `straat` VARCHAR(50) NULL ,
  `postbus` VARCHAR(40) NULL ,
  `postcode` VARCHAR(4) NULL ,
  `gemeente` VARCHAR(40) NULL ,
  `land` VARCHAR(45) NULL ,
  `email` VARCHAR(50) NULL ,
  `tel` VARCHAR(30) NULL ,
  `tel2` VARCHAR(30) NULL ,
  `gsm` VARCHAR(30) NULL ,
  `gemaakt_op` VARCHAR(8) NULL ,
  `groep_id` INT NULL ,
  `beschrijving` VARCHAR(150) NULL ,
  `actief` TINYINT(1) NULL ,
  `verwijderd` TINYINT(1) NULL ,
  `lkr_vak_id` INT NULL ,
  `lln_vorige_school` VARCHAR(50) NULL ,
  `lln_stam_nummer` VARCHAR(30) NULL ,
  `ouder_beroep` VARCHAR(100) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `lkr_vak_id` (`lkr_vak_id` ASC) ,
  CONSTRAINT `lkr_vak_id`
    FOREIGN KEY (`lkr_vak_id` )
    REFERENCES `CentralAccountDB`.`vak` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`groep`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`groep` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `naam` VARCHAR(45) NOT NULL ,
  `parent_id` INT NULL ,
  `child_id` INT NULL ,
  `beschrijving_rechten` TEXT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`relatie_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`relatie_type` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `beschrijving` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`persoon_relatie`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`persoon_relatie` (
  `persoon1_id` INT UNSIGNED NOT NULL ,
  `persoon2_id` INT UNSIGNED NOT NULL ,
  `type_relatie_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`persoon1_id`, `persoon2_id`) ,
  INDEX `persoon1_id` (`persoon1_id` ASC, `persoon2_id` ASC, `type_relatie_id` ASC) ,
  INDEX `persoon2_id` () ,
  INDEX `type_relatie_id` () ,
  CONSTRAINT `persoon1_id`
    FOREIGN KEY (`persoon1_id` , `persoon2_id` , `type_relatie_id` )
    REFERENCES `CentralAccountDB`.`persoon` (`id` , `id` , `id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `persoon2_id`
    FOREIGN KEY ()
    REFERENCES `CentralAccountDB`.`persoon` ()
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `type_relatie_id`
    FOREIGN KEY ()
    REFERENCES `CentralAccountDB`.`relatie_type` ()
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`groep_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`groep_type` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `naam` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`rechten_solid_eagle`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`rechten_solid_eagle` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  PRIMARY KEY (`id`) ,
  INDEX `id` (`id` ASC) ,
  CONSTRAINT `id`
    FOREIGN KEY (`id` )
    REFERENCES `CentralAccountDB`.`groep_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`groep_closure`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`groep_closure` (
  `parent_id` INT UNSIGNED NOT NULL ,
  `child_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`parent_id`, `child_id`) ,
  INDEX `parent_id` (`parent_id` ASC, `child_id` ASC) ,
  INDEX `child_id` () ,
  CONSTRAINT `parent_id`
    FOREIGN KEY (`parent_id` , `child_id` )
    REFERENCES `CentralAccountDB`.`groep` (`id` , `id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `child_id`
    FOREIGN KEY ()
    REFERENCES `CentralAccountDB`.`groep` ()
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`groep_persoon`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`groep_persoon` (
  `groep_id` INT UNSIGNED NOT NULL ,
  `persoon_id` INT UNSIGNED NOT NULL ,
  `lkr_klastitularis` TINYINT(1) NULL ,
  PRIMARY KEY (`groep_id`, `persoon_id`) ,
  INDEX `fk_groep_has_persoon_persoon1` (`persoon_id` ASC) ,
  INDEX `fk_groep_has_persoon_groep` (`groep_id` ASC) ,
  CONSTRAINT `fk_groep_has_persoon_groep`
    FOREIGN KEY (`groep_id` )
    REFERENCES `CentralAccountDB`.`groep` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_groep_has_persoon_persoon1`
    FOREIGN KEY (`persoon_id` )
    REFERENCES `CentralAccountDB`.`persoon` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`groep_type_groep`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`groep_type_groep` (
  `groep_id` INT UNSIGNED NOT NULL ,
  `groep_type_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`groep_id`, `groep_type_id`) ,
  INDEX `fk_groep_has_groep_type_groep_type1` (`groep_type_id` ASC) ,
  INDEX `fk_groep_has_groep_type_groep1` (`groep_id` ASC) ,
  CONSTRAINT `fk_groep_has_groep_type_groep1`
    FOREIGN KEY (`groep_id` )
    REFERENCES `CentralAccountDB`.`groep` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_groep_has_groep_type_groep_type1`
    FOREIGN KEY (`groep_type_id` )
    REFERENCES `CentralAccountDB`.`groep_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`taaktype`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`taaktype` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `naam` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`taak`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`taak` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `naam` VARCHAR(100) NULL ,
  `pad_script` VARCHAR(150) NULL ,
  `taaktype_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_taak_taaktype1` (`taaktype_id` ASC) ,
  CONSTRAINT `fk_taak_taaktype1`
    FOREIGN KEY (`taaktype_id` )
    REFERENCES `CentralAccountDB`.`taaktype` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`proces`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`proces` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `naam` VARCHAR(100) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`proces_taak`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`proces_taak` (
  `taak_id` INT UNSIGNED NOT NULL ,
  `proces_id` INT UNSIGNED NOT NULL ,
  `standaard_configuratie` TEXT NULL ,
  PRIMARY KEY (`taak_id`, `proces_id`) ,
  INDEX `fk_functionaliteit_has_functionaliteit_sjabloon_functionalite2` (`proces_id` ASC) ,
  INDEX `fk_functionaliteit_has_functionaliteit_sjabloon_functionalite1` (`taak_id` ASC) ,
  CONSTRAINT `fk_functionaliteit_has_functionaliteit_sjabloon_functionalite1`
    FOREIGN KEY (`taak_id` )
    REFERENCES `CentralAccountDB`.`taak` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_functionaliteit_has_functionaliteit_sjabloon_functionalite2`
    FOREIGN KEY (`proces_id` )
    REFERENCES `CentralAccountDB`.`proces` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`persoon_taak`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`persoon_taak` (
  `persoon_id` INT UNSIGNED NOT NULL ,
  `taak_id` INT UNSIGNED NOT NULL ,
  `configuratie` TEXT NULL ,
  PRIMARY KEY (`persoon_id`, `taak_id`) ,
  INDEX `fk_persoon_has_persoon_taak_persoon_taak1` (`taak_id` ASC) ,
  INDEX `fk_persoon_has_persoon_taak_persoon1` (`persoon_id` ASC) ,
  CONSTRAINT `fk_persoon_has_persoon_taak_persoon1`
    FOREIGN KEY (`persoon_id` )
    REFERENCES `CentralAccountDB`.`persoon` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_persoon_has_persoon_taak_persoon_taak1`
    FOREIGN KEY (`taak_id` )
    REFERENCES `CentralAccountDB`.`taak` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`platform`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`platform` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `naam` VARCHAR(80) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`persoon_taak_platform`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`persoon_taak_platform` (
  `persoon_taak_persoon_id` INT UNSIGNED NOT NULL ,
  `persoon_taak_taak_id` INT UNSIGNED NOT NULL ,
  `platform_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`persoon_taak_persoon_id`, `persoon_taak_taak_id`, `platform_id`) ,
  INDEX `fk_persoon_taak_persoon_has_platform_platform1` (`platform_id` ASC) ,
  INDEX `fk_persoon_taak_persoon_has_platform_persoon_taak_persoon1` (`persoon_taak_persoon_id` ASC, `persoon_taak_taak_id` ASC) ,
  CONSTRAINT `fk_persoon_taak_persoon_has_platform_persoon_taak_persoon1`
    FOREIGN KEY (`persoon_taak_persoon_id` , `persoon_taak_taak_id` )
    REFERENCES `CentralAccountDB`.`persoon_taak` (`persoon_id` , `taak_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_persoon_taak_persoon_has_platform_platform1`
    FOREIGN KEY (`platform_id` )
    REFERENCES `CentralAccountDB`.`platform` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`persoon_taak_rollback`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`persoon_taak_rollback` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `persoon_id` INT UNSIGNED NOT NULL ,
  `taak_id` INT UNSIGNED NOT NULL ,
  `configuratie` TEXT NULL ,
  `gebeurd_op` DATETIME NULL ,
  PRIMARY KEY (`id`, `persoon_id`, `taak_id`) ,
  INDEX `fk_persoon_has_persoon_taak_persoon_taak1` (`taak_id` ASC) ,
  INDEX `fk_persoon_has_persoon_taak_persoon1` (`persoon_id` ASC) ,
  CONSTRAINT `fk_persoon_has_persoon_taak_persoon10`
    FOREIGN KEY (`persoon_id` )
    REFERENCES `CentralAccountDB`.`persoon` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_persoon_has_persoon_taak_persoon_taak10`
    FOREIGN KEY (`taak_id` )
    REFERENCES `CentralAccountDB`.`taak` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`type` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `naam` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`type_has_persoon`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`type_has_persoon` (
  `type_id` INT UNSIGNED NOT NULL ,
  `persoon_id` INT UNSIGNED NOT NULL ,
  `persoon_type` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`type_id`, `persoon_id`, `persoon_type`) ,
  INDEX `fk_type_has_persoon_persoon1` (`persoon_id` ASC, `persoon_type` ASC) ,
  INDEX `fk_type_has_persoon_type1` (`type_id` ASC) ,
  CONSTRAINT `fk_type_has_persoon_type1`
    FOREIGN KEY (`type_id` )
    REFERENCES `CentralAccountDB`.`type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_type_has_persoon_persoon1`
    FOREIGN KEY (`persoon_id` , `persoon_type` )
    REFERENCES `CentralAccountDB`.`persoon` (`id` , `type` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`groep_action`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`groep_action` (
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`groep_taak`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`groep_taak` (
  `groep_id` INT UNSIGNED NOT NULL ,
  `taak_id` INT UNSIGNED NOT NULL ,
  `configuratie` TEXT NULL ,
  PRIMARY KEY (`groep_id`, `taak_id`) ,
  INDEX `fk_groep_has_taak_taak1` (`taak_id` ASC) ,
  INDEX `fk_groep_has_taak_groep1` (`groep_id` ASC) ,
  CONSTRAINT `fk_groep_has_taak_groep1`
    FOREIGN KEY (`groep_id` )
    REFERENCES `CentralAccountDB`.`groep` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_groep_has_taak_taak1`
    FOREIGN KEY (`taak_id` )
    REFERENCES `CentralAccountDB`.`taak` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`platform_groep_taak`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`platform_groep_taak` (
  `platform_id` INT UNSIGNED NOT NULL ,
  `groep_taak_groep_id` INT UNSIGNED NOT NULL ,
  `groep_taak_taak_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`platform_id`, `groep_taak_groep_id`, `groep_taak_taak_id`) ,
  INDEX `fk_platform_has_groep_taak_groep_taak1` (`groep_taak_groep_id` ASC, `groep_taak_taak_id` ASC) ,
  INDEX `fk_platform_has_groep_taak_platform1` (`platform_id` ASC) ,
  CONSTRAINT `fk_platform_has_groep_taak_platform1`
    FOREIGN KEY (`platform_id` )
    REFERENCES `CentralAccountDB`.`platform` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_platform_has_groep_taak_groep_taak1`
    FOREIGN KEY (`groep_taak_groep_id` , `groep_taak_taak_id` )
    REFERENCES `CentralAccountDB`.`groep_taak` (`groep_id` , `taak_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`groep_taak_rollback`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`groep_taak_rollback` (
  `groep_id` INT UNSIGNED NOT NULL ,
  `taak_id` INT UNSIGNED NOT NULL ,
  `configuratie` TEXT NULL ,
  PRIMARY KEY (`groep_id`, `taak_id`) ,
  INDEX `fk_groep_has_taak_taak1` (`taak_id` ASC) ,
  INDEX `fk_groep_has_taak_groep1` (`groep_id` ASC) ,
  CONSTRAINT `fk_groep_has_taak_groep10`
    FOREIGN KEY (`groep_id` )
    REFERENCES `CentralAccountDB`.`groep` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_groep_has_taak_taak10`
    FOREIGN KEY (`taak_id` )
    REFERENCES `CentralAccountDB`.`taak` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CentralAccountDB`.`persoon_revisie`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CentralAccountDB`.`persoon_revisie` (
  `id` INT UNSIGNED NOT NULL ,
  `versie_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `type` INT UNSIGNED NULL ,
  `account_gebruikersnaam` VARCHAR(45) NULL ,
  `account_wachtwoord` VARCHAR(64) NULL ,
  `account_actief` TINYINT(1) NULL ,
  `account_actief_tot` VARCHAR(8) NULL ,
  `account_actief_van` VARCHAR(8) NULL ,
  `startdatum` DATETIME NULL ,
  `voornaam` VARCHAR(45) NOT NULL ,
  `familienaam` VARCHAR(45) NOT NULL ,
  `geslacht` CHAR NULL ,
  `geboortedatum` VARCHAR(8) NULL ,
  `geboorteplaats` VARCHAR(45) NULL ,
  `nationaliteit` VARCHAR(45) NULL ,
  `straat` VARCHAR(50) NULL ,
  `postbus` VARCHAR(40) NULL ,
  `postcode` VARCHAR(4) NULL ,
  `gemeente` VARCHAR(40) NULL ,
  `land` VARCHAR(45) NULL ,
  `email` VARCHAR(50) NULL ,
  `tel` VARCHAR(30) NULL ,
  `tel2` VARCHAR(30) NULL ,
  `gsm` VARCHAR(30) NULL ,
  `gemaakt_op` VARCHAR(8) NULL ,
  `groep_id` INT NULL ,
  `beschrijving` VARCHAR(150) NULL ,
  `actief` TINYINT(1) NULL ,
  `verwijderd` TINYINT(1) NULL ,
  `lln_vorige_school` VARCHAR(50) NULL ,
  `lln_stam_nummer` VARCHAR(30) NULL ,
  `ouder_beroep` VARCHAR(100) NULL ,
  PRIMARY KEY (`id`, `versie_id`) ,
  INDEX `id` (`id` ASC) ,
  CONSTRAINT `id`
    FOREIGN KEY (`id` )
    REFERENCES `CentralAccountDB`.`persoon` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
