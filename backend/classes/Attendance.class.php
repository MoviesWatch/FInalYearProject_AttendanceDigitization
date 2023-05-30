<?php
class Attendance
{
  protected static $tableName = "";

  protected static function processBulkAttendance(
    $activePeriods,
    $studentsAndSubjects,
    $absentees,
    $ods
  ) {
    $attendanceData = [];
    foreach ($activePeriods as $activePeriod) {
      foreach ($studentsAndSubjects as $studentAndSubject) {
        if (
          $studentAndSubject->classSubjectID != $activePeriod->classSubjectID ||
          $studentAndSubject->inactive >= $activePeriod->date
        ) {
          continue;
        }
        $isAbsent = array_filter($absentees, function ($absentee) use (
          $activePeriod,
          $studentAndSubject
        ) {
          return $activePeriod->hour == $absentee->hour &&
            $activePeriod->date == $absentee->date &&
            $studentAndSubject->regNo == $absentee->regNo;
        });
        $isOd = array_filter($ods, function ($od) use (
          $activePeriod,
          $studentAndSubject
        ) {
          return $activePeriod->hour == $od->hour &&
            $activePeriod->date == $od->date &&
            $studentAndSubject->regNo == $od->regNo;
        });
        $attendance = new Attendance();
        $attendance->regNo = $studentAndSubject->regNo;
        $attendance->name = $studentAndSubject->name;
        $attendance->periodID = $activePeriod->periodID;
        $attendance->classSubjectID = $activePeriod->classSubjectID;
        $attendance->classID = $activePeriod->classID;
        $attendance->subjectAcronym = $activePeriod->subjectAcronym;
        $attendance->subjectCode = $activePeriod->subjectCode;
        $attendance->day = $activePeriod->day;
        $attendance->date = $activePeriod->date;
        $attendance->hour = $activePeriod->hour;
        $attendance->attended = count($isAbsent) <= 0 && count($isOd) <= 0;
        $attendance->isOd = count($isOd) > 0;
        $attendanceData[] = $attendance;
      }
    }
    return $attendanceData;
  }

  public static function readWithClassID($classID)
  {
    $studentsAndSubjects = StudentAndSubject::read([
      "students.classID" => $classID,
    ]);
    $students = Student::read(["classes.classID" => $classID, ["regNo"]]);
    $subjects = ClassAndSubject::read(["classes.classID" => $classID]);
    $absentees = Absentee::read(["classes.classID" => $classID]);
    // $activePeriods = activePeriod::read([
    //   "WorkingDays.classID" => $classID,
    // ]);
    $activePeriods = ActivePeriod::read([
      "classesAndSubjects.classID" => $classID,
    ]);
    $ods = OdDetail::read(["odRequests.classID" => $classID, "statusID" => 2]);
    return [
      "students" => $students,
      "subjects" => $subjects,
      "attendances" => self::processBulkAttendance(
        $activePeriods,
        $studentsAndSubjects,
        $absentees,
        $ods
      ),
    ];
  }

  public static function readWithClassSubjectID($classSubjectID)
  {
    $studentsAndSubjects = StudentAndSubject::read([
      "studentsAndSubjects.classSubjectID" => $classSubjectID,
    ]);
    $absentees = Absentee::read([
      "timetable.classSubjectID" => $classSubjectID,
    ]);
    // $activePeriods = activePeriod::read([
    //   "classSubjectID" => $classSubjectID,
    // ]);
    $activePeriods = ActivePeriod::read([
      "classesAndSubjects.classSubjectID" => $classSubjectID,
    ]);
    // echo json_encode($activePeriods);
    $ods = OdDetail::read([
      "statusID" => 2,
    ]);
    return [
      "students" => $studentsAndSubjects,
      "attendances" => self::processBulkAttendance(
        $activePeriods,
        $studentsAndSubjects,
        $absentees,
        $ods
      ),
    ];
  }

  public static function readWithRegNo($regNo, $classID = 0)
  {
    $student = Student::read(["regNo" => $regNo], ["classID", "inactive"])[0];
    if (!$classID) {
      $classID = $student->classID;
    }

    $subjects = StudentAndSubject::read([
      "students.regNo" => $regNo,
    ]);
    $absentees = Absentee::read(["regNo" => $regNo]);
    // $activePeriods = activePeriod::read([
    //   "workingDays.classID" => $classID,
    // ]);
    $activePeriods = ActivePeriod::read([
      "studentsAndSubjects.regNo" => $regNo,
    ]);
    $ods = OdDetail::read(["odDetails.regNo" => $regNo, "statusID" => 2]);
    $attendanceData = [];
    foreach ($activePeriods as $activePeriod) {
      if ($student->inactive >= $activePeriod->date) {
        continue;
      }
      $subject = array_filter($subjects, function ($subject) use (
        $activePeriod
      ) {
        return $activePeriod->classSubjectID == $subject->classSubjectID;
      });
      if (count($subject) <= 0) {
        continue;
      }
      $isAbsent = array_filter($absentees, function ($absentee) use (
        $activePeriod
      ) {
        return $activePeriod->hour == $absentee->hour &&
          $activePeriod->date == $absentee->date;
      });
      $isOd = array_filter($ods, function ($od) use ($activePeriod) {
        return $activePeriod->hour == $od->hour &&
          $activePeriod->date == $od->date;
      });
      $attendance = new Attendance();
      $attendance->periodID = $activePeriod->periodID;
      $attendance->classSubjectID = $activePeriod->classSubjectID;
      $attendance->classID = $activePeriod->classID;
      $attendance->subjectAcronym = $activePeriod->subjectAcronym;
      $attendance->subjectCode = $activePeriod->subjectCode;
      $attendance->day = $activePeriod->day;
      $attendance->date = $activePeriod->date;
      $attendance->hour = $activePeriod->hour;
      $attendance->attended = count($isAbsent) <= 0 && count($isOd) <= 0;
      $attendance->isOd = count($isOd) > 0;
      $attendanceData[] = $attendance;
    }
    return ["attendance" => $attendanceData, "subjects" => $subjects];
  }
}
