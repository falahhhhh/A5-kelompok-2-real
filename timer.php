<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timer untuk Setiap Soal</title>
    <link rel="stylesheet" href="timer.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Timer untuk Soal Matematika</h1>
        </header>

        <?php
        session_start();

        // Fungsi untuk menghasilkan soal acak
        function generateRandomQuestion()
        {
            $operators = ['+', '-', '*'];
            $num1 = rand(1, 20);
            $num2 = rand(1, 20);
            $operator = $operators[array_rand($operators)];

            switch ($operator) {
                case '+':
                    $result = $num1 + $num2;
                    break;
                case '-':
                    $result = $num1 - $num2;
                    break;
                case '*':
                    $result = $num1 * $num2;
                    break;
            }

            $_SESSION['question'] = "$num1 $operator $num2";
            $_SESSION['answer'] = $result;
        }

        // Jika permainan dimulai
        if (isset($_POST['startGame'])) {
            generateRandomQuestion();
        }

        // Jika jawaban dikirim
        $feedback = '';
        if (isset($_POST['submitAnswer'])) {
            $userAnswer = isset($_POST['answer']) ? intval($_POST['answer']) : null;
            $correctAnswer = isset($_SESSION['answer']) ? $_SESSION['answer'] : null;

            if ($userAnswer === $correctAnswer) {
                $feedback = '<span style="color: green;">Benar! Jawaban Anda tepat.</span>';
                generateRandomQuestion(); // Buat soal baru
            } else {
                $feedback = '<span style="color: red;">Salah! Jawaban yang benar adalah ' . $correctAnswer . '.</span>';
            }
        }

        $question = isset($_SESSION['question']) ? $_SESSION['question'] : 'Tekan tombol "Mulai Permainan" untuk memulai!';
        ?>

        <div class="timer-area">
            <p id="timer">Waktu Tersisa: <span id="time">10</span> detik</p>
            <p id="question"><?= htmlspecialchars($question) ?></p>
            <form method="POST" action="">
                <input type="number" name="answer" id="answer" placeholder="Masukkan jawaban Anda">
                <button type="submit" name="submitAnswer" id="submitAnswer">Kirim Jawaban</button>
            </form>
        </div>

        <footer>
            <form method="POST" action="">
                <button type="submit" name="startGame" id="startGame">Mulai Permainan</button>
            </form>
            <p id="feedback"><?= $feedback ?></p>
        </footer>
    </div>

    <script>
        // Timer
        let timeLeft = 10;
        const timerElement = document.getElementById('time');
        const answerInput = document.getElementById('answer');
        const submitButton = document.getElementById('submitAnswer');

        function startTimer() {
            const timerInterval = setInterval(() => {
                timeLeft--;
                timerElement.textContent = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    alert("Waktu habis! Silakan tekan 'Mulai Permainan' untuk soal baru.");
                    answerInput.disabled = true;
                    submitButton.disabled = true;
                }
            }, 1000);
        }

        // Mulai timer saat permainan dimulai
        document.getElementById('startGame').addEventListener('click', () => {
            timeLeft = 10;
            timerElement.textContent = timeLeft;
            answerInput.disabled = false;
            submitButton.disabled = false;
            startTimer();
        });
    </script>
</body>
</html>
