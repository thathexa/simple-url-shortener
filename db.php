<?php
  class Database {
    const SERVER_ADDR = 'localhost';
    const USER = 'root';
    const PASSWORD = '';
    const DATABASE = 'url_shortener';

    private $mysql;

    function __construct() {
      $this->mysql = new mysqli(self::SERVER_ADDR, self::USER, self::PASSWORD, self::DATABASE);
      
      // Verify connection success
      if ($this->mysql->connect_error) {
        die('MySQL connection failed: ' . $this->mysql->connect_error);
      }

      // Verify database was found
      $result = $this->mysql->query('select database()');
      if (!$result or $result->fetch_row()[0] !== self::DATABASE) {
        die('Failed to select database "' . DATABASE . '"');
      }
    }

    function __destruct() {
      $this->mysql->close();
    }
    
    function query($sqlQuery) {
      return $this->mysql->query($sqlQuery);
    }
  }

  $db = new Database();
?>
