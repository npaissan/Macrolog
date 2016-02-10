<?php

class Database
    {
    private static $dbHandler = null;

    public static function setHandler()
        {
        if (Database::$dbHandler != null)
            return Database::$dbHandler;

        try
            {
            
            Database::$dbHandler = Database::getServerPDO();

            if (true)
                Database::$dbHandler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        catch (PDOException $exception)
            {
            Database::handleError($exception);
            }
        }

    public static function tell($query, $values)
        {
        try
            {
            $statement = Database::$dbHandler->prepare($query);
            $statement->execute($values);

            return true;
            }
        catch (PDOException $exception)
            {
            Database::handleError($exception);
            }
        }

    public static function ask($query, $values)
        {
        try
            {
            $statement = Database::$dbHandler->prepare($query);
            $statement->execute($values);

            return $statement->fetchAll();
            }
        catch (PDOException $exception)
            {
            Database::handleError($exception);
            }
        }

    private static function getServerPDO()
        {
        return new PDO("sqlite:macro.db");
        }

    private static function handleError($exception)
        {
        if (true)
            echo $exception->getMessage();

        //quit("500 Internal Server Error");
        }
    }

Database::setHandler();

?>