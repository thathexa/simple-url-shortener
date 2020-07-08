<?php
  const SERVER_ADDR = 'localhost';
  const USER = 'root';
  const DATABASE = 'url_shortener';

  $mysql = new mysqli(SERVER_ADDR, USER);

  if ($mysql->connect_error) {
    die('MySQL connection failed: ' . $mysql->connect_error);
  }

  $mysql->select_db(DATABASE);
  $result = $mysql->query('select database()');
  if (!$result or $result->fetch_row()[0] !== DATABASE) {
    $result->close();
    $mysql->close();
    die('Failed to select database "' . DATABASE . '"');
  }
?>
