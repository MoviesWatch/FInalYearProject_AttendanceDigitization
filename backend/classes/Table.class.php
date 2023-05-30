<?php

class Table
{
    public static function commonRead($filter = [], $columns = [], $query = "")
    {
        $filterColumns = array_keys($filter);
        $filterValues = array_values($filter);
        $tableName = static::$tableName;
        $columns = empty($columns) ? " * " : implode(", ", $columns);
        $query = $query == "" ? "SELECT $columns FROM $tableName" : $query;
        $query = empty($filterColumns)
          ? $query
          : $query . " WHERE " . implode(" = ? AND ", $filterColumns) . " = ? ";
        return Connection::makeQuery(get_called_class(), $query, $filterValues);
    }

    public static function commonCreate($data)
    {
        $columns = array_keys($data);
        $values = array_values($data);
        $tableName = static::$tableName;
        $query = "INSERT INTO $tableName( " . implode(", ", $columns) . " )";
        $valuesSegment = " VALUES( ";
        foreach ($columns as $column) {
            $valuesSegment .= "?, ";
        }
        $query .= rtrim($valuesSegment, ", ") . " )";
        Connection::makeQuery(get_called_class(), $query, $values);

    }

    public static function commonCreateMore($data)
    {
        //begin transaction;
        foreach ($data as $row) {
            self::commonCreate($row);
        }
        //end
    }

    public static function commonUpdate($filter, $update)
    {
        $filterColumns = array_keys($filter);
        $filterValues = array_values($filter);
        $columns = array_keys($update);
        $values = array_values($update);
        $tableName = static::$tableName;
        $query =
          "UPDATE $tableName SET " .
          implode(" = ?, ", $columns) .
          " = ? " .
          " WHERE " .
          implode(" = ? AND", $filterColumns) .
          " = ?";
        Connection::makeQuery(
            get_called_class(),
            $query,
            array_merge($values, $filterValues)
        );
    }

    public static function commonDelete($filter, $query = "")
    {
        $filterColumns = array_keys($filter);
        $filterValues = array_values($filter);
        $tableName = static::$tableName;
        $query = $query == "" ? "DELETE FROM $tableName" : $query;
        $query .= " WHERE ";
        foreach ($filterColumns as $filterColumn) {
            $query .= " $filterColumn  = ? AND ";
        }
        $query = rtrim($query, "AND ");
        Connection::makeQuery(get_called_class(), $query, $filterValues);
    }

}
