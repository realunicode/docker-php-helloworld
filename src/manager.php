<?php
// TinyFileManager configuration
$root_path = $_SERVER['DOCUMENT_ROOT']; // Root path for file manager
$root_url = ''; // Root URL for links in file manager

// Authentication
$use_auth = true; // Enable or disable authentication
$auth_users = [
    'admin' => '$2y$10$e0MYzXyjpJS2lT2lZc9R.e0MYzXyjpJS2lT2lZc9R.e0MYzXyjpJS2lT2lZc9R.e' // Password hash for 'admin' user (password: admin)
];

// File manager settings
$show_hidden_files = false; // Show or hide hidden files
$allowed_file_types = ''; // Allowed file types for upload
$disallowed_file_types = ''; // Disallowed file types for upload
$max_upload_size = 1000000; // Max file size for upload in bytes

// Start session
session_start();

// Authentication check
if ($use_auth) {
    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            if (isset($auth_users[$_POST['username']]) && password_verify($_POST['password'], $auth_users[$_POST['username']])) {
                $_SESSION['logged_in'] = true;
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            } else {
                $login_error = 'Invalid username or password';
            }
        }
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Login</title>
        </head>
        <body>
        <form method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
            <button type="submit">Login</button>
        </form>
        <?php if (isset($login_error)) echo '<p>' . $login_error . '</p>'; ?>
        </body>
        </html>
        <?php
        exit;
    }
}

// File manager logic
$files = scandir($root_path);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Manager</title>
    <style>
        table { width: 100%; }
        th, td { padding: 8px; text-align: left; }
    </style>
</head>
<body>
<h1>File Manager</h1>
<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Size</th>
        <th>Last Modified</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($files as $file): ?>
        <?php if ($file === '.' || $file === '..') continue; ?>
        <tr>
            <td><?php echo $file; ?></td>
            <td><?php echo is_dir($root_path . '/' . $file) ? 'Directory' : 'File'; ?></td>
            <td><?php echo is_dir($root_path . '/' . $file) ? '-' : filesize($root_path . '/' . $file); ?></td>
            <td><?php echo date('Y-m-d H:i:s', filemtime($root_path . '/' . $file)); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
