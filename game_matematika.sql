-- Membuat database
CREATE DATABASE  game_database;
USE game_database;

-- Membuat tabel users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Membuat tabel questions
CREATE TABLE questions (
    id_soal INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(100) NOT NULL,
    answer INT NOT NULL,
    difficulty ENUM('mudah', 'sedang', 'sulit') NOT NULL
);

-- Membuat tabel player_difficulty
CREATE TABLE player_difficulty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_name VARCHAR(255) NOT NULL,
    difficulty ENUM('mudah', 'sedang', 'sulit') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Membuat tabel scores
CREATE TABLE scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_name VARCHAR(255),
    score INT NOT NULL,
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
CREATE TABLE active_games (
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
CREATE TABLE game_history (
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
('10 + 3', 13, 'mudah'),
('13 + 4', 17, 'mudah'),
('6 + 9', 15, 'mudah'),
('8 + 12', 20, 'mudah'),
('12 - 4', 8, 'mudah'),
('5 - 2', 3, 'mudah'),
('9 - 4', 5, 'mudah'),
('15 - 6', 9, 'mudah'),
('20 - 8', 12, 'mudah'),
('6 * 7', 42, 'sedang'),
('6 * 6', 36, 'sedang'),
('8 * 9', 72, 'sedang'),
('7 * 8', 56, 'sedang'),
('95 - 47', 48, 'sedang'),
('81 - 53', 28, 'sedang'), 
('64 - 29', 35, 'sedang'),
('25 + 17', 42, 'sedang'),
('36 + 29', 65, 'sedang'),
('42 + 58', 100, 'sedang'),
('15 + 9 - 4', 20, 'sulit'),
('15 * 3 + 20', 65, 'sulit'),
('10 + 25 - 30', 5, 'sulit'),
('50 - 6 * 8', 2, 'sulit'),
('10 - 3 * 4', -2, 'sulit'),
('16 * 9 - 50', 94, 'sulit'),
('30 * 4 + 30', 150, 'sulit'),
('12 * 7 - 18', 66, 'sulit'),
('40 - 9 + 54', 85, 'sulit'),
('20 + 15 * 4', 80, 'sulit');
