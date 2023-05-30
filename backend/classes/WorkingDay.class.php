<?php

class WorkingDay extends Table
{
    protected static $tableName = "workingDays";

    public static function read($filter=[])
    {
        $query = "SELECT * FROM workingDays INNER JOIN classes ON classes.classID = workingDays.classID";
        return self::commonRead($filter, [], $query);
    }
}
