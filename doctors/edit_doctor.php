<?php

session_start();

include "../config/database.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit();

}

if (!isset($_GET["id"])) {

    header("Location: view_doctors.php");
    exit();

}

$id = (int)$_GET["id"];

$stmt = $conn->prepare("SELECT * FROM doctors WHERE doctor_id = ?");

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows != 1) {

    header("Location: view_doctors.php");
    exit();

}

$doctor = $result->fetch_assoc();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $doctor_name = trim($_POST["doctor_name"]);
    $specialization = trim($_POST["specialization"]);
    $contact_number = trim($_POST["contact_number"]);
    $email = trim($_POST["email"]);

    $stmt = $conn->prepare("
        UPDATE doctors
        SET
            doctor_name=?,
            specialization=?,
            contact_number=?,
            email=?
        WHERE doctor_id=?
    ");

    $stmt->bind_param(
        "ssssi",
        $doctor_name,
        $specialization,
        $contact_number,
        $email,
        $id
    );

    if ($stmt->execute()) {

        header("Location: view_doctors.php");
        exit();

    } else {

        $message = "Failed to update doctor.";

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>CareSync | Edit Doctor</title>

<link rel="stylesheet" href="../css/style.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

<div class="container">

    <div class="left">

        <i class="bi bi-person-plus-fill"></i>

        <h1>CareSync</h1>

        <h3>Edit Doctor Record</h3>

        <p>

             Update an existing doctor record
             in the CareSync system.

        </p>

    </div>

    <div class="right">

        <h2>Edit Doctor</h2>

        <p class="subtitle">

            Update the doctor's information.

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
                value="<?= htmlspecialchars($doctor["doctor_name"]) ?>"
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
                value="<?= htmlspecialchars($doctor["specialization"]) ?>"
                required>

        </div>

    </div>

    <div>

        <label>Contact Number</label>

        <div class="input-box">

            <i class="bi bi-telephone"></i>

            <input
                type="text"
                name="contact_number"
                value="<?= htmlspecialchars($doctor["contact_number"]) ?>"
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
                value="<?= htmlspecialchars($doctor["email"]) ?>"
                required>

        </div>

    </div>

 <div class="full" style="text-align:center;">

    <button
        type="submit"
        style="display:inline-block; width:250px; margin-top:15px;">

        <i class="bi bi-save"></i>

        Update Doctor

    </button>

</div>

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