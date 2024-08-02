<?php

$unique_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
$recipient = filter_input(INPUT_GET, 'recipient', FILTER_SANITIZE_STRING);
$chat_id = filter_input(INPUT_GET, 'chat_id', FILTER_SANITIZE_STRING);
$bot_token = filter_input(INPUT_GET, 'bot_token', FILTER_SANITIZE_STRING);

if ($unique_id && $chat_id && $bot_token) {
    $log_file = "tracking_log_" . preg_replace('/[^a-zA-Z0-9_-]/', '', $unique_id) . ".txt";

    if (file_exists($log_file)) {
        echo "File exists";
    } else {
        $log_content = "Recipient: " . htmlspecialchars($recipient) . "\n" .
                       "Chat ID: " . htmlspecialchars($chat_id) . "\n" .
                       "Bot Token: [REDACTED]\n";

        if (file_put_contents($log_file, $log_content) !== false) {
            echo "Tracking information saved for ID: " . htmlspecialchars($unique_id);
        } else {
            echo "Error saving tracking information.";
        }
    }
} else {
    echo "Missing required parameters. Tracking information not saved.";
}

?>
