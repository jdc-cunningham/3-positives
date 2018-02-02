<?php

    // simple ip check
    $client_ip = $_SERVER['REMOTE_ADDR'];

    // Additional check for proxy
    // If more than one ip address is returned, the last one is captured
    if ( array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
	    $client_ip = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
    }

    if ($client_ip != 'your-pi-network-public-ip') {

        error_log('wrong ip'); // optional call to let user know this is broken
        exit;

    }

    // main script to send first email, if no response in certain time, try again
    require(dirname(__FILE__) . DIRECTORY_SEPARATOR.'db-connect.php'); // get db credentials
    require(dirname(__FILE__) . DIRECTORY_SEPARATOR.'send-mail.php');   // send mail function through MG

    date_default_timezone_set("America/Chicago");

    $date_today = date('Y-m-d');
    $user_email = 'your-email';
    $email_subject = 'What are 3 positive things about your life?';
    $email_message = 'Respond with three lines or paragraphs separated by line breaks.';

    // do db lookup to check if an entry exists for today
    $stmt = $dbh->prepare('SELECT id, send_try_num, responded FROM positive_responses WHERE date_today=:date_today');
    $stmt->bindParam(':date_today', $date_today, PDO::PARAM_STR);
    if ($stmt->execute()) {
        $result = $stmt->fetchAll();
        if (empty($result)) {

            // no entry exists for today (first time asking for 3 positives)

            // send email
            send_mail($user_email, $email_subject, $email_message);

            // insert attempt

            $id = ""; // auto incremented
            $send_try_num = 1;
            $response = "";
            $responded = 0;

            $stmt = $dbh->prepare('INSERT INTO positive_responses VALUES (:id, :date_today, :send_try_num, :response, :responded)');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':date_today', $date_today, PDO::PARAM_STR);
            $stmt->bindParam(':send_try_num', $send_try_num, PDO::PARAM_INT);
            $stmt->bindParam(':response', $response, PDO::PARAM_STR);
            $stmt->bindParam(':responded', $responded, PDO::PARAM_INT);
            $stmt->execute();

        }
        else {
            
            // check if user has responded
            if (count($result) == 1) {
                
                foreach ($result as $row) {

                    // should only be 1 return
                    if ($row['responded'] != 1) {

                        // user hasn't responded, check try count, send again if less than 6
                        $send_attempts = $row['send_try_num'];

                        if ($send_attempts < 6) {

                            // send email
                            send_mail($user_email, $email_subject, $email_message);

                            // increment send attempt

                            $send_try_num = $send_attempts + 1;
                            $cur_id = $row['id'];

                            $stmt = $dbh->prepare('UPDATE positive_responses SET send_try_num=:send_try_num WHERE id=:id');
                            $stmt->bindParam(':send_try_num', $send_try_num, PDO::PARAM_INT);
                            $stmt->bindParam(':id', $cur_id, PDO::PARAM_INT);
                            $stmt->execute();

                        }

                    }

                }
            }
        }
    }