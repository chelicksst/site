<?php
// Backend: course.php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle course enrollment
    $userId = $_SESSION['user_id'];
    $courseId = sanitizeInput($_POST['course_id']);
    
    $stmt = $conn->prepare("INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $userId, $courseId);
    $stmt->execute();
}

// Fetch courses
$courses = $conn->query("SELECT * FROM courses LIMIT 10");
?>