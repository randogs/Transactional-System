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
    $age = $_POST["age"];
    $gender = $_POST["gender"];
    $address = trim($_POST["address"]);
    $contact_number = trim($_POST["contact_number"]);
    $email = trim($_POST["email"]);

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

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>CareSync | Add Patient</title>

<link rel="stylesheet" href="../css/style.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

<div class="container">

    <div class="left">

        <i class="bi bi-person-plus-fill"></i>

        <h1>CareSync</h1>

        <h3>Add New Patient</h3>

        <p>

            Register new patient information
            into the CareSync system.

        </p>

    </div>

    <div class="right">

        <h2>Add Patient</h2>

        <p class="subtitle">

            Enter the patient's information.

        </p>

        <?php if(!empty($message)): ?>

        <div class="<?= strpos(strtolower($message),'successfully') !== false ? 'success' : 'error'; ?>">

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
                        placeholder="Enter Middle Name"
                        required>

                </div>

            </div>

            <div>

                <label>Last Name</label>

                <div class="input-box">

                    <i class="bi bi-person"></i>

                    <input
                        type="text"
                        name="last_name"
                        placeholder="Enter Middle Name"
                        required>

                </div>

            </div>

            <div>

                <label>Age</label>

                <div class="input-box">

                    <i class="bi bi-calendar"></i>

                    <input
                        type="number"
                        name="age"
                        min="1"
                        placeholder="Enter Age"
                        required>

                </div>

            </div>

            <div>

                <label>Gender</label>

                <select name="gender" required>

                    <option value="">Select Gender</option>

                    <option value="Male">Male</option>

                    <option value="Female">Female</option>

                </select>

            </div>

            <div>

                <label>Contact Number</label>

                <div class="input-box">

                    <i class="bi bi-telephone"></i>

                    <input
                        type="text"
                        name="contact_number"
                        placeholder="09XXXXXXXXX"
                        maxlength="11"
                        required>

                </div>

            </div>

            <div class="full">

                <label>Email</label>

                <div class="input-box">

                    <i class="bi bi-envelope"></i>

                    <input
                        type="email"
                        name="email"
                        placeholder="example@email.com">

                </div>

            </div>

            <div class="full">

               <label>Address</label>

                <div class="input-box">

        <textarea
            name="address"
            rows="4"
            placeholder="Enter complete address"
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