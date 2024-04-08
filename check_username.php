<?php
include 'connect.php'; // Dołącz plik z danymi do połączenia z bazą danych

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Oczyszczanie danych wejściowych
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Inicjalizacja tablicy odpowiedzi
    $response = array('username' => false, 'email' => false);

    // Sprawdzenie, czy nazwa użytkownika jest już w bazie danych
    $sql = "SELECT username FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $response['username'] = true;
    }

    // Sprawdzenie, czy email jest już w bazie danych
    $sql = "SELECT email FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $response['email'] = true;
    }

    // Zwrócenie odpowiedzi jako JSON
    echo json_encode($response);

    // Zamknięcie połączenia z bazą danych
    mysqli_close($conn);
}
?>