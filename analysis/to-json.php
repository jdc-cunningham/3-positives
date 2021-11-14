<?php

  require(dirname(__FILE__) . DIRECTORY_SEPARATOR.'db-connect.php'); // get db credentials

  $stmt = $dbh->prepare('SELECT id, date_today, response FROM positive_responses WHERE responded = 1 ORDER BY id ASC');

  if ($stmt->execute()) {
    $result = $stmt->fetchAll();
    $json_body = [];

    foreach ($result as $row) {
      $json_body[$row['date_today']] = $row['response'];
      echo '<div><span>' . $row['date_today'] . '</span><span>' . $row['response'] . '</span></div><br>';
    }
  }