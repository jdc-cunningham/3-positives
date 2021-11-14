<?php

    // get mail gun values from environment variable
    $mg_credentials_parts = explode(';', file_get_contents('.mg-credentials', true));

    $mg_api_key = explode(' = ', $mg_credentials_parts[0])[1];
    $mg_email_from = explode(' = ', $mg_credentials_parts[1])[1];
    $mg_email_reply_to = $mg_email_from;
    $mg_domain = explode(' = ', $mg_credentials_parts[2])[1];
    $mg_sent_by = explode(' = ', $mg_credentials_parts[3])[1];

    // send mail through MailGun function
    function send_mail($user_email, $email_subject, $email_message) {

        global $mg_api_key, $mg_email_from, $mg_email_reply_to, $mg_domain, $mg_sent_by;

        $mg_api = $mg_api_key;
        $mg_version = 'api.mailgun.net/v3/';
        $mg_domain = $mg_domain;
        $mg_from_email = $mg_email_from;
        $mg_reply_to_email = $mg_email_reply_to;

        $mg_message_url = "https://".$mg_version.$mg_domain."/messages";


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        curl_setopt ($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_VERBOSE, 0);
        curl_setopt ($ch, CURLOPT_HEADER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt($ch, CURLOPT_USERPWD, 'api:' . $mg_api);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HEADER, false);

        // moded msg Array
        $msg_array = [
            'from'       => $mg_sent_by . ' <' . $mg_email_from . '>',
            'to'         => $user_email,
            'h:Reply-To' =>  ' <' . $mg_reply_to_email . '>',
            'subject'    => $email_subject,
            'html'       => $email_message
        ];

        curl_setopt($ch, CURLOPT_URL, $mg_message_url);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$msg_array);
        $result = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($result,TRUE);

    }