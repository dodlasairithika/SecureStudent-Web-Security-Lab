<?php

session_start();

require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$username = $_POST["username"] ?? "";
$password = $_POST["password"] ?? "";

if ($username === "" || $password === "") {
    die("Username and password are required.");
}

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");

$stmt->bind_param("s", $username);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $user = $result->fetch_assoc();

    if (password_verify($password, $user["password"])) {

    session_regenerate_id(true);

    $_SESSION["user_id"] = $user["id"];
    $_SESSION["username"] = $user["username"];
    $_SESSION["role"] = $user["role"];

    echo "Login successful! Welcome, " . htmlspecialchars($user["username"]);

} else {

        echo "Invalid username or password.";

    }

} else {

    echo "Invalid username or password.";

}

$stmt->close();
$conn->close();

?>