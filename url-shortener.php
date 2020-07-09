<?php
  require_once 'include/db.php';
  require_once 'include/constants.php';

  $url = $_GET['url'];

  // Validate URL
  if (!preg_match('/^\w+:\/\/(www\.)?[-\w@:%.\+~#=\/?&]+$/', $url)) {
    http_response_code(HTTP_BAD_REQUEST);
    die('Invalid URL');
  }

  // Check if URL is already in database
  $result = $db->getRowByUrl($url);
  if ($result->num_rows > 0) {
    $id = $result->fetch_assoc()['id'];
  } else {
    $id = rtrim(base64_encode(str_pad(dechex($db->getNumberOfUrls()), 8, '0', STR_PAD_LEFT)), '=');
    $db->addUrl($id, $url); // Insert new URL into database
  }

  echo $_SERVER['SERVER_ADDR'] . '/?' . $id;
?>
