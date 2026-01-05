<?php
// Simple test image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['testfile'])) {
        $file = $_FILES['testfile'];
        echo "<h3>Upload Info:</h3>";
        echo "Name: " . $file['name'] . "<br>";
        echo "Type: " . $file['type'] . "<br>";
        echo "Size: " . $file['size'] . "<br>";
        echo "Tmp: " . $file['tmp_name'] . "<br>";
        echo "Error: " . $file['error'] . "<br>";
        
        $target = '../assets/img/services/test_' . time() . '.jpg';
        echo "<br><strong>Target: " . $target . "</strong><br>";
        
        echo "is_uploaded_file: " . (is_uploaded_file($file['tmp_name']) ? 'YES' : 'NO') . "<br>";
        
        $folder = '../assets/img/services';
        echo "Folder exists: " . (is_dir($folder) ? 'YES' : 'NO') . "<br>";
        echo "Folder writable: " . (is_writable($folder) ? 'YES' : 'NO') . "<br>";
        
        if (move_uploaded_file($file['tmp_name'], $target)) {
            echo "<br>✅ <strong>SUKSES!</strong> File saved to: " . $target;
            echo "<br>File exists: " . (file_exists($target) ? 'YES' : 'NO');
        } else {
            echo "<br>❌ <strong>GAGAL!</strong>";
        }
    }
}
?>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="testfile" required>
    <button type="submit">Test Upload</button>
</form>
