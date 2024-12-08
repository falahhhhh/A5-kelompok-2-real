<?php
session_start(); // Memulai session
require 'connection.php'; // Hubungkan ke database

// Periksa apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['difficulty'])) {
        // Ambil tingkat kesulitan dari form
        $difficulty = $_POST['difficulty'];

        // Simpan tingkat kesulitan ke session
        $_SESSION['difficulty'] = $difficulty;

        // Simpan tingkat kesulitan untuk pemain ke database
        $sql = "INSERT INTO player_difficulty (player_name, difficulty) VALUES ('Player1', '$difficulty')";
        $conn->query($sql);

        // Redirect ke halaman permainan (math-quiz.php)
        header("Location: math-quiz.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemilihan Tingkat Kesulitan</title>
    <link rel="stylesheet" href="difficulty.css">
</head>
<body>
    <div class="container">
        <h2>Pilih Tingkat Kesulitan</h2>

        <form method="POST" action="difficulty.php">
            <label for="difficulty">Tingkat Kesulitan:</label>
            <select id="difficulty" name="difficulty">
                <option value="mudah">Mudah</option>
                <option value="sedang">Sedang</option>
                <option value="sulit">Sulit</option>
            </select>
            <button type="submit">Mulai Permainan</button>
        </form>

        <div class="footer">
            <p>Semakin sulit, semakin seru!</p>
        </div>
    </div>
</body>
</html>