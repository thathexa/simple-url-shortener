<?php
  require_once 'db.php';

  $url = $_GET['url'];

  // Validate URL
  if (!preg_match('/^\w+:\/\/(www\.)?[-\w@:%.\+~#=\/?&]+$/', $url)) {
    http_response_code(400);
    die('Invalid URL');
  }

  $id = rtrim(base64_encode(hash('crc32', $_GET['url'])), '=');
  // Check if id already exists
  if ($mysql->query('select id from urls where id = "' . $id . '"')->num_rows === 0) {
    // Insert new URL into database
    $result = $mysql->query('insert into urls (id, url) values ("' . $id . '", "' . $url . '")');
    if (!$result) {
      http_response_code(400);
      die('Failed to insert URL into database');
    }
  }
  echo $_SERVER['SERVER_ADDR'] . '/?' . $id;
?>
