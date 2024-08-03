<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: *');

$unique_id = isset($_GET['id']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['id']) : 'Unknown';

$log_file = "tracking_log_{$unique_id}.txt";

if (file_exists($log_file)) {
    $log_content = file_get_contents($log_file);
    
    $lines = explode("\n", $log_content);
    $recipient = trim(explode(": ", $lines[0])[1]);
    $chat_id = trim(explode(": ", $lines[1])[1]);
    $bot_token = trim(explode(": ", $lines[2])[1]);
    
    $timestamp = date('Y-m-d H:i:s');

    $message = urlencode("Email for recipient: $recipient with ID: $unique_id was opened at $timestamp");
    $telegram_api_url = "https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$chat_id&text=$message";
    
    $response = file_get_contents($telegram_api_url);
    
    // Log the response for debugging
    file_put_contents("debug_log.txt", $response . PHP_EOL, FILE_APPEND);
}

// Return a 1x1 transparent image
header('Content-Type: image/png');
$im = imagecreatetruecolor(1, 1);
imagesavealpha($im, true);
$trans_color = imagecolorallocatealpha($im, 0, 0, 0, 127);
imagefill($im, 0, 0, $trans_color);
imagepng($im);
imagedestroy($im);

?>
