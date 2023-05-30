<?php

class AcademicSemesterDB
{
    public static function create($databaseName)
    {
        $recentDatabase = null;
        $temp = AcademicSemester::getRecentDatabase();
        if (count($temp) > 0) {
            $recentDatabase = $temp[0]->databaseName;
        }

        $createQuery = "-- MySQL Workbench Forward Engineering

        SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
        SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
        SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
        
        -- -----------------------------------------------------
        -- Schema $databaseName
        -- -----------------------------------------------------
        
        -- -----------------------------------------------------
        -- Schema $databaseName
        -- -----------------------------------------------------
        CREATE SCHEMA IF NOT EXISTS `$databaseName` DEFAULT CHARACTER SET utf8 ;
        USE `$databaseName` ;
        
        -- -----------------------------------------------------
        -- Table `$databaseName`.`departments`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `$databaseName`.`departments` (
          `departmentCode` VARCHAR(10) NOT NULL,
          `department` VARCHAR(255) NOT NULL,
          PRIMARY KEY (`departmentCode`))
        ENGINE = InnoDB;
        
        
        -- -----------------------------------------------------
        -- Table `$databaseName`.`semesters`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `$databaseName`.`semesters` (
          `semester` INT NOT NULL,
          PRIMARY KEY (`semester`))
        ENGINE = InnoDB;
        
        
        -- -----------------------------------------------------
        -- Table `$databaseName`.`classes`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `$databaseName`.`classes` (
          `departmentCode` VARCHAR(10) NOT NULL,
          `semester` INT NOT NULL,
          `classID` INT NOT NULL AUTO_INCREMENT,
          INDEX `fk_classes_semesters1_idx` (`semester` ASC) VISIBLE,
          PRIMARY KEY (`classID`),
          INDEX `fk_classes_departments_idx` (`departmentCode` ASC) VISIBLE,
          CONSTRAINT `fk_classes_departments`
            FOREIGN KEY (`departmentCode`)
            REFERENCES `$databaseName`.`departments` (`departmentCode`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
          CONSTRAINT `fk_classes_semesters1`
            FOREIGN KEY (`semester`)
            REFERENCES `$databaseName`.`semesters` (`semester`)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT)
        ENGINE = InnoDB;
        
        
        -- -----------------------------------------------------
        -- Table `$databaseName`.`students`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `$databaseName`.`students` (
          `regNo` VARCHAR(25) NOT NULL,
          `dateOfBirth` DATE NOT NULL,
          `classID` INT NOT NULL,
          `name` VARCHAR(45) NOT NULL,
          `gender` CHAR(1) NOT NULL,
          `mobile` CHAR(10) NULL,
          `email` VARCHAR(45) NULL,
          `parentName` VARCHAR(45) NULL,
          `parentMobile` CHAR(10) NULL,
          `dateJoined` DATE NOT NULL,
          `inactive` DATE NULL,
          PRIMARY KEY (`regNo`),
          INDEX `fk_students_classes1_idx` (`classID` ASC) VISIBLE,
          CONSTRAINT `fk_students_classes1`
            FOREIGN KEY (`classID`)
            REFERENCES `$databaseName`.`classes` (`classID`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION)
        ENGINE = InnoDB;
        
        
        -- -----------------------------------------------------
        -- Table `$databaseName`.`subjects`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `$databaseName`.`subjects` (
          `subjectCode` VARCHAR(10) NOT NULL,
          `subject` VARCHAR(255) NOT NULL,
          `subjectAcronym` VARCHAR(10) NOT NULL,
          PRIMARY KEY (`subjectCode`),
          UNIQUE INDEX `subjectAcronym_UNIQUE` (`subjectAcronym` ASC) VISIBLE)
        ENGINE = InnoDB;
        
        
        -- -----------------------------------------------------
        -- Table `$databaseName`.`subjectTypes`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `$databaseName`.`subjectTypes` (
          `typeID` INT NOT NULL,
          `type` VARCHAR(25) NOT NULL,
          PRIMARY KEY (`typeID`))
        ENGINE = InnoDB;
        
        
        -- -----------------------------------------------------
        -- Table `$databaseName`.`classesAndSubjects`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `$databaseName`.`classesAndSubjects` (
          `classSubjectID` INT NOT NULL AUTO_INCREMENT,
          `subjectCode` VARCHAR(10) NOT NULL,
          `classID` INT NOT NULL,
          `typeID` INT NOT NULL,
          `batch` INT NULL,
          PRIMARY KEY (`classSubjectID`),
          INDEX `fk_classesAndSubjects_classes1_idx` (`classID` ASC) VISIBLE,
          INDEX `fk_classesAndSubjects_subjectTypes1_idx` (`typeID` ASC) VISIBLE,
          INDEX `fk_classesAndSubjects_subjects1_idx` (`subjectCode` ASC) VISIBLE,
          CONSTRAINT `fk_classesAndSubjects_subjects1`
            FOREIGN KEY (`subjectCode`)
            REFERENCES `$databaseName`.`subjects` (`subjectCode`)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT,
          CONSTRAINT `fk_classesAndSubjects_classes1`
            FOREIGN KEY (`classID`)
            REFERENCES `$databaseName`.`classes` (`classID`)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT,
          CONSTRAINT `fk_classesAndSubjects_subjectTypes1`
            FOREIGN KEY (`typeID`)
            REFERENCES `$databaseName`.`subjectTypes` (`typeID`)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT)
        ENGINE = InnoDB;
        
        
        -- -----------------------------------------------------
        -- Table `$databaseName`.`facultyAccesses`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `$databaseName`.`facultyAccesses` (
          `accessID` INT NOT NULL,
          `access` VARCHAR(45) NOT NULL,
          PRIMARY KEY (`accessID`))
        ENGINE = InnoDB;
        
        
        -- -----------------------------------------------------
        -- Table `$databaseName`.`faculties`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `$databaseName`.`faculties` (
          `facultyID` VARCHAR(25) NOT NULL,
          `departmentCode` VARCHAR(10) NULL,
          `classID` INT NULL,
          `accessID` INT NOT NULL,
          `name` VARCHAR(45) NOT NULL,
          `mobile` CHAR(10) NULL,
          `email` VARCHAR(45) NULL,
          `inactive` DATE NULL,
          PRIMARY KEY (`facultyID`),
          INDEX `fk_faculties_classes1_idx` (`classID` ASC) VISIBLE,
          INDEX `fk_faculties_facultyAccesses1_idx` (`accessID` ASC) VISIBLE,
          INDEX `fk_faculties_departments1_idx` (`departmentCode` ASC) VISIBLE,
          CONSTRAINT `fk_faculties_departments1`
            FOREIGN KEY (`departmentCode`)
            REFERENCES `$databaseName`.`departments` (`departmentCode`)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT,
          CONSTRAINT `fk_faculties_classes1`
            FOREIGN KEY (`classID`)
            REFERENCES `$databaseName`.`classes` (`classID`)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT,
          CONSTRAINT `fk_faculties_facultyAccesses1`
            FOREIGN KEY (`accessID`)
            REFERENCES `$databaseName`.`facultyAccesses` (`accessID`)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT)
        ENGINE = InnoDB;
        
        
        -- -----------------------------------------------------
        -- Table `$databaseName`.`workingDays`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `$databaseName`.`workingDays` (
          `date` DATE NOT NULL,
          `day` INT NOT NULL,
          `classID` INT NOT NULL,
          PRIMARY KEY (`date`, `classID`),
          INDEX `fk_workingDays_classes1_idx` (`classID` ASC) VISIBLE,
          CONSTRAINT `fk_workingDays_classes1`
            FOREIGN KEY (`classID`)
            REFERENCES `$databaseName`.`classes` (`classID`)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT)
        ENGINE = InnoDB;
        
        
        -- -----------------------------------------------------
        -- Table `$databaseName`.`timetable`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `$databaseName`.`timetable` (
          `classSubjectID` INT NOT NULL,
          `day` INT NOT NULL,
          `hour` INT NOT NULL,
          `periodID` INT NOT NULL AUTO_INCREMENT,
          INDEX `fk_timetable_classesAndSubjects1_idx` (`classSubjectID` ASC) VISIBLE,
          PRIMARY KEY (`periodID`),
          CONSTRAINT `fk_timetable_classesAndSubjects1`
            FOREIGN KEY (`classSubjectID`)
            REFERENCES `$databaseName`.`classesAndSubjects` (`classSubjectID`)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT)
        ENGINE = InnoDB;
        
        
        -- -----------------------------------------------------
        -- Table `$databaseName`.`absentees`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `$databaseName`.`absentees` (
          `date` DATE NOT NULL,
          `periodID` INT NOT NULL,
          `regNo` VARCHAR(25) NOT NULL,
          INDEX `fk_absentees_timetable1_idx` (`periodID` ASC) VISIBLE,
          INDEX `fk_absentees_students1_idx` (`regNo` ASC) VISIBLE,
          PRIMARY KEY (`date`, `periodID`, `regNo`),
          CONSTRAINT `fk_absentees_workingDays1`
            FOREIGN KEY (`date`)
            REFERENCES `$databaseName`.`workingDays` (`date`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
          CONSTRAINT `fk_absentees_timetable1`
            FOREIGN KEY (`periodID`)
            REFERENCES `$databaseName`.`timetable` (`periodID`)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT,
          CONSTRAINT `fk_absentees_students1`
            FOREIGN KEY (`regNo`)
            REFERENCES `$databaseName`.`students` (`regNo`)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT)
        ENGINE = InnoDB;
        
        
        -- -----------------------------------------------------
        -- Table `$databaseName`.`odStatuses`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `$databaseName`.`odStatuses` (
          `statusID` INT NOT NULL AUTO_INCREMENT,
          `status` VARCHAR(25) NOT NULL,
          PRIMARY KEY (`statusID`))
        ENGINE = InnoDB;
        
        
        -- -----------------------------------------------------
        -- Table `$databaseName`.`odRequests`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `$databaseName`.`odRequests` (
          `attachment` VARCHAR(255) NOT NULL,
          `odID` VARCHAR(255) NOT NULL,
          `statusID` INT NOT NULL,
          `classID` INT NOT NULL,
          PRIMARY KEY (`odID`),
          INDEX `fk_odRequests_odStatuses1_idx` (`statusID` ASC) VISIBLE,
          INDEX `fk_odRequests_classes1_idx` (`classID` ASC) VISIBLE,
          CONSTRAINT `fk_odRequests_odStatuses1`
            FOREIGN KEY (`statusID`)
            REFERENCES `$databaseName`.`odStatuses` (`statusID`)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT,
          CONSTRAINT `fk_odRequests_classes1`
            FOREIGN KEY (`classID`)
            REFERENCES `$databaseName`.`classes` (`classID`)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT)
        ENGINE = InnoDB;
        
        
        -- -----------------------------------------------------
        -- Table `$databaseName`.`odDetails`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `$databaseName`.`odDetails` (
          `date` DATE NOT NULL,
          `regNo` VARCHAR(25) NOT NULL,
          `odID` VARCHAR(255) NOT NULL,
          `hour` VARCHAR(45) NOT NULL,
          INDEX `fk_odRequests_workingDays1_idx` (`date` ASC) VISIBLE,
          INDEX `fk_odRequests_students1_idx` (`regNo` ASC) VISIBLE,
          INDEX `fk_odDetails_odRequests1_idx` (`odID` ASC) VISIBLE,
          PRIMARY KEY (`date`, `regNo`, `hour`),
          CONSTRAINT `fk_odRequests_workingDays1`
            FOREIGN KEY (`date`)
            REFERENCES `$databaseName`.`workingDays` (`date`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
          CONSTRAINT `fk_odRequests_students1`
            FOREIGN KEY (`regNo`)
            REFERENCES `$databaseName`.`students` (`regNo`)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT,
          CONSTRAINT `fk_odDetails_odRequests1`
            FOREIGN KEY (`odID`)
            REFERENCES `$databaseName`.`odRequests` (`odID`)
            ON DELETE CASCADE
            ON UPDATE CASCADE)
        ENGINE = InnoDB;
        
        
        -- -----------------------------------------------------
        -- Table `$databaseName`.`studentsAndSubjects`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `$databaseName`.`studentsAndSubjects` (
          `classSubjectID` INT NOT NULL,
          `regNo` VARCHAR(25) NOT NULL,
          INDEX `fk_studentsAndSubjects_classesAndSubjects1_idx` (`classSubjectID` ASC) VISIBLE,
          INDEX `fk_studentsAndSubjects_students1_idx` (`regNo` ASC) VISIBLE,
          PRIMARY KEY (`classSubjectID`, `regNo`),
          CONSTRAINT `fk_studentsAndSubjects_classesAndSubjects1`
            FOREIGN KEY (`classSubjectID`)
            REFERENCES `$databaseName`.`classesAndSubjects` (`classSubjectID`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
          CONSTRAINT `fk_studentsAndSubjects_students1`
            FOREIGN KEY (`regNo`)
            REFERENCES `$databaseName`.`students` (`regNo`)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT)
        ENGINE = InnoDB;
        
        
        -- -----------------------------------------------------
        -- Table `$databaseName`.`facultiesAndSubjects`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `$databaseName`.`facultiesAndSubjects` (
          `facultyID` VARCHAR(25) NOT NULL,
          `classSubjectID` INT NOT NULL,
          INDEX `fk_facultiesAndSubjects_faculties1_idx` (`facultyID` ASC) VISIBLE,
          INDEX `fk_facultiesAndSubjects_classesAndSubjects1_idx` (`classSubjectID` ASC) VISIBLE,
          PRIMARY KEY (`facultyID`, `classSubjectID`),
          CONSTRAINT `fk_facultiesAndSubjects_faculties1`
            FOREIGN KEY (`facultyID`)
            REFERENCES `$databaseName`.`faculties` (`facultyID`)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT,
          CONSTRAINT `fk_facultiesAndSubjects_classesAndSubjects1`
            FOREIGN KEY (`classSubjectID`)
            REFERENCES `$databaseName`.`classesAndSubjects` (`classSubjectID`)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT)
        ENGINE = InnoDB;
        
        
        -- -----------------------------------------------------
        -- Table `$databaseName`.`studentCredentials`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `$databaseName`.`studentCredentials` (
          `password` VARCHAR(100) NOT NULL,
          `regNo` VARCHAR(25) NOT NULL,
          INDEX `fk_studentCredentials_students1_idx` (`regNo` ASC) VISIBLE,
          CONSTRAINT `fk_studentCredentials_students1`
            FOREIGN KEY (`regNo`)
            REFERENCES `$databaseName`.`students` (`regNo`)
            ON DELETE CASCADE
            ON UPDATE CASCADE)
        ENGINE = InnoDB;
        
        
        -- -----------------------------------------------------
        -- Table `$databaseName`.`facultyCredentials`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `$databaseName`.`facultyCredentials` (
          `password` VARCHAR(100) NOT NULL,
          `facultyID` VARCHAR(25) NOT NULL,
          PRIMARY KEY (`facultyID`),
          CONSTRAINT `fk_staff_credentials_faculties1`
            FOREIGN KEY (`facultyID`)
            REFERENCES `$databaseName`.`faculties` (`facultyID`)
            ON DELETE CASCADE
            ON UPDATE CASCADE)
        ENGINE = InnoDB;
        
        
        -- -----------------------------------------------------
        -- Table `$databaseName`.`activePeriods`
        -- -----------------------------------------------------
        CREATE TABLE IF NOT EXISTS `$databaseName`.`activePeriods` (
          `date` DATE NOT NULL,
          `periodID` INT NOT NULL,
          INDEX `fk_activePeriods_workingDays1_idx` (`date` ASC) VISIBLE,
          INDEX `fk_activePeriods_timetable1_idx` (`periodID` ASC) VISIBLE,
          PRIMARY KEY (`date`, `periodID`),
          CONSTRAINT `fk_activePeriods_workingDays1`
            FOREIGN KEY (`date`)
            REFERENCES `$databaseName`.`workingDays` (`date`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE,
          CONSTRAINT `fk_activePeriods_timetable1`
            FOREIGN KEY (`periodID`)
            REFERENCES `$databaseName`.`timetable` (`periodID`)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT)
        ENGINE = InnoDB;
        
        
        SET SQL_MODE=@OLD_SQL_MODE;
        SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
        SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
            
        
        INSERT INTO odStatuses(statusID, status) VALUES('1','Pending'),
        ('2', 'Approved'),
        ('3', 'Rejected');

        INSERT INTO facultyAccesses(accessID,access) 
        VALUES ('1','Faculty'),
        ('2','Faculty Advisor'),
        ('3','Head Of the Department(S&H)'),
        ('4','Head Of the Department'),
        ('5','Principal');

        INSERT INTO semesters(semester) 
        VALUES ('1'),('2'),('3'),('4'),('5'),('6'),('7'),('8');

        INSERT INTO subjectTypes(typeID, type) VALUES('1', 'Core'),
        ('2', 'Open elective'),
        ('3', 'Professional elective A'),
        ('4', 'Professional elective B'),
        ('4', 'Professional elective B');
";
        $copyQuery = "
INSERT INTO departments(SELECT * FROM `$recentDatabase`.departments);

INSERT INTO classes(SELECT * FROM `$recentDatabase`.classes);

INSERT INTO subjects(SELECT * FROM `$recentDatabase`.subjects);

INSERT INTO students (
  SELECT * FROM `$recentDatabase`.students 
  WHERE inactive IS NULL AND (classID mod 8) != 0 );
  
  UPDATE students SET classID = classID + 1;
  
  INSERT INTO studentCredentials (regNo,password)
  SELECT `$recentDatabase`.studentCredentials.regNo, `$recentDatabase`.studentCredentials.password 
  FROM `$recentDatabase`.studentCredentials INNER JOIN students 
  ON students.regNo = `$recentDatabase`.studentCredentials.regNo;
  INSERT INTO faculties (SELECT * FROM `$recentDatabase`.faculties );

INSERT INTO facultyCredentials (SELECT * FROM `$recentDatabase`.facultyCredentials);

UPDATE faculties SET classID = NULL WHERE (classID mod 8) = 0;

UPDATE faculties SET classID = classID + 1 WHERE classID IS NOT NULL;
";
        $createFacultyQuery = "
INSERT INTO faculties(facultyID,name,accessID) VALUES('0000','Principal','5');

INSERT INTO facultyCredentials(facultyID,password) VALUES('0000','$2y$10\$UJo3tHNhrUyJ6WnNfQ8diuK3VJu3oJt/weL1e5JCWI0Wjh.SJy0qK');
";
        Connection::makeQuery(get_called_class(), $createQuery);
        new Connection($databaseName);
        if ($recentDatabase) {
            Connection::makeQuery(get_called_class(), $copyQuery);
        } else {
            Connection::makeQuery(get_called_class(), $createFacultyQuery);
        }
        new Connection($_SESSION["db"]);
    }

    public static function delete($databaseName)
    {
        $query = "DROP DATABASE `$databaseName`";
        Connection::makeQuery(get_called_class(), $query);
    }
}
