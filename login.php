<?php
include 'connect.php';
session_start();
$login_message = "";
$show_toast = false;
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $login_message = "Zalogowano";
            $show_toast = true;
            $_SESSION["username"] = $username;
            $_SESSION["loggedin"] = true;
            $_SESSION["admin"] = $user['admin'];
            // Po zalogowaniu przenieś do strony głównej
            header("Location: index.php");
        } else {
            $login_message = "Nieprawidłowe hasło";
            $show_toast = true;
        }
    } else {
        $login_message = "Nieprawidłowa nazwa użytkownika";
        $show_toast = true;
    }

    mysqli_close($conn);
}
?>

<!doctype html>
<html lang="pl" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MJO | Logowanie</title>
    <link rel="shortcut icon" href="favicon.jpg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">MJO</a>

            </div>
        </nav>
    </header>
    <main>
        <section class="container">
            <h1 class="text-center my-4">Zaloguj się</h1>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-3">
                    <label for="username" class="form-label">Nazwa użytkownika</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Hasło</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="d-grid">
                    <button type="submit" id="login" disabled class="btn btn-primary mx-auto">Zaloguj</button>
                </div>
            </form>
            <div class="toast-container top-0 end-0 p-3">
                <div class="toast align-items-center text-white <?php echo $login_message == 'Zalogowano' ? 'bg-success' : 'bg-danger'; ?> border-0 "
                    data-bs-autohide="true" id="loginToast">
                    <div class="d-flex">
                        <div class="toast-body">
                            <?php echo $login_message; ?>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="footer mt-auto py-3 bg-dark text-white fixed-bottom">
        <div class="container text-center">
            <span>&copy; 2024 MJO</span>
        </div>
    </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            // Funkcja sprawdzająca, czy formularz jest wypełniony
            function checkForm() {
                // Pobranie wartości z pól formularza
                var username = $("#username").val();
                var password = $("#password").val();

                // Jeżeli oba pola są wypełnione, przycisk logowania staje się aktywny
                if (username && password) {
                    $('#login').prop('disabled', false);
                } else {
                    // W przeciwnym wypadku przycisk logowania jest nieaktywny
                    $('#login').prop('disabled', true);
                }
            }

            // Wywołanie funkcji checkForm po każdym wpisaniu znaku w pola formularza
            $("#username, #password").on('keyup', function () {
                checkForm();
            });

            // Jeżeli zmienna $show_toast jest prawdziwa, wyświetlany jest toast
            <?php if ($show_toast): ?>
                var toastEl = document.getElementById('loginToast');
                var toast = new bootstrap.Toast(toastEl);
                toast.show();
            <?php endif; ?>
        });
    </script>
</body>

</html>