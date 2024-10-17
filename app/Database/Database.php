<?php

namespace App\Database;

use PDO;
use PDOException;

class Database
{
  protected $table;

  /**
   * @var \PDO
   */
  protected PDO $connection;

  private static $instance = null;

  private function __clone() {}

  private function __construct() {}

  public function setConnection() {}

  public static function getInstance()
  {
    if (self::$instance === null) {
      try {
        self::$instance = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
        self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
        die('ERROR CATCH' . $e->getmessage());
      }
    }

    return self::$instance;
  }
}
