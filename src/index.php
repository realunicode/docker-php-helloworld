<?php

$unique_id = $_GET['id'] ?? 'Unknown';
$chat_id = $_GET['chat_id'] ?? '';
$bot_token = $_GET['bot_token'] ?? '';

$ip = $_SERVER['REMOTE_ADDR'];
$timestamp = date('Y-m-d H:i:s');

if ($unique_id && $chat_id && $bot_token) {
    $message = urlencode("Email with ID: $unique_id was opened at $timestamp from IP: $ip");
    $telegram_api_url = "https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$chat_id&text=$message";
    file_get_contents($telegram_api_url);
}

header('Content-Type: image/gif');
echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');

?>
