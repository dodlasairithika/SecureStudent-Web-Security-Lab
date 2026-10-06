<?php

require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];
    $role = "student";
    $email = $_POST["email"];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO users (username, password, role, email) VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssss",
        $username,
        $hashed_password,
        $role,
        $email
    );

    if ($stmt->execute()) {
        echo "User registered successfully!";
    } else {
        echo "Registration failed.";
    }

    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>SecureStudent - Register</title>
</head>
<body>

    <h1>SecureStudent</h1>
    <h2>Create User</h2>

    <form method="POST">

        <label>Username:</label><br>
        <input type="text" name="username" required>
        <br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required>
        <br><br>

        <label>Role:</label><br>
        <select name="role" required>
            <option value="student">Student</option>
        </select>
        <br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required>
        <br><br>

        <button type="submit">Register</button>

    </form>

</body>
</html>