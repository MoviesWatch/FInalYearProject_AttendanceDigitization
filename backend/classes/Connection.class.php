<?php

class Connection
{
    public static $connection;

    public function __construct(
        $database,
    ) {
        try {
            self::$connection = new PDO(
                "mysql:host=".Database::$host.";dbname=".$database.";charset=UTF8",
                Database::$user,
                Database::$password
            );
        } catch (PDOException $e) {
            switch ($e->getCode()) {
                case 1045:
                    echo json_encode(CustomException::internalConnectionError());
                    exit();
                case 1049:
                    echo json_encode(CustomException::invalidAcademicSemester());
                    exit();
            }
            exit();
        }
    }

    public static function makeQuery($class, $query, $values = [])
    {
        if (self::$connection === null) {
            new Connection($_SESSION["db"]);
        }
        try {
            $statement = self::$connection->prepare($query);
            $statement->execute($values);
            $statement->setFetchMode(PDO::FETCH_CLASS, $class);
            return $statement->fetchAll();
        } catch (PDOException $e) {
            switch ($e->errorInfo[1]) {
                case 1062:
                    echo json_encode(CustomException::dataAlreadyIn());
                    exit();
                case 1452:
                    echo json_encode(CustomException::cantPerform());
                    exit();
                default:
                    echo json_encode(CustomException::cantPerform());
                    exit();
            }
        }
    }

    public function lastInsertedID()
    {
        return self::$connection->lastInsertId();
    }
}
