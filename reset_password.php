<?php

session_start();

include "config/database.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $currentPassword = $_POST["current_password"];
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($newPassword !== $confirmPassword) {

        $message = "New password and Confirm new password should be the same.";

    } else {

        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (password_verify($currentPassword, $user["password"])) {


            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);

            $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->bind_param("si", $newHash, $_SESSION["user_id"]);

            if ($update->execute()) {

                header("Location: home.php?password_updated=1");
                exit();

            } else {

                $message = "Failed to update password.";

            }

            $update->close();

        } else {

            $message = "Current password is incorrect.";

        }

        $stmt->close();

    }

}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CareSync | Reset Password</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

<div class="container">

    <div class="left">

        <i class="bi bi-shield-lock-fill"></i>

        <h1>CareSync</h1>

        <h3>Reset Password</h3>

        <p>

            Keep your account secure by updating
            your password regularly.

        </p>

    </div>

    <div class="right">

        <h2>Change Password</h2>

        <p class="subtitle">

            Update your account password.

        </p>

        <?php if(!empty($message)): ?>

        <div class="<?= strpos(strtolower($message), 'successfully') !== false ? 'success' : 'error'; ?>">

            <?= htmlspecialchars($message); ?>

        </div>

        <?php endif; ?>

        <form action="" method="POST">

            <div class="full">

                <label>Current Password</label>

                <div class="input-box">

                    <i class="bi bi-lock-fill"></i>

                    <input
                        type="password"
                        name="current_password"
                        placeholder="Current Password"
                        required>

                </div>

            </div>

            <div class="full">

                <label>New Password</label>

                <div class="input-box">

                    <i class="bi bi-key-fill"></i>

                    <input
                        type="password"
                        name="new_password"
                        placeholder="New Password"
                        required>

                </div>

            </div>

            <div class="full">

                <label>Confirm New Password</label>

                <div class="input-box">

                    <i class="bi bi-key-fill"></i>

                    <input
                        type="password"
                        name="confirm_password"
                        placeholder="Confirm New Password"
                        required>

                </div>

            </div>

            <button type="submit">

                <i class="bi bi-arrow-repeat"></i>

                Update Password

            </button>

        </form>

        <div class="footer">

            <a href="home.php">

                ← Back to Home

            </a>

        </div>

    </div>

</div>

</body>

</html>