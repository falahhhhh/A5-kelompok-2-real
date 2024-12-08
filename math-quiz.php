<?php
session_start(); // Memulai session
require 'connection.php'; // Hubungkan ke database

// Fungsi untuk mengambil soal berdasarkan tingkat kesulitan dari database
function getQuestionFromDatabase($difficulty)
{
    global $conn; // Mengakses variabel koneksi
    $stmt = $conn->prepare("SELECT id_soal, question, answer FROM questions WHERE difficulty = ? ORDER BY RAND() LIMIT 1");
    $stmt->bind_param('s', $difficulty);
    $stmt->execute();
    $stmt->bind_result($id_soal, $question, $answer);
    if ($stmt->fetch()) {
        $_SESSION['current_question_id'] = $id_soal; // Simpan ID soal yang diambil
        $_SESSION['question'] = $question;
        $_SESSION['answer'] = $answer;
    } else {
        // Jika tidak ada soal, reset sesi
        unset($_SESSION['question']);
        unset($_SESSION['answer']);
    }
    $stmt->close();
}

// Ambil tingkat kesulitan dari sesi
$difficulty = isset($_SESSION['difficulty']) ? $_SESSION['difficulty'] : 'mudah'; // Default ke 'mudah'

// Ambil soal jika tombol ditekan
if (isset($_POST['generateQuestion'])) {
    getQuestionFromDatabase($difficulty);
}

// Tampilkan soal dari sesi
$question = isset($_SESSION['question']) ? htmlspecialchars($_SESSION['question']) : 'Pilih tingkat kesulitan dan tekan tombol untuk memulai soal!';

// Umpan balik jawaban
$feedback = '';
if (isset($_POST['submitAnswer'])) {
    $userAnswer = isset($_POST['answer']) ? intval($_POST['answer']) : null;
    $correctAnswer = isset($_SESSION['answer']) ? $_SESSION['answer'] : null;

    $isCorrect = ($userAnswer === $correctAnswer) ? 1 : 0;

    // Simpan ke database
    if (isset($_SESSION['question'])) {
        $stmt = $conn->prepare("INSERT INTO scores (player_name, question, correct_answer, user_answer, is_correct, user_id, question_id) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $playerName = 'Player1'; // Ganti sesuai input pemain atau sesi
        $userId = 1; // Ganti sesuai ID pengguna yang sedang login
        $stmt->bind_param('ssiiiii', $playerName, $_SESSION['question'], $correctAnswer, $userAnswer, $isCorrect, $userId, $_SESSION['current_question_id']);
        $stmt->execute();
        $stmt->close();
    }

    if ($userAnswer === $correctAnswer) {
        $feedback = '<span style="color: green;">Benar! Jawaban Anda tepat.</span>';
    } else {
        $feedback = '<span style="color: red;">Salah! Jawaban yang benar adalah ' . htmlspecialchars($correctAnswer) . '.</span>';
    }

    // Ambil soal berikutnya berdasarkan tingkat kesulitan
    getQuestionFromDatabase($difficulty);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soal Matematika Acak</title>
    <link rel="stylesheet" href="math-quiz.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Soal Matematika Acak</h1>
        </header>
        <div class="question-area">
            <p id="question"><?= $question ?></p>

            <form method="POST" action="">
                <input type="number" name="answer" id="answer" placeholder="Masukkan jawaban Anda" required>
                <button type="submit" name="submitAnswer">Kirim Jawaban</button>
            </form>
        </div>

        <footer>
            <form method="POST" action="">
                <button type="submit" name="generateQuestion" id="generateQuestion">Buat Soal Baru</button>
            </form>
            <p id="feedback"><?= $feedback ?></p>
        </footer>
    </div>
</body>
</html>