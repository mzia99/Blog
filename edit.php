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
<html lang="en">
    <head>

        <title>Edit Blog</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
    </head>
    <body class="bg-dark">
        <div class="container mt-5">
            <h1 class="text-center mb-4 text-white">Edit Blog</h1>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="mb-4 text-center">
                <?php if (!empty($post['image'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($post['image']); ?>" alt="Post Image" class="img-fluid" style="max-width: 300px; max-height: 300px;">
                <?php else: ?>
                    <p>No image uploaded.</p>
                <?php endif; ?>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea class="form-control" id="content" name="content" rows="5" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Change Image (optional, max 5MB)</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>

        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </body>
</html>