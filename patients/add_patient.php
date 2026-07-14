<?php

session_start();

include "../config/database.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit();

}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = trim($_POST["first_name"]);
    $middle_name = trim($_POST["middle_name"]);
    $last_name = trim($_POST["last_name"]);
    $age = (int)$_POST["age"];
    $gender = $_POST["gender"];
    $address = trim($_POST["address"]);
    $contact_number = trim($_POST["contact_number"]);
    $email = trim($_POST["email"]);

    /* Duplicate Check */

    $check = $conn->prepare("
        SELECT patient_id
        FROM patients
        WHERE
            first_name = ?
            AND middle_name = ?
            AND last_name = ?
            AND contact_number = ?
            AND email = ?
    ");

    $check->bind_param(
        "sssss",
        $first_name,
        $middle_name,
        $last_name,
        $contact_number,
        $email
    );

    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {

        $message = "Patient already exists.";

    } else {

        $stmt = $conn->prepare("
            INSERT INTO patients
            (
                first_name,
                middle_name,
                last_name,
                age,
                gender,
                address,
                contact_number,
                email
            )
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssissss",
            $first_name,
            $middle_name,
            $last_name,
            $age,
            $gender,
            $address,
            $contact_number,
            $email
        );

        if ($stmt->execute()) {

            $message = "Patient added successfully.";

        } else {

            $message = "Failed to add patient.";

        }

        $stmt->close();

    }

    $check->close();

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>CareSync | Add Appointment</title>

<link rel="stylesheet"
      href="../css/style.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>CareSync | Add Patient</title>

<link rel="stylesheet"
      href="../css/style.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

<div class="container">

    <div class="left">

        <i class="bi bi-person-plus-fill"></i>

        <h1>CareSync</h1>

        <h3>Add Patient</h3>

        <p>

            Register a new patient
            into the CareSync system.

        </p>

    </div>

    <div class="right">

        <h2>Add Patient</h2>

        <p class="subtitle">

            Enter the patient information.

        </p>

        <?php if(!empty($message)): ?>

        <div class="<?= strpos(strtolower($message),"successfully") !== false ? "success" : "error"; ?>">

            <?= htmlspecialchars($message) ?>

        </div>

        <?php endif; ?>

        <form action="" method="POST">

<div>

    <label>First Name</label>

    <div class="input-box">

        <i class="bi bi-person-fill"></i>

        <input
            type="text"
            name="first_name"
            placeholder="Enter First Name"
            required>

    </div>

</div>

<!-- Middle Name -->

<div>

    <label>Middle Name</label>

    <div class="input-box">

        <i class="bi bi-person-fill"></i>

        <input
            type="text"
            name="middle_name"
            placeholder="Enter Middle Name">

    </div>

</div>

<!-- Last Name -->

<div>

    <label>Last Name</label>

    <div class="input-box">

        <i class="bi bi-person-fill"></i>

        <input
            type="text"
            name="last_name"
            placeholder="Enter Last Name"
            required>

    </div>

</div>

<!-- Age -->

<div>

    <label>Age</label>

    <div class="input-box">

        <i class="bi bi-calendar"></i>

        <input
            type="number"
            name="age"
            min="1"
            max="120"
            placeholder="Age"
            required>

    </div>

</div>

<!-- Gender -->

<div>

    <label>Gender</label>

    <div class="input-box">

        <select name="gender" required>

            <option value="">Select Gender</option>

            <option value="Male">Male</option>

            <option value="Female">Female</option>

        </select>

    </div>

</div>

<!-- Contact Number -->

<div>

    <label>Contact Number</label>

    <div class="input-box">

        <i class="bi bi-telephone-fill"></i>

        <input
            type="text"
            name="contact_number"
            placeholder="09XXXXXXXXX"
            required>

    </div>

</div>

<!-- Email -->

<div>

    <label>Email Address</label>

    <div class="input-box">

        <i class="bi bi-envelope-fill"></i>

        <input
            type="email"
            name="email"
            placeholder="example@email.com"
            required>

    </div>

</div>

<!-- Address -->

<div class="full">

    <label>Address</label>

    <div class="input-box">

        <textarea
            name="address"
            rows="4"
            placeholder="Enter Address"
            required></textarea>

    </div>

</div>

<button type="submit">

    <i class="bi bi-save"></i>

    Save Patient

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