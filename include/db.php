<?php
  require_once 'include/constants.php';

  class Database {
    const SERVER_ADDR = 'localhost';
    const USER = 'root';
    const PASSWORD = '';
    const DATABASE = 'url_shortener';

    private $mysql;

    private $numOfUrlsStmt;
    private $getByIdStmt;
    private $getByUrlStmt;
    private $addUrlStmt;

    function __construct() {
      $this->mysql = new mysqli(self::SERVER_ADDR, self::USER, self::PASSWORD, self::DATABASE);
      
      // Verify connection success
      if ($this->mysql->connect_error) {
        self::exitWithErrorExplicit('MySQL connection failed', $this->mysql->connect_error, $this->mysql->connect_errno);
      }

      // Prepare statements
      $this->numOfUrlsStmt = $this->prepareStatement('SELECT COUNT(*) FROM urls');
      $this->getByIdStmt   = $this->prepareStatement('SELECT id, url FROM urls WHERE id = ?');
      $this->getByUrlStmt  = $this->prepareStatement('SELECT id, url FROM urls WHERE url = ?');
      $this->addUrlStmt    = $this->prepareStatement('INSERT INTO urls (id, url) VALUES (?, ?)');
    }

    function __destruct() {
      $this->addUrlStmt->close();
      $this->getByUrlStmt->close();
      $this->getByIdStmt->close();
      $this->numOfUrlsStmt->close();
      $this->mysql->close();
    }

    function getNumberOfUrls() {
      if (!$this->numOfUrlsStmt->execute()) {
        self::exitWithError('Statement execution failed', $this->numOfUrlsStmt);
      }
      return $this->numOfUrlsStmt->get_result()->fetch_row()[0];
    }

    function getRowById($id) {
      if (!$this->getByIdStmt->bind_param('s', $id)) {
        self::exitWithError('Binding parameters failed', $this->getByIdStmt);
      }

      if (!$this->getByIdStmt->execute()) {
        self::exitWithError('Statement execution failed', $this->getByIdStmt);
      }

      return $this->getByIdStmt->get_result();
    }

    function getRowByUrl($url) {
      if (!$this->getByUrlStmt->bind_param('s', $url)) {
        self::exitWithError('Binding parameters failed', $this->getByUrlStmt);
      }

      if (!$this->getByUrlStmt->execute()) {
        self::exitWithError('Statement execution failed', $this->getByUrlStmt);
      }

      return $this->getByUrlStmt->get_result();
    }

    function addUrl($id, $url) {
      if (!$this->addUrlStmt->bind_param('ss', $id, $url)) {
        self::exitWithError('Binding parameters failed', $this->addUrlStmt);
      }

      if (!$this->addUrlStmt->execute()) {
        self::exitWithError('Statement execution failed', $this->addUrlStmt);
      }
    }

    private function prepareStatement($sqlQuery) {
      $stmt = $this->mysql->prepare($sqlQuery);
      if (!$stmt) {
        self::exitWithError('Prepare statement failed', $this->mysql->error, $this->mysql->errno);
      }

      return $stmt;
    }

    private static function exitWithError($message, $object) {
      self::exitWithErrorExplicit($message, $object->error, $object->errno);
    }

    private static function exitWithErrorExplicit($message, $error, $errno) {
      http_response_code(HTTP_SERVER_ERROR);
      die("$message: $error ($errno)");
    }
  }

  $db = new Database();
?>
