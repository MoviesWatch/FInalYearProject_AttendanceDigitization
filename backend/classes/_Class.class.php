<?php

class _Class extends Table
{
    protected static $tableName = "classes";
    public static function createMore($departmentCode)
    {
        $count = Connection::makeQuery(get_called_class(), "SELECT COUNT(classID) as count FROM classes")[0]->count;
        foreach([1,2,3,4,5,6,7,8] as $semester) {
            self::commonCreate(["classID"=>$count+$semester,"departmentCode"=>$departmentCode,"semester"=>$semester]);
        }
    }
}
