<?php

require_once "../config/database.php";

$username = $_GET["username"];

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {

    $user = $result->fetch_assoc();

    echo "<h1>Student Profile</h1>";
    echo "<p>Username: " . htmlspecialchars($user["username"], ENT_QUOTES, 'UTF-8') . "</p>";
    echo "<p>Email: " . htmlspecialchars($user["email"], ENT_QUOTES, 'UTF-8') . "</p>";

} else {

    echo "User not found.";

}

?>