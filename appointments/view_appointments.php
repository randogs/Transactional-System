<?php

session_start();

include "../config/database.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit();

}

$search = "";

if(isset($_GET["search"])){

    $search = trim($_GET["search"]);

    $stmt = $conn->prepare("
        SELECT
            appointments.*,
            CONCAT(
                patients.first_name,' ',
                patients.middle_name,' ',
                patients.last_name
            ) AS patient_name,
            doctors.doctor_name
        FROM appointments
        INNER JOIN patients
            ON appointments.patient_id = patients.patient_id
        INNER JOIN doctors
            ON appointments.doctor_id = doctors.doctor_id
        WHERE
            patients.first_name LIKE ?
            OR doctors.doctor_name LIKE ?
            OR appointments.status LIKE ?
            OR appointments.reason LIKE ?
        ORDER BY appointment_date DESC,
                 appointment_time DESC
    ");

    $keyword = "%".$search."%";

    $stmt->bind_param(
        "ssss",
        $keyword,
        $keyword,
        $keyword,
        $keyword
    );

}else{

    $stmt = $conn->prepare("
        SELECT
            appointments.*,
            CONCAT(
                patients.first_name,' ',
                patients.middle_name,' ',
                patients.last_name
            ) AS patient_name,
            doctors.doctor_name
        FROM appointments
        INNER JOIN patients
            ON appointments.patient_id = patients.patient_id
        INNER JOIN doctors
            ON appointments.doctor_id = doctors.doctor_id
        ORDER BY appointment_date DESC,
                 appointment_time DESC
    ");

}

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>CareSync | Appointment Management</title>

<link rel="stylesheet" href="../css/dashboard.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

<div class="wrapper">

    <?php include "../includes/sidebar.php"; ?>

    <div class="main">

        <?php

        $pageTitle = "Appointment Management";
        include "../includes/header.php";

        ?>

        <div class="top-actions">

            <a href="add_appointment.php" class="action-btn">

                <i class="bi bi-person-plus-fill"></i>

                Add appointment

            </a>

            <form method="GET" class="search-form">

                <input
                    type="text"
                    name="search"
                    placeholder="Search appointment..."
                    value="<?= htmlspecialchars($search) ?>">

                <button type="submit">

                    <i class="bi bi-search"></i>

                </button>

            </form>

        </div>

        <table>

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>

            </thead>

            <tbody>

            <?php if($result->num_rows > 0): ?>

                <?php while($row = $result->fetch_assoc()): ?>

                   <tr>

    <td><?= $row["appointment_id"] ?></td>

    <td><?= htmlspecialchars($row["patient_name"]) ?></td>

    <td><?= htmlspecialchars($row["doctor_name"]) ?></td>

    <td><?= htmlspecialchars($row["appointment_date"]) ?></td>

    <td><?= htmlspecialchars($row["appointment_time"]) ?></td>

    <td><?= htmlspecialchars($row["status"]) ?></td>

    <td>

        <a
            href="edit_appointment.php?id=<?= $row["appointment_id"] ?>"
            class="edit-btn">

            Edit

        </a>

        <a
            href="delete_appointment.php?id=<?= $row["appointment_id"] ?>"
            class="delete-btn"
            onclick="return confirm('Delete this appointment?')">

            Delete

        </a>

    </td>

</tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td colspan="8">

                        No appointments found.

                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>

</html>