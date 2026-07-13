<?php

session_start();

include "../config/database.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit();

}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $doctor_name = trim($_POST["doctor_name"]);
    $specialization = trim($_POST["specialization"]);
    $contact_number = trim($_POST["contact_number"]);
    $email = trim($_POST["email"]);

    $stmt = $conn->prepare("
    INSERT INTO doctors
    (
        doctor_name,
        specialization,
        contact_number,
        email
    )
    VALUES
    (?, ?, ?, ?)
   ");

    $stmt->bind_param(
    "ssss",
    $doctor_name,
    $specialization,
    $contact_number,
    $email
    );

    if ($stmt->execute()) {

        $message = "Doctor added successfully.";

    } else {

        $message = "Failed to add doctor.";

    }

    $stmt->close();

}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>CareSync | Add Doctor</title>

<link rel="stylesheet" href="../css/style.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

<div class="container">

    <div class="left">

        <i class="bi bi-person-plus-fill"></i>

        <h1>CareSync</h1>

        <h3>Add New Doctor</h3>

        <p>

            Register doctor information
            into the CareSync system.

        </p>

    </div>

    <div class="right">

        <h2>Add Doctor</h2>

        <p class="subtitle">

            Enter the doctor's information.

        </p>

        <?php if(!empty($message)): ?>

        <div class="<?= strpos(strtolower($message),'successfully') !== false ? 'success' : 'error'; ?>">

            <?= htmlspecialchars($message); ?>

        </div>

        <?php endif; ?>

        <form action="" method="POST">

            <div>

                <label>Doctor Name</label>

                <div class="input-box">

                    <i class="bi bi-person-badge"></i>

                    <input
                        type="text"
                        name="doctor_name"
                        placeholder="Enter Doctor Name"
                        required>

                </div>

            </div>

            <div>

    <label>Specialization</label>

    <div class="input-box">

        <i class="bi bi-hospital"></i>

        <input
            type="text"
            name="specialization"
            placeholder="Enter Specialization"
            required>

    </div>

</div>

<div>

    <label>Contact</label>

    <div class="input-box">

        <i class="bi bi-telephone"></i>

        <input
            type="text"
            name="contact_number"
            placeholder="09XXXXXXXXXXX"
            maxlength="11"
            required>

    </div>

</div>

<div>

    <label>Email</label>

    <div class="input-box">

        <i class="bi bi-envelope"></i>

        <input
            type="email"
            name="email"
            placeholder="example@email.com">

    </div>

</div>

            <button type="submit">

                <i class="bi bi-save"></i>

                Save Doctor

            </button>

        </form>

        <div class="footer">

            <a href="../home.php">

                ← Back to Dashboard

            </a>

        </div>

    </div>

</div>

</body>

</html>