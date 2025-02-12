<?php
require_once 'config.php';

// Отключить кеширование
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

// Проверка существования таблиц
$check = $conn->query("SELECT 1 FROM users LIMIT 1");

if ($check !== false) {
    die("Database already installed!");
}

// Создание таблиц
$tables = [
    "CREATE TABLE users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_username (username)
    ) ENGINE=InnoDB",

    "CREATE TABLE courses (
        id INT PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        duration INT NOT NULL,
        instructor VARCHAR(100) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FULLTEXT KEY search_index (title, description)
    ) ENGINE=InnoDB",

    "CREATE TABLE enrollments (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        course_id INT NOT NULL,
        enrollment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        progress INT DEFAULT 0,
        completed TINYINT(1) DEFAULT 0,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
    ) ENGINE=InnoDB",

    "CREATE TABLE quizzes (
        id INT PRIMARY KEY AUTO_INCREMENT,
        course_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        questions JSON NOT NULL,
        passing_score INT DEFAULT 70,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
    ) ENGINE=InnoDB",

    "CREATE TABLE certificates (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        course_id INT NOT NULL,
        issue_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        certificate_hash VARCHAR(255) UNIQUE NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
    ) ENGINE=InnoDB"
];

try {
    // Создание таблиц
    foreach ($tables as $sql) {
        if (!$conn->query($sql)) {
            throw new Exception("Table creation failed: " . $conn->error);
        }
    }
    
    // Добавление тестовых данных
    $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
    $conn->query("INSERT INTO users (username, email, password_hash) VALUES
        ('admin', 'admin@example.com', '$password_hash')");
    
    $conn->query("INSERT INTO courses (title, description, duration, instructor) VALUES
        ('Introduction to PHP', 'Learn PHP basics', 8, 'John Doe'),
        ('Web Development', 'Full-stack development course', 30, 'Jane Smith')");
    
    echo "Installation completed successfully!<br>";
    echo "IMPORTANT: Delete this file from the server!";
    
} catch (Exception $e) {
    die("Installation error: " . $e->getMessage());
} finally {
    $conn->close();
}
?>