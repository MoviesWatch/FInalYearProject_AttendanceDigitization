<?php

class OdDetail extends Table
{
    protected static $tableName = "odDetails";
    public static function read($filters = [])
    {
        $query = "SELECT * FROM odDetails 
      INNER JOIN odRequests
      ON odDetails.odID = odRequests.odID
      INNER JOIN students
      ON students.regNo = odDetails.regNo
      INNER JOIN classes 
      ON classes.classID = odRequests.classID";
        return self::commonRead($filters, [], $query);
    }
}
