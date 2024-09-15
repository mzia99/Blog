<?php
include 'db.php';

$id = $_GET['id'];
$query = "SELECT * FROM posts WHERE id = $id";
$post = $conn->query($query)->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image'];

    // Set the maximum file size (5MB)
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    // Check if the fields are not empty
    if (!empty($title) && !empty($content)) {

        // Update the post without changing the image if no new image is uploaded
        if (!empty($image['name'])) {
            // Validate the file size
            if ($image['size'] > $maxFileSize) {
                $error = "File size must be less than 5MB.";
            } else {
                // Read the image file content
                $imageData = file_get_contents($image['tmp_name']);
                
                // Update title, content, and image
                $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, image = ? WHERE id = ?");
                $stmt->bind_param("ssbi", $title, $content, $imageData, $id);
                $stmt->send_long_data(2, $imageData); // For large binary data
            }
        } else {
            // Update title and content only, without changing the image
            $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
            $stmt->bind_param("ssi", $title, $content, $id);
        }

        $stmt->execute();
        $stmt->close();
        header('Location: index.php');
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
</head>
<body>
    <h1>Edit Post</h1>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    
    <!-- Display the current image -->
    <?php if (!empty($post['image'])): ?>
        <img src="data:image/jpeg;base64,<?php echo base64_encode($post['image']); ?>" alt="Post Image" style="max-width: 300px; max-height: 300px;"><br><br>
    <?php else: ?>
        <p>No image uploaded.</p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?php echo $post['title']; ?>"><br><br>

        <label>Content:</label><br>
        <textarea name="content"><?php echo $post['content']; ?></textarea><br><br>

        <label>Change Image (optional, max 5MB):</label><br>
        <input type="file" name="image" accept="image/*"><br><br>

        <button type="submit">Update</button>
    </form>
</body>
</html>