<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include "config/database.php";

$message = "";

if (isset($_GET["registered"])) {
    $message = "Registration successful! Please log in.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["first_name"] = $user["first_name"];
            $_SESSION["last_name"] = $user["last_name"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["birthday"] = $user["birthday"];
            $_SESSION["contact_number"] = $user["contact_number"];

            if (isset($_POST["remember"])) {

    setcookie(
        "username",
        $username,
        time() + (86400 * 30),
        "/"
    );

    setcookie(
        "password",
        $password,
        time() + (86400 * 30),
        "/"
    );

} else {

    setcookie(
        "username",
        "",
        time() - 3600,
        "/"
    );

    setcookie(
        "password",
        "",
        time() - 3600,
        "/"
    );

}
            $stmt->close();
            $conn->close();

            header("Location: home.php");
            exit();

        } else {

            $message = "Current password is not the same with the old password.";

        }

    } else {

        $message = "Username not found.";

    }

    $stmt->close();
}
    $conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>CareSync | Login</title>

<link rel="stylesheet"
href="css/style.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

<div class="container">

    <div class="left">

        <i class="bi bi-heart-pulse-fill"></i>

        <h1>CareSync</h1>

        <h3>Welcome Back!</h3>

        <p>

            Sign in to securely access your
            appointments and healthcare records.

        </p>

    </div>

    <div class="right">

        <h2>Login</h2>

        <p class="subtitle">

            Enter your account credentials.

        </p>

        <?php if(!empty($message)): ?>

        <div class="<?= strpos(strtolower($message),'successful') !== false ? 'success' : 'error'; ?>">

            <?= htmlspecialchars($message); ?>

        </div>

        <?php endif; ?>

        <form action="" method="POST">

            <div class="full">

                <label>Username</label>

                <div class="input-box">

                    <i class="bi bi-person-circle"></i>

                    <input
                        type="text"
                        name="username"
                        placeholder="Enter Username"
                        value="<?= isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : '' ?>"
                        required>

                </div>

            </div>

            <div class="full">

                <label>Password</label>

                <div class="input-box">

                    <i class="bi bi-lock-fill"></i>

                    <input
                       type="password"
                       name="password"
                       placeholder="Enter Password"
                       value="<?= isset($_COOKIE['password']) ? htmlspecialchars($_COOKIE['password']) : '' ?>"
                       required>

                </div>

            </div>

            <div class="remember">

                <input
                    type="checkbox"
                    name="remember"
                    id="remember">

                <label for="remember">

                    Remember Me

                </label>

            </div>

            <button type="submit">

                <i class="bi bi-box-arrow-in-right"></i>

                Login

            </button>

        </form>

        <div class="footer">

            Don't have an account?

            <a href="register.php">

                Register

            </a>

        </div>

    </div>

</div>

</body>

</html>