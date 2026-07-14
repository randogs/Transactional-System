<?php

session_start();

include "../config/database.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit();

}

$message = "";

/* Load Patients */

$patients = $conn->query("
    SELECT patient_id,
           first_name,
           middle_name,
           last_name
    FROM patients
    ORDER BY first_name
");

/* Load Doctors */

$doctors = $conn->query("
    SELECT doctor_id,
           doctor_name
    FROM doctors
    ORDER BY doctor_name
");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $patient_id = $_POST["patient_id"];
    $doctor_id = $_POST["doctor_id"];
    $appointment_date = $_POST["appointment_date"];
    $appointment_time = $_POST["appointment_time"];
    $reason = trim($_POST["reason"]);
    $status = $_POST["status"];

    /* Prevent duplicate appointments */

    $check = $conn->prepare("
        SELECT appointment_id
        FROM appointments
        WHERE
            patient_id = ?
            AND doctor_id = ?
            AND appointment_date = ?
            AND appointment_time = ?
    ");

    $check->bind_param(
        "iiss",
        $patient_id,
        $doctor_id,
        $appointment_date,
        $appointment_time
    );

    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {

        $message = "This appointment already exists.";

    } else {

        $stmt = $conn->prepare("
            INSERT INTO appointments
            (
                patient_id,
                doctor_id,
                appointment_date,
                appointment_time,
                reason,
                status
            )
            VALUES
            (?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "iissss",
            $patient_id,
            $doctor_id,
            $appointment_date,
            $appointment_time,
            $reason,
            $status
        );

        if ($stmt->execute()) {

            $message = "Appointment added successfully.";

        } else {

            $message = "Failed to add appointment.";

        }

        $stmt->close();

    }

    $check->close();

}

$conn->close();

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

<div class="container">

    <div class="left">

        <i class="bi bi-calendar-check-fill"></i>

        <h1>CareSync</h1>

        <h3>Schedule Appointment</h3>

        <p>
            Schedule a new appointment
            in the CareSync system.
        </p>

    </div>

    <div class="right">

        <h2>Add Appointment</h2>

        <p class="subtitle">
            Enter the appointment details.
        </p>

        <?php if(!empty($message)): ?>

        <div class="<?= strpos(strtolower($message),"successfully") !== false ? "success" : "error"; ?>">

            <?= htmlspecialchars($message) ?>

        </div>

        <?php endif; ?>

        <form action="" method="POST">

            <!-- Patient -->

            <div>

                <label>Patient</label>

                <div class="input-box">

                    <select name="patient_id" required>

                        <option value="">Select Patient</option>

                        <?php while($patient = $patients->fetch_assoc()): ?>

                            <option value="<?= $patient["patient_id"] ?>">

                                <?= htmlspecialchars(
                                    $patient["first_name"] . " " .
                                    $patient["middle_name"] . " " .
                                    $patient["last_name"]
                                ) ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

            </div>

            <!-- Doctor -->

            <div>

                <label>Doctor</label>

                <div class="input-box">

                    <select name="doctor_id" required>

                        <option value="">Select Doctor</option>

                        <?php while($doctor = $doctors->fetch_assoc()): ?>

                            <option value="<?= $doctor["doctor_id"] ?>">

                                <?= htmlspecialchars($doctor["doctor_name"]) ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

            </div>

            <!-- Appointment Date -->

            <div>

                <label>Appointment Date</label>

                <div class="input-box">

                    <i class="bi bi-calendar-date"></i>

                    <input
                        type="date"
                        name="appointment_date"
                        min="<?= date('Y-m-d') ?>"
                        required>

                </div>

            </div>

            <!-- Appointment Time -->

            <div>

                <label>Appointment Time</label>

                <div class="input-box">

                    <i class="bi bi-clock"></i>

                    <input
                        type="time"
                        name="appointment_time"
                        min="08:00"
                        max="17:00"
                        required>

                </div>

            </div>

            <!-- Reason -->

            <div class="full">

                <label>Reason</label>

                <div class="input-box">

                    <textarea
                        name="reason"
                        rows="4"
                        placeholder="Reason for appointment"></textarea>

                </div>

            </div>

            <!-- Status -->

            <div class="full">

                <label>Status</label>

                <div class="input-box">

                    <select name="status">

                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>

                    </select>

                </div>

            </div>

            <button type="submit">

                <i class="bi bi-save"></i>

                Save Appointment

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