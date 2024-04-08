<?php
session_start();
include 'connect.php';
$sql = "SELECT * FROM posts ORDER BY post_id DESC";
$result = mysqli_query($conn, $sql);
mysqli_close($conn);
?>
<!doctype html>
<html lang="pl" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MJO | Wyposażenie</title>
    <link rel="shortcut icon" href="favicon.jpg" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
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
                            <a class="nav-link active" aria-current="page" href="studio.php">Wyposażenie</a>
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
            <h1 class="text-center my-4">Wyposażenie</h1>
            <p class="lead text-center mb-5">naszego studia</p>
            <div class="row justify-content-center">
                <div class="col-lg-3 col-6">
                    <figure id="gallery">
                        <a href="assets/microphone.jpg" data-lightbox="mygallery"><img src="assets/microphone.jpg"
                                class="img-thumbnail w-100" alt="Mikrofon studyjny"></a>
                        <figcaption class="text-center">Mikrofon studyjny</figcaption>
                    </figure>
                </div>
                <div class="col-lg-3 col-6">
                    <figure id="gallery">
                        <a href="assets/keys.jpg" data-lightbox="mygallery"><img src="assets/keys.jpg"
                                class="img-thumbnail w-100" alt="Klawisze"></a>
                        <figcaption class="text-center">Klawisze</figcaption>
                    </figure>
                </div>
                <div class="col-lg-3 col-6">
                    <figure id="gallery">
                        <a href="assets/computer.jpg" data-lightbox="mygallery"><img src="assets/computer.jpg"
                                class="img-thumbnail w-100" alt="Image 3"></a>
                        <figcaption class="text-center">Komputer</figcaption>
                    </figure>
                </div>
            </div>
        </section>
    </main>
    <footer class="footer mt-auto py-3 bg-dark text-white fixed-bottom">
        <div class="container text-center">
            <span>&copy; 2024 MJO</span>
        </div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>