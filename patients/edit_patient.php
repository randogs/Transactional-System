<?php

session_start();

include "../config/database.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit();

}

if (!isset($_GET["id"])) {

    header("Location: view_patients.php");
    exit();

}

$id = (int)$_GET["id"];

$stmt = $conn->prepare("SELECT * FROM patients WHERE patient_id = ?");

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows != 1) {

    header("Location: view_patients.php");
    exit();

}

$patient = $result->fetch_assoc();

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
        UPDATE patients
        SET
            first_name=?,
            middle_name=?,
            last_name=?,
            age=?,
            gender=?,
            address=?,
            contact_number=?,
            email=?
        WHERE patient_id=?
    ");

    $stmt->bind_param(
        "sssissssi",
        $first_name,
        $middle_name,
        $last_name,
        $age,
        $gender,
        $address,
        $contact_number,
        $email,
        $id
    );

    if ($stmt->execute()) {

        header("Location: view_patients.php");
        exit();

    } else {

        $message = "Failed to update patient.";

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>CareSync | Edit Patient</title>

<link rel="stylesheet" href="../css/style.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

<div class="container">

    <div class="left">

        <i class="bi bi-person-plus-fill"></i>

        <h1>CareSync</h1>

        <h3>Edit Patient Record</h3>

        <p>

             Update an existing patient record
             in the CareSync system.

        </p>

    </div>

    <div class="right">

        <h2>Edit Patient</h2>

        <p class="subtitle">

            Update the patient's information.

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
                        value="<?= htmlspecialchars($patient["first_name"]) ?>"
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
                        value="<?= htmlspecialchars($patient["middle_name"]) ?>"
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
                        value="<?= htmlspecialchars($patient["last_name"]) ?>"
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
                        min="0"
                        max="120"
                        value="<?= htmlspecialchars($patient["age"]) ?>"
                        required>

                </div>

            </div>

            <div>

                <label>Gender</label>

                <select name="gender" required>

    <option value="">Select Gender</option>

    <option value="Male"
        <?= $patient["gender"] == "Male" ? "selected" : "" ?>>

        Male

    </option>

    <option value="Female"
        <?= $patient["gender"] == "Female" ? "selected" : "" ?>>

        Female

    </option>

</select>

            </div>

            <div>

                <label>Contact Number</label>

                <div class="input-box">

                    <i class="bi bi-telephone"></i>

                    <input
                        type="text"
                        name="contact_number"
                        value="<?= htmlspecialchars($patient["contact_number"]) ?>"
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
                        value="<?= htmlspecialchars($patient["email"]) ?>"
                        required>

                </div>

            </div>

            <div class="full">

    <label>Address</label>

    <div class="input-box">

        <textarea
            style="width:35%; min-height:90px;"
            name="address"
            rows="4"
            placeholder="Enter complete address"
            required><?= htmlspecialchars($patient["address"]) ?></textarea>

    </div>

</div>

<div class="full">

    <button type="submit">
            
        <i class="bi bi-save"></i>
         Update Patient

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