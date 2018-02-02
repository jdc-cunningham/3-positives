# 3-positives
Daily email asks for three positive things, retries on fail, stores into db

## Requirements
A Raspberry Pi, or VPS/home server that has CRON and can execute Python/requests module. A MailGun account (free per 10,000 emails per month <- insane!). Remote server with PHP, MySQL running.

## Code Execution
Raspberry Pi runs a set of CRON jobs which runs a Python script 3-positives.py This is a GET request to 3-positives.php and 3-positives.php deals with interacting with MySQL and if necessary sending an email with MailGun. MailGun receives my response, forwards response as a POST request to store-response.php which stores the response and updates responded to 1, thereby stopping any more emails for the day.
Validation is only by IP and email address of sender (match recipient end of MailGun).