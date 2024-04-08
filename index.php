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
    <title>MJO | Strona główna</title>
    <link rel="shortcut icon" href="favicon.jpg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .card:hover {
            transform: scale(1.01);
            transition: transform .5s ease-in-out;

        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">MJO</a>
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
            <h1 class="text-center my-4">Twój dźwięk</h1>
            <p class="lead text-center mb-5">Nasza pasja!</p>
            <div class="row">
                <div class="col-lg-6 d-flex">
                    <div class="card shadow-lg mb-4 flex-fill">
                        <img src="assets/studio.jpg" alt="Wizualizacja studia nagraniowego" class="card-img-top">
                    </div>
                </div>
                <div class="col-lg-6 d-flex">
                    <div class="card shadow-lg mb-4 flex-fill">
                        <div class="card-body">
                            <h2 class="card-title">O nas</h2>
                            <p class="card-text">MJO to innowacyjna wytwórnia muzyczna, która zaczęła swoją działalność
                                w
                                2023 roku.
                                Jednym z głównych filarów działalności MJO jest promowanie nowych talentów i wspieranie
                                ich
                                w
                                rozwoju kariery. Wytwórnia zapewnia artystom wsparcie w procesie nagrywania, produkcji
                                oraz
                                promocji
                                ich utworów.
                                MJO Music Production nie tylko stawia na rozwój artystyczny, ale również dba o wysoką
                                jakość
                                dźwięku
                                i produkcji.
                                MJO Music Production to miejsce, gdzie pasja do muzyki łączy się z profesjonalizmem i
                                innowacyjnością, tworząc inspirujące środowisko dla artystów i miłośników muzyki na
                                całym
                                świecie.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="container my-5">
            <h2 class="text-center mb-5">Artyści z którymi współpracujemy</h2>
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <img src="assets/artist1.jpg" class="card-img-top" alt="Zylophonic Triton">
                        <div class="card-body">
                            <h5 class="card-title">Zylophonic Triton</h5>
                            <p class="card-text">Elektroniczny artysta, który komponuje muzykę wyłącznie za pomocą
                                instrumentów stworzonych z organicznych materiałów, takich jak drewno i kamienie. Jego
                                melodie brzmią jak odgłosy natury splecione z elektronicznymi dźwiękami.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <img src="assets/artist2.jpg" class="card-img-top" alt="Luna Seraphim">
                        <div class="card-body">
                            <h5 class="card-title">Luna Seraphim</h5>
                            <p class="card-text">Piosenkarka operowa z fantastycznym głosem, który tworzy dźwięki
                                nieznane w
                                żadnym innym gatunku muzycznym. Jej utwory przypominają mistyczne opowieści, a jej głos
                                jest
                                porównywany do anielskiego chóru.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <img src="assets/artist3.jpg" class="card-img-top" alt="Nebula Beatmaster">
                        <div class="card-body">
                            <h5 class="card-title">Nebula Beatmaster</h5>
                            <p class="card-text">Wirtuoz grający na instrumentach stworzonych z kosmicznych materiałów.
                                Jego
                                muzyka transportuje słuchaczy w podróż przez nieznane galaktyki, łącząc w sobie elementy
                                techno, ambientu i dźwięków inspirowanych kosmosem.</p>
                        </div>
                    </div>
                </div>
            </div>
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