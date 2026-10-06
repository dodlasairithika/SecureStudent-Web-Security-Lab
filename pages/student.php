<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    die("Access denied. Please login first.");
}

require_once "../config/database.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null) {
    die("Invalid student ID.");
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare(
    "SELECT * FROM students WHERE id = ? AND user_id = ?"
);

$stmt->bind_param("ii", $id, $user_id);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $student = $result->fetch_assoc();

    echo "<h1>Student Details</h1>";
    echo "<p>ID: " . htmlspecialchars($student["id"]) . "</p>";
    echo "<p>Name: " . htmlspecialchars($student["name"]) . "</p>";
    echo "<p>Department: " . htmlspecialchars($student["department"]) . "</p>";
    echo "<p>Year: " . htmlspecialchars($student["year"]) . "</p>";
    echo "<p>Email: " . htmlspecialchars($student["email"]) . "</p>";
    echo "<p>Marks: " . htmlspecialchars($student["marks"]) . "</p>";

} else {

    echo "Access denied. You are not authorized to view this student record.";

}

$stmt->close();
$conn->close();

?>