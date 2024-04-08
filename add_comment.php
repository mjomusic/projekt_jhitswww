<?php
session_start(); // Rozpocznij sesję
include 'connect.php';

// Pobierz ID użytkownika z bazy danych za pomocą nazwy użytkownika
$username = $_SESSION['username'];
$sql = "SELECT user_id FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
$user_id = $user['user_id'];

// Dodaj komentarz do bazy danych
$post_id = $_POST['post_id'];
$comment = $_POST['comment'];
$date = date('Y-m-d H:i:s'); // Pobierz aktualną datę i godzinę
$sql = "INSERT INTO comments (post_id, user_id, content, date) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "iiss", $post_id, $user_id, $comment, $date);
mysqli_stmt_execute($stmt);

// Zamknij połączenie z bazą danych
mysqli_close($conn);

// Przekieruj z powrotem na stronę posta
header("Location: post.php?id=" . $post_id);
?>