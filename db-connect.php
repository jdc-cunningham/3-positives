<?php

    $db_credentials = file_get_contents('.db-credentials', true);

    $db_credentials_parts = explode(';', $db_credentials);

    $user = explode(' = ', $db_credentials_parts[0])[1];
    $password = explode(' = ', $db_credentials_parts[1])[1];
    $db_name = explode(' = ', $db_credentials_parts[2])[1];

    // try to connect
    try {
        $dbusername = $user;
        $dbpassword = $password;
        $dbh = new PDO('mysql:host=localhost;dbname='.$db_name,$dbusername,$dbpassword);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }