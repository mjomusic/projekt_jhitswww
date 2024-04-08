<?php
session_start(); // Rozpoczęcie sesji

// Sprawdzenie, czy użytkownik jest zalogowany i jest administratorem, jeśli nie - przekierowanie na stronę logowania
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["admin"] !== '1') {
    header("location: login.php");
    exit;
}

// Dołączenie pliku z połączeniem do bazy danych
include 'connect.php';

// Sprawdzenie, czy w URL jest parametr user_id
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Pobranie użytkownika
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    // Zmiana statusu admina
    $new_admin_status = $user['admin'] == '1' ? '0' : '1';

    // Aktualizacja użytkownika w bazie danych
    $sql = "UPDATE users SET admin = ? WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $new_admin_status, $user_id);
    mysqli_stmt_execute($stmt);

    // Przekierowanie z powrotem na stronę admina
    header("location: admin.php");
    exit;
}

// Sprawdzenie, czy formularz został wysłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $username = $_SESSION['username']; // Pobranie nazwy użytkownika z sesji
    $date = date('Y-m-d H:i:s'); // Pobranie aktualnej daty i czasu

    // Obsługa przesyłania obrazu posta
    $target_dir = "assets/posts/";
    $target_file = $target_dir . basename($_FILES["post_image"]["name"]);
    move_uploaded_file($_FILES["post_image"]["tmp_name"], $target_file);

    // Pobranie id użytkownika
    $sql = "SELECT user_id FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    $user_id = $user['user_id'];

    // Wstawienie posta do bazy danych
    $sql = "INSERT INTO posts (title, content, user_id, date, image) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssiss", $param_title, $param_content, $param_user_id, $param_date, $param_image);
        $param_title = $title;
        $param_content = $content;
        $param_user_id = $user_id;
        $param_date = $date;
        $param_image = $target_file;
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['post_created'] = true;
        } else {
            echo "Coś poszło nie tak. Spróbuj ponownie później.";
        }
    }
    mysqli_stmt_close($stmt);
}

// Pobranie wszystkich użytkowników
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_close($conn); // Zamknięcie połączenia z bazą danych

?>
<!doctype html>
<html lang="pl" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MJO | Admin</title>
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
        <section class="container my-5">
            <h2>Stwórz post</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                enctype="multipart/form-data">
                <div class="form-group mb-3">
                    <label>Tytuł</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label>Zawartość</label>
                    <textarea name="content" class="form-control" required></textarea>
                </div>
                <div class="form-group mb-3">
                    <label>Zdjęcie</label>
                    <input type="file" name="post_image" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <input type="submit" class="btn btn-primary" value="Dodaj">
                </div>
            </form>
        </section>

        <section class="container my-5">
            <h2>Zarządzanie użytkownikami</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nazwa użytkownika</th>
                        <th>Admin</th>
                        <th>Akcja</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <?php if ($user['username'] !== $_SESSION['username']): ?>
                            <tr>
                                <td>
                                    <?php echo $user['username']; ?>
                                </td>
                                <td>
                                    <?php echo $user['admin'] ? 'Tak' : 'Nie'; ?>
                                </td>
                                <td>
                                    <?php if ($_SESSION['username'] == 'admin'): ?>
                                        <a href="admin.php?user_id=<?php echo $user['user_id']; ?>" class="btn btn-primary">Zmień
                                            status
                                            admina</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
    <footer class="footer mt-auto py-3 bg-dark text-white">
        <div class="container text-center">
            <span>&copy; 2024 MJO</span>
        </div>
    </footer>
    <div class="toast-container top-0 end-0 p-3">
        <div class="toast align-items-center text-white <?php echo isset($_SESSION['post_created']) ? 'bg-success' : ''; ?> border-0"
            data-bs-autohide="true" id="postToast">
            <div class="d-flex">
                <div class="toast-body">
                    <?php echo isset($_SESSION['post_created']) ? 'Post został pomyślnie utworzony.' : ''; ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script>
        <?php if (isset($_SESSION['post_created'])): ?>
            var toastEl = document.getElementById('postToast');
            var toast = new bootstrap.Toast(toastEl);
            toast.show();
            <?php unset($_SESSION['post_created']); ?>
        <?php endif; ?>
    </script>
</body>

</html>