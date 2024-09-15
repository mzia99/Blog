<?php
    include 'db.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $name = $_POST['name'];
        $email = $_POST['email'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $image = $_FILES['image'];

        // Set the maximum file size (5MB)
        $maxFileSize = 5 * 1024 * 1024; // 5MB

        // Check if all fields are filled and image is uploaded
        if (!empty($name) && !empty($email) && !empty($title) && !empty($content) && !empty($image['name'])) {
            if ($image['size'] > $maxFileSize) {
                $error = "File size must be less than 5MB.";
            } else {
                // Check if user already exists by email
                $checkUserStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
                $checkUserStmt->bind_param("s", $email);
                $checkUserStmt->execute();
                $checkUserStmt->store_result();
                
                if ($checkUserStmt->num_rows == 0) {
                    // User does not exist, insert new user
                    $insertUserStmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
                    $insertUserStmt->bind_param("ss", $name, $email);
                    $insertUserStmt->execute();
                    $userId = $insertUserStmt->insert_id;
                    $insertUserStmt->close();
                } else {
                    // Fetch existing user's ID
                    $checkUserStmt->bind_result($userId);
                    $checkUserStmt->fetch();
                }
                $checkUserStmt->close();

                // Insert the post
                $imageData = file_get_contents($image['tmp_name']);
                $stmt = $conn->prepare("INSERT INTO posts (title, content, image, user_id) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssbi", $title, $content, $imageData, $userId);
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
        
        <title>Create Post</title>

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
            <h1 class="text-center mb-4 text-white">Let others know what's on your mind</h1>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Your Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Your Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter post title">
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea class="form-control" id="content" name="content" rows="5" placeholder="Enter post content"></textarea>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Upload Image (max 5MB)</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success">Post</button>
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