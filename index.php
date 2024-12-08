<?php
session_start();
require 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mulai Permainan</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Selamat Datang di Permainan Matematika</h1>
        </header>

        <div class="start-game">
            <p>Tekan tombol di bawah untuk memulai permainan!</p>
            <form method="POST" action="difficulty.php">
                <button type="submit" name="startGame" class="start-button">Mulai Permainan</button>
            </form>
            <form method="POST" action="resetgame.php">
                <button type="submit" class="reset-button">Reset Permainan</button>
            </form>
        </div>
    </div>
</body>
</html>
