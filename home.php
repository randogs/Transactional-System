<?php

session_start();

include "config/database.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}

// Dashboard Statistics
$patients = $conn->query("SELECT COUNT(*) AS total FROM patients");
$totalPatients = $patients->fetch_assoc()["total"];

$doctors = $conn->query("SELECT COUNT(*) AS total FROM doctors");
$totalDoctors = $doctors->fetch_assoc()["total"];

$appointments = $conn->query("SELECT COUNT(*) AS total FROM appointments");
$totalAppointments = $appointments->fetch_assoc()["total"];

$pending = $conn->query("SELECT COUNT(*) AS total FROM appointments WHERE status='Pending'");
$totalPending = $pending->fetch_assoc()["total"];

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>CareSync Dashboard</title>

<link rel="stylesheet" href="css/dashboard.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

<div class="wrapper">

    <?php include "includes/sidebar.php"; ?>

    <div class="main">

        <?php

$pageTitle = "Dashboard";
include "includes/header.php";

           ?>

        <!-- Statistics -->

        <div class="cards">

            <div class="card">

                <i class="bi bi-people-fill"></i>

                <h3>Patients</h3>

                <p><?= $totalPatients ?></p>

            </div>

            <div class="card">

                <i class="bi bi-person-badge-fill"></i>

                <h3>Doctors</h3>

                <p><?= $totalDoctors ?></p>

            </div>

            <div class="card">

                <i class="bi bi-calendar-check-fill"></i>

                <h3>Appointments</h3>

                <p><?= $totalAppointments ?></p>

            </div>

            <div class="card">

                <i class="bi bi-hourglass-split"></i>

                <h3>Pending</h3>

                <p><?= $totalPending ?></p>

            </div>

        </div>

        <!-- Quick Actions -->

        <h2 style="margin-bottom:20px; color:#0F766E;">

            Quick Actions

        </h2>

        <div class="actions">

            <a
                href="patients/add_patient.php"
                class="action">

                <i class="bi bi-person-plus-fill"></i>

                <br><br>

                Add Patient

            </a>

            <a
                href="patients/view_patients.php"
                class="action">

                <i class="bi bi-people-fill"></i>

                <br><br>

                Manage Patients

            </a>

            <a
                href="doctors/view_doctors.php"
                class="action">

                <i class="bi bi-person-badge-fill"></i>

                <br><br>

                Manage Doctors

            </a>

            <a
                href="#"
                class="action">

                <i class="bi bi-calendar-check-fill"></i>

                <br><br>

                Manage Appointments

            </a>

        </div>

    </div>

</div>

</body>

</html>