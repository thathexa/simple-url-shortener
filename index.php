<?php
  if (preg_match('/^[a-zA-Z0-9]{11}$/', $_SERVER['QUERY_STRING'])) {
    require_once 'include/db.php';
    require_once 'include/constants.php';

    $result = $db->getRowById($_SERVER['QUERY_STRING']);
    if ($result->num_rows > 0) {
      header('Location: ' . $result->fetch_assoc()['url']);
    } else {
      http_response_code(HTTP_NOT_FOUND);
      echo file_get_contents(NOT_FOUND_PAGE);
    }
    die();
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>URL Shortener</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="static/main-page.css" />
    <script type="text/javascript" src="static/main-page.js"></script>
  </head>
  <body>
    <div class="content-area">
      <h1>URL Shortener</h1>
      <h3>SHORT ANY URL!</h3>
      <input id="url-input" type="text" placeholder="http://some-long-url.to.short">
      <div class="button-container">
        <p id="response-message"></p>
        <button onclick="shortenUrl()">Short it!</button>
      </div>
    </div>
  </body>
</html>
