<?php
include 'db.php';

$id = $_GET['id'];

// Fetch the post details
$postQuery = "SELECT posts.*, users.name, users.email FROM posts LEFT JOIN users ON posts.user_id = users.id WHERE posts.id = ?";
$stmt = $conn->prepare($postQuery);
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle new comment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    $comment = $_POST['comment'];
    $stmt = $conn->prepare("INSERT INTO comments (post_id, comment) VALUES (?, ?)");
    $stmt->bind_param("is", $id, $comment);
    $stmt->execute();
    $stmt->close();
}

// Handle comment deletion
if (isset($_GET['delete_comment_id'])) {
    $commentId = $_GET['delete_comment_id'];
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->bind_param("i", $commentId);
    $stmt->execute();
    $stmt->close();
    // Redirect to avoid resubmission issues
    header("Location: post.php?id=$id");
    exit();
}

// Fetch all comments for the post
$commentQuery = "SELECT * FROM comments WHERE post_id = ?";
$stmt = $conn->prepare($commentQuery);
$stmt->bind_param("i", $id);
$stmt->execute();
$comments = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center text-primary"><?php echo htmlspecialchars($post['title']); ?></h1>
        <p class="text-center text-muted">By <?php echo htmlspecialchars($post['name']); ?> (<?php echo htmlspecialchars($post['email']); ?>) on <?php echo date('F j, Y', strtotime($post['created_at'])); ?></p>

        <!-- Post image if exists -->
        <?php if (!empty($post['image'])): ?>
            <div class="text-center">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($post['image']); ?>" alt="Post Image" class="img-fluid" style="max-width: 600px;">
            </div>
        <?php endif; ?>

        <!-- Post content -->
        <div class="mt-4">
            <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
        </div>

        <!-- Comments section -->
        <div class="mt-5">
            <h4>Comments</h4>
            <?php while($comment = $comments->fetch_assoc()): ?>
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div class="bg-white p-3 rounded flex-grow-1">
                        <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                    </div>
                    <!-- Add delete button -->
                    <div class="ms-2">
                        <a href="post.php?id=<?php echo $id; ?>&delete_comment_id=<?php echo $comment['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this comment?');">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>

            <!-- Comment form -->
            <form method="POST" class="mt-3">
                <div class="mb-3">
                    <label for="comment" class="form-label">Leave a Comment:</label>
                    <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Comment</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
