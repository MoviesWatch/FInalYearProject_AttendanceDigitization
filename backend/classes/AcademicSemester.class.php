<?php

class AcademicSemester extends Table
{
    protected static $tableName = "academicSemesters";
    public static function getRecentDatabase()
    {
        $query = "SELECT databaseName 
      FROM academicSemesters.academicSemesters 
      WHERE id = (SELECT MAX(id) FROM academicSemesters.academicSemesters)";
        return Connection::makeQuery(get_called_class(), $query, []);
    }
}
