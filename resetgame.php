<?php
session_start();


// Tangani aksi reset permainan
if (isset($_POST['confirmReset'])) {
    session_destroy(); // Hapus semua data sesi
    header("Location: startgame.php"); // Ganti dengan halaman awal permainan
    exit;
}

// Tangani aksi batal reset
if (isset($_POST['cancelReset'])) {
    header("Location: game.php"); // Ganti dengan halaman permainan
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Permainan</title>
    <link rel="stylesheet" href="resetgame.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Reset Permainan</h1>
        </header>

        <div class="reset-area">
            <p id="resetMessage">Apakah Anda ingin memulai ulang permainan?</p>
            <form method="POST" action="">
                <button type="submit" name="confirmReset" id="confirmReset">Ya, Reset Permainan</button>
                <button type="submit" name="cancelReset" id="cancelReset">Tidak, Kembali</button>
            </form>
        </div>
    </div>
</body>
</html>
