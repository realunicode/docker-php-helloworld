<?php
// TinyFileManager configuration
$root_path = $_SERVER['DOCUMENT_ROOT']; // Root path for file manager
$root_url = ''; // Root URL for links in file manager

// File manager settings
$show_hidden_files = false; // Show or hide hidden files
$allowed_file_types = ''; // Allowed file types for upload
$disallowed_file_types = ''; // Disallowed file types for upload
$max_upload_size = 1000000; // Max file size for upload in bytes

// File manager logic
$current_path = isset($_GET['path']) ? $_GET['path'] : '';
$full_path = rtrim($root_path, '/') . '/' . trim($current_path, '/');

if (!is_dir($full_path)) {
    $full_path = $root_path;
    $current_path = '';
}

$files = scandir($full_path);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Manager</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        a { color: #0066cc; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .back-link { margin-bottom: 20px; }
    </style>
</head>
<body>
<h1>File Manager</h1>
<?php if ($current_path): ?>
    <div class="back-link">
        <a href="?path=<?php echo urlencode(dirname($current_path)); ?>">&larr; Back to parent directory</a>
    </div>
<?php endif; ?>
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
    <?php foreach ($files as $file):
        if ($file === '.' || ($file === '..' && !$current_path)) continue;
        $file_path = $full_path . '/' . $file;
        $is_dir = is_dir($file_path);
        if (!$show_hidden_files && $file[0] === '.') continue;
        ?>
        <tr>
            <td>
                <?php if ($is_dir): ?>
                    <a href="?path=<?php echo urlencode(trim($current_path . '/' . $file, '/')); ?>"><?php echo htmlspecialchars($file); ?></a>
                <?php else: ?>
                    <?php echo htmlspecialchars($file); ?>
                <?php endif; ?>
            </td>
            <td><?php echo $is_dir ? 'Directory' : 'File'; ?></td>
            <td><?php echo $is_dir ? '-' : number_format(filesize($file_path)) . ' bytes'; ?></td>
            <td><?php echo date('Y-m-d H:i:s', filemtime($file_path)); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
