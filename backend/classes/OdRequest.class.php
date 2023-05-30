<?php

class OdRequest extends Table
{
    protected static $tableName = "odRequests";

    public static function read($filters = [])
    {
        $query = "SELECT * FROM odRequests 
    INNER JOIN classes 
    ON classes.classID = odRequests.classID";
        return self::commonRead($filters, [], $query);
    }

    public static function readForHOD($departmentCode)
    {
        $query = "SELECT * FROM odRequests 
    INNER JOIN classes 
    ON classes.classID = odRequests.classID
    WHERE classes.departmentCode = ? AND classes.semester > 2";
        return Connection::makeQuery(get_called_class(), $query, [$departmentCode]);
    }

    public static function readForSHHOD()
    {
        $query = "SELECT * FROM odRequests 
    INNER JOIN classes 
    ON classes.classID = odRequests.classID
    WHERE classes.semester <= 2";
        return Connection::makeQuery(get_called_class(), $query, []);
    }

    public static function update($filters, $update)
    {
        if ($update["statusID"] == "2") {
            foreach (OdDetail::read($filters) as $odDetail) {
                Absentee::commonDelete([
                  "regNo" => $odDetail->regNo,
                  "date" => $odDetail->date,
                  "periodID" => $odDetail->periodID,
                ]);
            }
        }
        return self::commonUpdate($filters, $update);
    }
}
