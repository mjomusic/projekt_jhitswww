<?php
session_start();
include 'connect.php';

$post_id = $_GET['id'];
$sql = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.user_id WHERE post_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $post_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$post = mysqli_fetch_assoc($result);

$sql = "SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.user_id WHERE post_id = ? ORDER BY comment_id DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $post_id);
mysqli_stmt_execute($stmt);
$comments = mysqli_stmt_get_result($stmt);

mysqli_close($conn);
?>
<!doctype html>
<html lang="pl" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MJO |
        <?php echo $post['title'] ?>
    </title>
    <link rel="shortcut icon" href="favicon.jpg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .post-image {
            width: 500px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">MJO</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="news.php">Aktualności</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="studio.php">Wyposażenie</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="releases.php">Wydania</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">Kontakt</a>
                        </li>

                    </ul>
                    <div class="d-flex ms-auto">
                        <?php
                        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                            echo '<span class="navbar-text fw-bold">' . $_SESSION["username"] . '</span>';
                            if (isset($_SESSION["admin"]) && $_SESSION["admin"] === '1') {
                                echo '<button onclick="window.location.href=\'admin.php\'" class="btn btn-outline-warning ms-2" type="button">Admin</button>';
                            }
                            echo '<a href="logout.php" class="btn btn-outline-danger ms-2">Wyloguj</a>';
                        } else {
                            echo '<button onclick="window.location.href=\'login.php\'" class="btn btn-outline-success me-2" type="button">Zaloguj się</button>';
                            echo '<button onclick="window.location.href=\'register.php\'" class="btn btn-outline-info" type="button">Zarejestruj się</button>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="container">
            <div class="row">
                <div class="col-md-6">
                    <img src="<?php echo htmlspecialchars($post['image']); ?>" class="img-fluid post-image"
                        alt="Obrazek">
                </div>
                <div class="col-md-6">
                    <h1>
                        <?php echo htmlspecialchars($post['title']); ?>
                    </h1>
                    <p class="text-muted">
                        <?php echo htmlspecialchars($post['username']) . ' | ' . htmlspecialchars($post['date']); ?>
                    </p>
                    <p>
                        <?php echo htmlspecialchars($post['content']); ?>
                    </p>
                    <?php
                    if (isset($_SESSION["admin"]) && $_SESSION["admin"] === '1') {
                        echo '<a href="edit_post.php?post_id=' . $post['post_id'] . '" class="btn btn-primary">Edytuj</a>';
                    }
                    ?>
                </div>
            </div>

            <h2>Komentarze</h2>
            <?php
            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                echo '<form action="add_comment.php" method="post" class="mb-3">';
                echo '<input type="hidden" name="post_id" value="' . htmlspecialchars($post_id) . '">';
                echo '<textarea name="comment" class="form-control" required style="resize: none;"></textarea>';
                echo '<button type="submit" class="btn btn-light mt-2">Dodaj komentarz!</button>';
                echo '</form>';
            } else {
                echo '<p>Musisz się zalogować, aby dodać komentarz.</p>';
            }
            while ($comment = mysqli_fetch_assoc($comments)) {
                echo '<div class="card mb-3">';
                echo '<div class="card-header">';
                echo '<strong>' . htmlspecialchars($comment['username']) . '</strong> <small class="text-muted">(' . htmlspecialchars($comment['date']) . ')</small>';
                echo '</div>';
                echo '<div class="card-body">';
                echo '<p class="card-text">' . htmlspecialchars($comment['content']) . '</p>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </section>
    </main>
    <footer class="footer mt-auto py-3 bg-dark text-white">
        <div class="container text-center">
            <span>&copy; 2024 MJO</span>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>