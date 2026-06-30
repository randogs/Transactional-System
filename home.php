<?php

session_start();

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>CareSync | Home</title>

<link rel="stylesheet" href="css/style.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

<div class="container">

    <div class="left">

        <i class="bi bi-heart-pulse-fill"></i>

        <h1>CareSync</h1>

        <h3>Welcome!</h3>

        <p>
            You are now logged in.
        </p>

    </div>

    <div class="right">

        <h2>

            Welcome,

            <?= htmlspecialchars($_SESSION["first_name"]) ?>

        </h2>

        <br>

        <p>

            <strong>Username:</strong>

            <?= htmlspecialchars($_SESSION["username"]) ?>

        </p>

        <br>

        <p>

            <strong>Email:</strong>

            <?= htmlspecialchars($_SESSION["email"]) ?>

        </p>

        <br>

        <p>

            <strong>Birthday:</strong>

            <?= htmlspecialchars($_SESSION["birthday"]) ?>

        </p>

        <br>

        <p>

            <strong>Contact Number:</strong>

            <?= htmlspecialchars($_SESSION["contact_number"]) ?>

        </p>

        <br><br>

        <a href="reset_password.php">

            Reset Password

        </a>

        |

        <a href="logout.php">

            Logout

        </a>

    </div>

</div>

</body>

</html>