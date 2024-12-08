<?php
session_start();
require 'connection.php';

$playerName = "Player1"; // Bisa didapatkan dari session atau form
$score = $_GET['score'] ?? 0;

// Simpan skor ke database
$sql = "INSERT INTO scores (player_name, score, date) VALUES ('$playerName', $score, NOW())";

if ($conn->query($sql) === TRUE) {
    echo "Skor berhasil disimpan.";
} else {
    echo "Error: " . $conn->error;
}

// Ambil skor dari session atau atur menjadi 0 jika belum ada
$score = isset($_SESSION['score']) ? $_SESSION['score'] : 0;

// Reset permainan jika tombol restart ditekan
if (isset($_POST['restartGame'])) {
    session_destroy(); // Hapus semua data sesi
    header("Location: startgame.php"); // Ganti dengan halaman awal permainan
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akhir Permainan</title>
    <link rel="stylesheet" href="endgame.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Permainan Selesai!</h1>
        </header>

        <div class="end-game">
            <p id="finalScore">Skor Anda: <span id="score"><?= htmlspecialchars($score) ?></span></p>
            <p id="summary">Terima kasih telah bermain! Klik tombol di bawah untuk bermain lagi.</p>
            <form method="POST" action="">
                <button type="submit" name="restartGame" id="restartGame">Main Lagi</button>
            </form>
        </div>
    </div>
</body>
</html>
