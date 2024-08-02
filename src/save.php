<?php

$unique_id = $_GET['id'] ?? null;
$recipient = $_GET['$recipient'] ?? null;
$chat_id = $_GET['chat_id'] ?? null;
$bot_token = $_GET['bot_token'] ?? null;

if ($unique_id && $chat_id && $bot_token) {
    $log_file = "tracking_log_{$unique_id}.txt";

    if (file_exists($log_file)) {
        echo "file exist";
    } else {
        $log_content = "Recipient: {$recipient}\nChat ID: {$chat_id}\nBot Token: {$bot_token}\n";

        file_put_contents($log_file, $log_content);

        echo "Tracking information saved for ID: {$unique_id}";
    }
} else {
    echo "Missing required parameters. Tracking information not saved.";
}

?>
