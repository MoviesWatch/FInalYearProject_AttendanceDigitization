<?php

class Faculty extends Table
{
    protected static $tableName = "faculties";
    public static function read($filters = [], $activeOnly= false)
    {
        $query ="SELECT * FROM faculties 
        INNER JOIN facultyAccesses 
        ON facultyAccesses.accessID = faculties.accessID";
        $faculties = self::commonRead([], [], $query);
        if($activeOnly) {
            $faculties = array_filter($faculties, function ($faculty) {
                return $faculty->inactive == null;
            });
        }
        $classes = _Class::commonRead();
        foreach ($faculties as $faculty) {
            if ($faculty->classID != null) {
                $founded = array_values(
                    array_filter($classes, function ($class) use ($faculty) {
                        return $faculty->classID == $class->classID;
                    })
                )[0];
                $faculty->departmentCode = $founded->departmentCode;
                $faculty->semester = $founded->semester;
            }
        }
        return $faculties;
    }

    public static function create($faculty)
    {
        self::commonCreate($faculty);
        FacultyCredential::create($faculty["facultyID"], $faculty["facultyID"]);
        return "Added successfully";
    }
}
