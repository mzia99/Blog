<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image'];

    // Set the maximum file size (5MB)
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    // Check if all fields are filled and image is uploaded
    if (!empty($title) && !empty($content) && !empty($image['name'])) {
        if ($image['size'] > $maxFileSize) {
            $error = "File size must be less than 5MB.";
        } else {
            // Read the image file content and escape it for insertion
            $imageData = file_get_contents($image['tmp_name']);

            // Prepare the SQL query
            $stmt = $conn->prepare("INSERT INTO posts (title, content, image) VALUES (?, ?, ?)");
            $stmt->bind_param("ssb", $title, $content, $imageData);
            $stmt->send_long_data(2, $imageData); // For large binary data
            $stmt->execute();
            $stmt->close();

            // Redirect after successful insert
            header('Location: index.php');
        }
    } else {
        $error = "All fields are required, and an image must be uploaded.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center mb-4 text-primary">Create a New Post</h1>

        <!-- Display error message if exists -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Enter post title" required>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" rows="5" placeholder="Enter post content" required></textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Upload Image (max 5MB)</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success">Create Post</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
