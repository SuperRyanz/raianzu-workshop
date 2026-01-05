<!DOCTYPE html>
<html>
<head>
    <title>Test Upload Form</title>
</head>
<body>
    <h2>Test Upload to services folder</h2>
    
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="testfile" required>
        <button type="submit" name="submit">Upload</button>
    </form>
    
    <?php
    if (isset($_POST['submit']) && isset($_FILES['testfile'])) {
        $file = $_FILES['testfile'];
        
        echo "<h3>Debug Info:</h3>";
        echo "File name: " . $file['name'] . "<br>";
        echo "File type: " . $file['type'] . "<br>";
        echo "File size: " . $file['size'] . "<br>";
        echo "File error: " . $file['error'] . "<br>";
        echo "Tmp file: " . $file['tmp_name'] . "<br>";
        echo "Is uploaded: " . (is_uploaded_file($file['tmp_name']) ? "YES" : "NO") . "<br>";
        
        echo "<br>---<br>";
        
        $target = "../assets/img/services/upload_" . time() . ".jpg";
        echo "Target: " . $target . "<br>";
        
        if (is_uploaded_file($file['tmp_name'])) {
            if (move_uploaded_file($file['tmp_name'], $target)) {
                echo "✅ SUCCESS! File moved to: " . $target . "<br>";
                echo "File exists now: " . (file_exists($target) ? "YES" : "NO") . "<br>";
            } else {
                echo "❌ FAILED! Could not move file.<br>";
            }
        } else {
            echo "❌ File is not uploaded file!<br>";
        }
    }
    ?>
</body>
</html>
