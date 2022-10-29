<?php

class Database
{
  private static $dbHost = "127.0.0.1:3306";
  private static $dbName = "u786407711_fitness_spirit";
  private static $dbUser = "u786407711_root";
  private static $dbUserPassword = "E[k2B5/>^81u";
  private static $connection = null;

  public static function connect()
  {
    try {
      self::$connection = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName, self::$dbUser, self::$dbUserPassword);
    } catch (PDOException $e) {
      die($e->getMessage());
    }
    return self::$connection;
  }

  public static function disconnect()
  {
    self::$connection = null;
  }
}
