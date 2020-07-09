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
        $this->exitWithError('MySQL connection failed', $this->mysql->connect_error, $this->mysql->connect_errno);
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
        $this->exitWithError('Statement execution failed', $this->numOfUrlsStmt->error, $this->getUrlStmt->errno);
      }
      return $this->numOfUrlsStmt->get_result()->fetch_row()[0];
    }

    function getRowById($id) {
      if (!$this->getByIdStmt->bind_param('s', $id)) {
        $this->exitWithError('Binding parameters failed', $this->getByIdStmt->error, $this->getByIdStmt->errno);
      }

      if (!$this->getByIdStmt->execute()) {
        $this->exitWithError('Statement execution failed', $this->getByIdStmt->error, $this->getByIdStmt->errno);
      }

      return $this->getByIdStmt->get_result();
    }

    function getRowByUrl($url) {
      if (!$this->getByUrlStmt->bind_param('s', $url)) {
        $this->exitWithError('Binding parameters failed', $this->getByUrlStmt->error, $this->getByUrlStmt->errno);
      }

      if (!$this->getByUrlStmt->execute()) {
        $this->exitWithError('Statement execution failed', $this->getByUrlStmt->error, $this->getByUrlStmt->errno);
      }

      return $this->getByUrlStmt->get_result();
    }

    function addUrl($id, $url) {
      if (!$this->addUrlStmt->bind_param('ss', $id, $url)) {
        $this->exitWithError('Binding parameters failed', $this->addUrlStmt->error, $this->addUrlStmt->errno);
      }

      if (!$this->addUrlStmt->execute()) {
        $this->exitWithError('Statement execution failed', $this->addUrlStmt->error, $this->addUrlStmt->errno);
      }
    }

    private function prepareStatement($sqlQuery) {
      $stmt = $this->mysql->prepare($sqlQuery);
      if (!$stmt) {
        $this->exitWithError('Prepare statement failed', $this->mysql->error, $this->mysql->errno);
      }

      return $stmt;
    }

    private function exitWithError($message, $error, $errno) {
      http_response_code(HTTP_SERVER_ERROR);
      die("$message: $error ($errno)");
    }
  }

  $db = new Database();
?>
