<?php
include 'connect.php'; // Dołącz plik z danymi do połączenia z bazą danych
session_start(); // Rozpocznij sesję
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php"); // Jeżeli użytkownik jest już zalogowany, przekieruj go do strony głównej
    exit;
}
$register_message = ""; // Inicjalizacja wiadomości o rejestracji
$show_toast = false; // Inicjalizacja zmiennej decydującej o wyświetleniu toastu
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Jeżeli formularz został wysłany
    $username = mysqli_real_escape_string($conn, $_POST['username']); // Oczyszczanie danych wejściowych
    $password = mysqli_real_escape_string($conn, $_POST['password']); // Oczyszczanie danych wejściowych
    $email = mysqli_real_escape_string($conn, $_POST['email']); // Oczyszczanie danych wejściowych

    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hashowanie hasła

    $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'"; // Zapytanie do bazy danych sprawdzające, czy użytkownik o podanej nazwie lub adresie e-mail już istnieje
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) { // Jeżeli użytkownik o podanej nazwie lub adresie e-mail już istnieje
        $register_message = "Użytkownik o takiej nazwie lub adresie e-mail już istnieje";
        $show_toast = true;
    } else { // Jeżeli użytkownik o podanej nazwie lub adresie e-mail nie istnieje
        $sql = "INSERT INTO users (username, password, email, admin) VALUES ('$username', '$hashed_password', '$email','0')"; // Zapytanie do bazy danych dodające nowego użytkownika

        if (mysqli_query($conn, $sql)) { // Jeżeli zapytanie do bazy danych zostało wykonane poprawnie
            $register_message = "Zarejestrowano";
            $show_toast = true;
            echo "<script>setTimeout(function(){ window.location.href = 'login.php'; }, 3000);</script>"; // Przekierowanie do strony logowania po 3 sekundach
        } else { // Jeżeli zapytanie do bazy danych nie zostało wykonane poprawnie
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    mysqli_close($conn); // Zamknięcie połączenia z bazą danych
}
?>

<!doctype html>
<html lang="pl" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MJO | Rejestracja</title>
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
            <h1 class="text-center my-4">Rejestracja</h1>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-3">
                    <label for="username" class="form-label">Nazwa użytkownika</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                    <div class="invalid-feedback">
                        Użytkownik o takiej nazwie już istnieje.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Hasło</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Powtórz hasło</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    <div class="invalid-feedback">
                        Hasła nie są takie same
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">
                        Użytkownik o takim adresie e-mail już istnieje.
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" id="register" class="btn btn-primary mx-auto" disabled>Zarejestruj</button>
                </div>

            </form>
            <div class="toast-container top-0 end-0 p-3">
                <div class="toast align-items-center text-white <?php echo $register_message == 'Zarejestrowano' ? 'bg-success' : 'bg-danger'; ?> border-0 "
                    data-bs-autohide="true" id="registerToast">
                    <div class="d-flex">
                        <div class="toast-body">
                            <?php echo $register_message; ?>
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
                var username = $("#username").val();
                var email = $("#email").val();
                var password = $("#password").val();
                var confirmPassword = $("#confirm_password").val();

                // Jeżeli wszystkie pola są wypełnione, hasła są takie same i nie ma błędów, przycisk rejestracji staje się aktywny
                if (username && email && password && confirmPassword && password === confirmPassword && !$('#username').hasClass('is-invalid') && !$('#email').hasClass('is-invalid')) {
                    $('#register').prop('disabled', false);
                } else {
                    // W przeciwnym wypadku przycisk rejestracji jest nieaktywny
                    $('#register').prop('disabled', true);
                }
            }

            // Wywołanie funkcji checkForm po każdym wpisaniu znaku w pola hasła i potwierdzenia hasła
            $("#password, #confirm_password").on('keyup', function () {
                var password = $("#password").val();
                var confirmPassword = $("#confirm_password").val();

                // Jeżeli hasła nie są takie same, wyświetlany jest błąd
                if (password && confirmPassword && password !== confirmPassword) {
                    $('#confirm_password').addClass('is-invalid');
                } else {
                    $('#confirm_password').removeClass('is-invalid');
                }
                checkForm();
            });

            // Wywołanie funkcji checkForm po każdym wpisaniu znaku w pola nazwy użytkownika i adresu e-mail
            $("#username, #email").on('blur', function () {
                var username = $("#username").val();
                var email = $("#email").val();

                // Wywołanie funkcji AJAX sprawdzającej, czy użytkownik o podanej nazwie lub adresie e-mail już istnieje
                $.ajax({
                    url: 'check_username.php',
                    type: 'post',
                    data: {
                        username: username,
                        email: email
                    },
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data.username) {
                            $('#username').addClass('is-invalid');
                        } else {
                            $('#username').removeClass('is-invalid');
                        }
                        if (data.email) {
                            $('#email').addClass('is-invalid');
                        } else {
                            $('#email').removeClass('is-invalid');
                        }
                        checkForm();
                    }
                });
            });

            // Wywołanie funkcji checkForm po każdym wpisaniu znaku w pola formularza
            $("#username, #email, #password, #confirm_password").on('keyup', function () {
                checkForm();
            });
            <?php if ($register_message): ?>
                // Jeżeli zmienna $register_message jest prawdziwa, wyświetlany jest toast
                var toastEl = document.getElementById('registerToast');
                var toast = new bootstrap.Toast(toastEl);
                toast.show();
            <?php endif; ?>
        });
    </script>
</body>

</html>