<?php
    include 'db.php';

    $id = $_GET['id'];

    $query = 
    "   SELECT posts.*, users.name, users.email 
        FROM posts 
        LEFT JOIN users ON posts.user_id = users.id 
        WHERE posts.id = ?  ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $post = $stmt->get_result()->fetch_assoc();
    $stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $post['title']; ?> - Eweberenic Blogs</title>
        
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
    <body class="bg-dark text-white">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="card shadow-sm bg-light text-dark">
                        <div class="card-body">
                            <h1 class="card-title text-center mb-4"><?php echo $post['title']; ?></h1>
                            <hr>
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="card-text"><?php echo nl2br($post['content']); ?></p>
                                </div>

                                <div class="col-md-4">
                                    <?php if (!empty($post['image'])): ?>
                                        <img src="data:image/jpeg;base64,<?php echo base64_encode($post['image']); ?>" class="img-fluid rounded" alt="Post Image">
                                    <?php else: ?>
                                        <img src="https://via.placeholder.com/300" class="img-fluid rounded" alt="Placeholder Image">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-muted d-flex justify-content-between">
                            <span class="small"><?php echo htmlspecialchars($post['name']); ?></span>
                            <span class="small"><?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="index.php" class="btn btn-secondary">Back to Blogs>></a>
                    </div>
                </div>
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