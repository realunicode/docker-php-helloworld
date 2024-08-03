<?php
// Advanced File Manager Configuration
$root_path = $_SERVER['DOCUMENT_ROOT'];
$current_path = isset($_GET['path']) ? $_GET['path'] : '';
$full_path = rtrim($root_path, '/') . '/' . trim($current_path, '/');

// Ensure we're within the root path
if (!is_dir($full_path) || strpos(realpath($full_path), realpath($root_path)) !== 0) {
    $full_path = $root_path;
    $current_path = '';
}

// Handle file operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'upload':
                if (isset($_FILES['file'])) {
                    $target_file = $full_path . '/' . basename($_FILES['file']['name']);
                    move_uploaded_file($_FILES['file']['tmp_name'], $target_file);
                }
                break;
            case 'rename':
                if (isset($_POST['oldname']) && isset($_POST['newname'])) {
                    rename($full_path . '/' . $_POST['oldname'], $full_path . '/' . $_POST['newname']);
                }
                break;
            case 'delete':
                if (isset($_POST['filename'])) {
                    $file_to_delete = $full_path . '/' . $_POST['filename'];
                    if (is_file($file_to_delete)) {
                        unlink($file_to_delete);
                    } elseif (is_dir($file_to_delete)) {
                        rmdir($file_to_delete);
                    }
                }
                break;
            case 'save':
                if (isset($_POST['filename']) && isset($_POST['content'])) {
                    file_put_contents($full_path . '/' . $_POST['filename'], $_POST['content']);
                }
                break;
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . '?path=' . urlencode($current_path));
    exit;
}

// Handle file editing
$edit_file = isset($_GET['edit']) ? $_GET['edit'] : null;
$file_content = '';
if ($edit_file) {
    $file_to_edit = $full_path . '/' . $edit_file;
    if (is_file($file_to_edit)) {
        $file_content = htmlspecialchars(file_get_contents($file_to_edit));
    }
}

$files = scandir($full_path);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Advanced File Manager</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        a { color: #0066cc; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .back-link, .upload-form, .edit-form { margin-bottom: 20px; }
        .actions { display: flex; gap: 10px; }
        .actions form { margin: 0; }
    </style>
</head>
<body>
    <h1>Advanced File Manager</h1>
    
    <?php if ($current_path): ?>
        <div class="back-link">
            <a href="?path=<?php echo urlencode(dirname($current_path)); ?>">&larr; Back to parent directory</a>
        </div>
    <?php endif; ?>

    <div class="upload-form">
        <h3>Upload File</h3>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="upload">
            <input type="file" name="file" required>
            <button type="submit">Upload</button>
        </form>
    </div>

    <?php if ($edit_file): ?>
        <div class="edit-form">
            <h3>Edit File: <?php echo htmlspecialchars($edit_file); ?></h3>
            <form action="" method="post">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="filename" value="<?php echo htmlspecialchars($edit_file); ?>">
                <textarea name="content" rows="10" cols="80"><?php echo $file_content; ?></textarea>
                <br>
                <button type="submit">Save</button>
            </form>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th>Last Modified</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $file):
                    if ($file === '.' || ($file === '..' && !$current_path)) continue;
                    $file_path = $full_path . '/' . $file;
                    $is_dir = is_dir($file_path);
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
                        <td class="actions">
                            <?php if (!$is_dir): ?>
                                <a href="?path=<?php echo urlencode($current_path); ?>&edit=<?php echo urlencode($file); ?>">Edit</a>
                            <?php endif; ?>
                            <form action="" method="post" onsubmit="return confirm('Are you sure you want to rename this <?php echo $is_dir ? 'directory' : 'file'; ?>?');">
                                <input type="hidden" name="action" value="rename">
                                <input type="hidden" name="oldname" value="<?php echo htmlspecialchars($file); ?>">
                                <input type="text" name="newname" value="<?php echo htmlspecialchars($file); ?>" required>
                                <button type="submit">Rename</button>
                            </form>
                            <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete this <?php echo $is_dir ? 'directory' : 'file'; ?>?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="filename" value="<?php echo htmlspecialchars($file); ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
