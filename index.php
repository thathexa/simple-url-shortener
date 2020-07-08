<?php
  if (preg_match('/^[a-zA-Z0-9]{11}$/', $_SERVER['QUERY_STRING'])) {
    require_once 'db.php';

    $result = $mysql->query('select url from urls where id = "' . $_SERVER['QUERY_STRING'] . '"');
    if ($result and $result->num_rows === 1) {
      header('Location: ' . $result->fetch_assoc()['url']);
      die();
    }

    echo '
<!DOCTYPE html>
<html>
  <head>
    <title>URL Not Found</title>
    <link rel="stylesheet" type="text/css" href="not-found.css" />
  </head>
  <body>
    <h1>The requested URL does not exist :(</h1>
  </body>
</html>
    ';
    $mysql->close();
    die();
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>URL Shortener</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="main-page.css" />
    <script type="text/javascript" src="main-page.js"></script>
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
