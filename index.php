<?php
include 'db.php';

// Fetch all posts with user details
$query = "
    SELECT posts.*, users.name, users.email 
    FROM posts 
    LEFT JOIN users ON posts.user_id = users.id 
    ORDER BY posts.created_at DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Posts</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .post { border: 1px solid #ddd; border-radius: 5px; padding: 15px; margin-bottom: 20px; }
        .post img { max-width: 100%; height: auto; }
        .post-title { font-size: 1.5rem; font-weight: bold; }
        .post-meta { font-size: 0.875rem; color: #6c757d; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center mb-4 text-primary">All Blog Posts</h1>
        <a href="create.php" class="btn btn-primary mb-3">Create New Post</a>
        
        <!-- Blog posts displayed in rows -->
        <div class="row">
            <?php while($post = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="post bg-white shadow-sm p-3 mb-4 rounded">
                        <!-- Display post image if it exists -->
                        <?php if (!empty($post['image'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($post['image']); ?>" alt="Post Image" class="img-fluid">
                        <?php endif; ?>
                        
                        <h2 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                        <p class="post-meta">By <?php echo htmlspecialchars($post['name']); ?> (<?php echo htmlspecialchars($post['email']); ?>) on <?php echo date('F j, Y', strtotime($post['created_at'])); ?></p>
                        <p><?php echo htmlspecialchars(substr($post['content'], 0, 100)) . '...'; ?></p>
                        <a href="edit.php?id=<?php echo $post['id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="delete.php?id=<?php echo $post['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
