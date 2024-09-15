<?php
include 'db.php';

$query = "SELECT * FROM posts ORDER BY created_at DESC";
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
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-primary">All Blog Posts</h1>
            <a href="create.php" class="btn btn-success">Create New Post</a>
        </div>
        
        <hr>

        <!-- Display each post in a card format -->
        <div class="row">
            <?php while($post = $result->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm">
                        <?php if (!empty($post['image'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($post['image']); ?>" class="card-img-top" alt="Post Image">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/150" class="card-img-top" alt="Placeholder Image">
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $post['title']; ?></h5>
                            <p class="card-text"><?php echo substr($post['content'], 0, 100) . '...'; ?></p>
                            <a href="edit.php?id=<?php echo $post['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete.php?id=<?php echo $post['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
