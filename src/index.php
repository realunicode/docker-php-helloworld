<?php

$unique_id = $_GET['id'] ?? 'Unknown';

$log_file = "tracking_log_{$unique_id}.txt";

if (file_exists($log_file)) {
    $log_content = file_get_contents($log_file);
    
    $lines = explode("\n", $log_content);
    $chat_id = trim(explode(": ", $lines[0])[1]);
    $bot_token = trim(explode(": ", $lines[1])[1]);

    $ip = $_SERVER['REMOTE_ADDR'];
    $timestamp = date('Y-m-d H:i:s');

    $message = urlencode("Email with ID: $unique_id was opened at $timestamp from IP: $ip");
    $telegram_api_url = "https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$chat_id&text=$message";
    file_get_contents($telegram_api_url);
}

header('Content-Type: image/gif');
echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');

?>
