<?php
include 'connect.php';
session_start(); // Rozpoczęcie sesji

// Sprawdzenie, czy użytkownik jest zalogowany i jest administratorem, jeśli nie - przekierowanie na stronę logowania
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["admin"] !== '1') {
    header("location: login.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $post_id = $_POST['post_id'];

    $sql = "UPDATE posts SET title = ?, content = ? WHERE post_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $title, $content, $post_id);
    mysqli_stmt_execute($stmt);

    header("location: news.php");
    exit;
} else {
    $post_id = $_GET['post_id'];

    $sql = "SELECT * FROM posts WHERE post_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $post_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $post = mysqli_fetch_assoc($result);
}

mysqli_close($conn);
?>

<!doctype html>
<html lang="pl" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MJO | Edytuj post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
            <h2>Edytuj post</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
                <div class="form-group mb-3">
                    <label>Tytuł</label>
                    <input type="text" name="title" class="form-control" value="<?php echo $post['title']; ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label>Zawartość</label>
                    <textarea name="content" class="form-control" required><?php echo $post['content']; ?></textarea>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Edytuj">
                </div>
            </form>
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