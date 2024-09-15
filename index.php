<?php

    include 'db.php';
    $query = 
    "   SELECT posts.*, users.name, users.email 
        FROM posts 
        LEFT JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC  ";
    $result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Eweberinc Blogs</title>
        
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
            <div class="d-flex justify-content-center align-items-center position-relative mb-4">
                <p class="text-white h1">All Blogs</p>
                <a href="create.php" class="btn btn-success position-absolute end-0">New Post</a>
            </div>
            
            <hr class="text-white">

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
                                <p class="card-title h5"><?php echo $post['title']; ?></p>
                                <p class="card-text"><?php echo substr($post['content'], 0, 100) . '...'; ?></p>
                                <a href="edit.php?id=<?php echo $post['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                                <a href="delete.php?id=<?php echo $post['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                <a href="open_blog.php?id=<?php echo $post['id']; ?>" class="btn btn-warning btn-sm">See Blog</a>
                            </div>
                            <div class="card-footer text-muted d-flex justify-content-between">
                                <span class="small"><?php echo htmlspecialchars($post['name']); ?></span>
                                <span class="small"><?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
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