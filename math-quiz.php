<?php
session_start(); // Memulai session
require 'connection.php'; // Hubungkan ke database

// Inisialisasi array untuk menyimpan ID soal yang sudah dikerjakan
if (!isset($_SESSION['answered_questions'])) {
    $_SESSION['answered_questions'] = [];
}

// Inisialisasi skor jika belum ada
if (!isset($_SESSION['score'])) {
    $_SESSION['score'] = 0;
}

// Mendapatkan total soal dari database
$result = $conn->query("SELECT COUNT(*) FROM questions");
$row = $result->fetch_row();
define('TOTAL_SOAL', $row[0]);

// Fungsi untuk mengambil soal berdasarkan tingkat kesulitan dari database
function getQuestionFromDatabase($difficulty)
{
    global $conn; // Mengakses variabel koneksi
    $answeredQuestions = $_SESSION['answered_questions'];

    if (empty($answeredQuestions)) {
        $stmt = $conn->prepare("SELECT id_soal, question, answer FROM questions WHERE difficulty = ? ORDER BY RAND() LIMIT 1");
    } else {
        $stmt = $conn->prepare("SELECT id_soal, question, answer FROM questions WHERE difficulty = ? AND id_soal NOT IN (" . implode(',', $answeredQuestions) . ") ORDER BY RAND() LIMIT 1");
    }

    $stmt->bind_param('s', $difficulty);
    $stmt->execute();
    $stmt->bind_result($id_soal, $question, $answer);

    if ($stmt->fetch()) {
        $_SESSION['current_question_id'] = $id_soal;
        $_SESSION['question'] = $question;
        $_SESSION['answer'] = $answer;
        $_SESSION['time_left'] = 10; // Reset timer saat soal baru dibuat
    } else {
        unset($_SESSION['question']);
        unset($_SESSION['answer']);
        header("Location: endgame.php");
        exit();
    }
    $stmt->close();
}

$difficulty = isset($_SESSION['difficulty']) ? $_SESSION['difficulty'] : 'mudah';

if (isset($_POST['generateQuestion'])) {
    getQuestionFromDatabase($difficulty);
}

$question = isset($_SESSION['question']) ? htmlspecialchars($_SESSION['question']) : '';

$feedback = '';
if (isset($_POST['submitAnswer'])) {
    $userAnswer = isset($_POST['answer']) ? intval($_POST['answer']) : null;
    $correctAnswer = isset($_SESSION['answer']) ? $_SESSION['answer'] : null;
    $timeRemaining = isset($_POST['timeRemaining']) ? intval($_POST['timeRemaining']) : 0;

    $isCorrect = ($userAnswer === $correctAnswer) ? 1 : 0;

    if (isset($_SESSION['question'])) {
        $stmt = $conn->prepare("INSERT INTO scores (player_name, question, correct_answer, user_answer, is_correct, user_id, question_id) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $playerName = 'Player1';
        $userId = 1;
        $stmt->bind_param('ssiiiii', $playerName, $_SESSION['question'], $correctAnswer, $userAnswer, $isCorrect, $userId, $_SESSION['current_question_id']);
        $stmt->execute();
        $stmt->close();
    }

    if ($userAnswer === $correctAnswer) {
        $feedback = '<span style="color: green;">Benar! Jawaban Anda tepat.</span>';
        $_SESSION['score'] = isset($_SESSION['score']) ? $_SESSION['score'] + 10 : 10;
    } else {
        $feedback = '<span style="color: red;">Salah! Jawaban yang benar adalah ' . htmlspecialchars($correctAnswer) . '.</span>';
    }

    $_SESSION['answered_questions'][] = $_SESSION['current_question_id'];

    if (count($_SESSION['answered_questions']) >= TOTAL_SOAL) {
        header("Location: endgame.php");
        exit();
    }
}

$timeLeft = isset($_SESSION['time_left']) ? $_SESSION['time_left'] : 10;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soal Matematika dengan Timer</title>
    <link rel="stylesheet" href="math-quiz.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Soal Matematika dengan Timer</h1>
        </header>
        <div class="timer-area">
            <p id="timer">Waktu Tersisa: <span id="time"><?= $timeLeft ?></span> detik</p>
        </div>
        <div class="question-area">
            <p id="question"><?= $question ?></p>
            <form method="POST" action="">
                <input type="number" name="answer" id="answer" placeholder="Masukkan jawaban Anda" required>
                <input type="hidden" name="timeRemaining" id="timeRemaining" value="<?= $timeLeft ?>">
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

    <script>
        let timeLeft = <?= $timeLeft ?>;
        const timerElement = document.getElementById('time');
        const timeRemainingInput = document.getElementById('timeRemaining');
        const answerInput = document.getElementById('answer');
        const submitButton = document.getElementById('submitAnswer');

        function startTimer() {
            const timerInterval = setInterval(() => {
                timeLeft--;
                timerElement.textContent = timeLeft;
                timeRemainingInput.value = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    alert("Waktu habis! Silakan tekan 'Buat Soal Baru' untuk melanjutkan.");
                    answerInput.disabled = true;
                    submitButton.disabled = true;
                }
            }, 1000);
        }

        <?php if (!isset($_POST['submitAnswer'])): ?>
        startTimer();
        <?php endif; ?>
    </script>
</body>
</html>
