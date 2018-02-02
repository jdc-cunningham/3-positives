<?php

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // set date
        date_default_timezone_set("America/Chicago");
        $date_today = date('Y-m-d');

        // credentials
        require(dirname(__FILE__) . DIRECTORY_SEPARATOR.'db-connect.php'); // get db credentials

        // get details from email
        $positive_response= strip_tags($_POST['body-html']);
        $responded = 1;

        // insert response and update responded to 1
        $stmt = $dbh->prepare('UPDATE positive_responses SET response=:response, responded=:responded WHERE date_today=:date_today');
        $stmt->bindParam(':response', $positive_response, PDO::PARAM_STR);
        $stmt->bindParam(':responded', $responded, PDO::PARAM_INT);
        $stmt->bindParam(':date_today', $date_today, PDO::PARAM_STR);
        $stmt->execute();



    }