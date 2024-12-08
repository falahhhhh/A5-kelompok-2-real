-- Membuat database
CREATE DATABASE IF NOT EXISTS game_database;
USE game_database;


-- Membuat tabel users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Membuat tabel questions
CREATE TABLE IF NOT EXISTS questions (
    id_soal INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(100) NOT NULL,
    answer INT NOT NULL,
    difficulty ENUM('mudah', 'sedang', 'sulit') NOT NULL
);

-- Membuat tabel player_difficulty
CREATE TABLE IF NOT EXISTS player_difficulty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_name VARCHAR(255) NOT NULL,
    difficulty ENUM('mudah', 'sedang', 'sulit') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Membuat tabel scores
CREATE TABLE IF NOT EXISTS scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_name VARCHAR(255),
    question VARCHAR(100),
    correct_answer INT,
    user_answer INT,
    is_correct TINYINT(1),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    question_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (question_id) REFERENCES questions(id_soal)
);

-- Membuat tabel active_games
CREATE TABLE IF NOT EXISTS active_games (
    game_id INT AUTO_INCREMENT PRIMARY KEY,
    player_name VARCHAR(100) NOT NULL,
    difficulty VARCHAR(50) NOT NULL,
    score INT DEFAULT 0,
    time_left INT,
    current_question VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Membuat tabel game_history
CREATE TABLE IF NOT EXISTS game_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_name VARCHAR(100),
    difficulty VARCHAR(50),
    score INT,
    questions_correct INT,
    questions_total INT,
    date_played TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Menambahkan data ke tabel users
INSERT INTO users (username, password, email) VALUES
('player1', '123', 'player1@example.com'),
('player2', '123', 'player2@example.com');

-- Menambahkan data ke tabel questions
INSERT INTO questions (question, answer, difficulty) VALUES
('5 + 3', 8, 'mudah'),
('12 - 4', 8, 'mudah'),
('6 * 7', 42, 'sedang'),
('15 + 9 - 4', 20, 'sulit'),
('10 + 3', 13, 'mudah');