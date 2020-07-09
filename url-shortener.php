<?php
  require_once 'db.php';

  $url = $_GET['url'];

  // Validate URL
  if (!preg_match('/^\w+:\/\/(www\.)?[-\w@:%.\+~#=\/?&]+$/', $url)) {
    http_response_code(400);
    die('Invalid URL');
  }

  $id = rtrim(base64_encode(hash('crc32', $_GET['url'])), '=');
  // Verify id doesn't already exists
  if ($db->query('select id from urls where id = "' . $id . '"')->num_rows === 0) {
    // Insert new URL into database
    $result = $db->query('insert into urls (id, url) values ("' . $id . '", "' . $url . '")');
    if (!$result) {
      http_response_code(500);
      die('Failed to insert URL into database');
    }
  }
  echo $_SERVER['SERVER_ADDR'] . '/?' . $id;
?>
