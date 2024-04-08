<?php
session_start();
include 'connect.php';
$sql = "SELECT * FROM releases ORDER BY release_id DESC";
$result = mysqli_query($conn, $sql);
mysqli_close($conn);
?>
<!doctype html>
<html lang="pl" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MJO | Wydania</title>
    <link rel="shortcut icon" href="favicon.jpg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

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
                            <a class="nav-link active" aria-current="page" href="releases.php">Wydania</a>
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
            <h1 class="text-center my-4">Najnowsze wydania</h1>
            <div class="row">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-lg">
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <div class="embed-responsive embed-responsive-1by1">
                                    <iframe class="embed-responsive-item" data-lnk-widget
                                        src="<?php echo $row['lnk_to_link']; ?>/widget" width="300" height="300"
                                        frameborder="0" allowtransparency="true"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>
    <footer class="footer mt-auto py-3 bg-dark text-white fixed-bottom">
        <div class="container text-center">
            <span>&copy; 2024 MJO</span>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://staticcloud.linkfire.com/widget-embed/v1/embed.min.js"></script>
</body>

</html>