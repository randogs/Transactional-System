<?php

include "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = trim($_POST["first_name"]);
    $middle_name = trim($_POST["middle_name"]);
    $last_name = trim($_POST["last_name"]);
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $birthday = $_POST["birthday"];
    $email = trim($_POST["email"]);
    $contact_number = trim($_POST["contact_number"]);

    if($password !== $confirm_password) {

        $message = "Passwords do not match.";

    } else {

        $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check->bind_param("ss", $username, $email);
        $check->execute();

        $result = $check->get_result();

        if($result->num_rows > 0) {

            $message = "Username or Email already exists.";

        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("
                INSERT INTO users
                (
                    first_name,
                    middle_name,
                    last_name,
                    username,
                    password,
                    birthday,
                    email,
                    contact_number
                )
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "ssssssss",
                $first_name,
                $middle_name,
                $last_name,
                $username,
                $hashedPassword,
                $birthday,
                $email,
                $contact_number
            );
           if ($stmt->execute()) {

    $stmt->close();
    $check->close();
    $conn->close();

    header("Location: login.php?registered=1");
    exit();

} else {

    $message = "Something went wrong. Please try again.";

}

$stmt->close();
$check->close();

}

        }

    }



$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CareSync | Register</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

<div class="container">

    <!-- LEFT PANEL -->

    <div class="left">

        <i class="bi bi-heart-pulse-fill"></i>

        <h1>CareSync</h1>

        <h3>Your Digital Healthcare Companion</h3>

        <p>
            Book appointments, manage patient records,
            and connect with healthcare professionals
            through one secure platform.
        </p>

    </div>

    <div class="right">

        <h2>Create Account</h2>

        <p class="subtitle">
            Register to continue using CareSync.
        </p>

        <?php if (!empty($message)): ?>

        <div class="<?= strpos($message, 'Successful') !== false ? 'success' : 'error'; ?>">

        <?= htmlspecialchars($message); ?>

        </div>

        <?php endif; ?>

        <form action="" method="POST">

            <div>

                <label>First Name</label>

                <div class="input-box">

                    <i class="bi bi-person"></i>

                    <input
                        type="text"
                        name="first_name"
                        placeholder="Enter First Name"
                        required>

                </div>

            </div>

            <div>

                <label>Middle Name</label>

                <div class="input-box">

                    <i class="bi bi-person"></i>

                    <input
                        type="text"
                        name="middle_name"
                        placeholder="Enter Middle Name">

                </div>

            </div>

            <div>

                <label>Last Name</label>

                <div class="input-box">

                    <i class="bi bi-person"></i>

                    <input
                        type="text"
                        name="last_name"
                        placeholder="Enter Last Name"
                        required>

                </div>

            </div>

            <div>

                <label>Username</label>

                <div class="input-box">

                    <i class="bi bi-person-circle"></i>

                    <input
                        type="text"
                        name="username"
                        placeholder="Choose Username"
                        required>

                </div>

            </div>

            <div>

                <label>Password</label>

                <div class="input-box">

                    <i class="bi bi-lock"></i>

                    <input
                        type="password"
                        name="password"
                        placeholder="Enter Password"
                        required>

                </div>

            </div>

            <div>

                <label>Confirm Password</label>

                <div class="input-box">

                    <i class="bi bi-lock-fill"></i>

                    <input
                        type="password"
                        name="confirm_password"
                        placeholder="Confirm Password"
                        required>

                </div>

            </div>

            <div>

                <label>Birthday</label>

                <div class="input-box">

                    <i class="bi bi-calendar-event"></i>

                    <input
                        type="date"
                        name="birthday"
                        required>

                </div>

            </div>

            <div>

                <label>Email Address</label>

                <div class="input-box">

                    <i class="bi bi-envelope"></i>

                    <input
                        type="email"
                        name="email"
                        placeholder="example@email.com"
                        required>

                </div>

            </div>

            <div class="full">

                <label>Contact Number</label>

                <div class="input-box">

                    <i class="bi bi-telephone"></i>

                    <input
                        type="text"
                        name="contact_number"
                        placeholder="09XXXXXXXXX"
                        required>

                </div>

            </div>

            <button type="submit">

                <i class="bi bi-person-plus-fill"></i>

                Create Account

            </button>

        </form>

        <div class="footer">

            Already have an account?

            <a href="login.php">Login</a>

        </div>

    </div>

</div>

</body>

</html>