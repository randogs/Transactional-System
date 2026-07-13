<?php

session_start();

include "../config/database.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit();

}

$search = "";

if (isset($_GET["search"])) {

    $search = trim($_GET["search"]);

    $stmt = $conn->prepare("
        SELECT *
        FROM patients
        WHERE
            first_name LIKE ?
            OR last_name LIKE ?
            OR contact_number LIKE ?
            OR email LIKE ?
        ORDER BY patient_id DESC
    ");

    $keyword = "%".$search."%";

    $stmt->bind_param(
        "ssss",
        $keyword,
        $keyword,
        $keyword,
        $keyword
    );

} else {

    $stmt = $conn->prepare("
        SELECT *
        FROM patients
        ORDER BY patient_id DESC
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

<title>CareSync | Patient Management</title>

<link rel="stylesheet" href="../css/dashboard.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

<div class="wrapper">

    <?php include "../includes/sidebar.php"; ?>

    <div class="main">

        <?php

        $pageTitle = "Patient Management";
        include "../includes/header.php";

        ?>

        <div class="top-actions">

            <a href="add_patient.php" class="action-btn">

                <i class="bi bi-person-plus-fill"></i>

                Add Patient

            </a>

            <form method="GET" class="search-form">

                <input
                    type="text"
                    name="search"
                    placeholder="Search patient..."
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
                    <th>Full Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Actions</th>

                </tr>

            </thead>

            <tbody>

            <?php if($result->num_rows > 0): ?>

                <?php while($row = $result->fetch_assoc()): ?>

                <tr>

                    <td><?= $row["patient_id"] ?></td>

                    <td>

                        <?= htmlspecialchars(
                            $row["first_name"] . " " .
                            $row["middle_name"] . " " .
                            $row["last_name"]
                        ) ?>

                    </td>

                    <td><?= $row["age"] ?></td>

                    <td><?= $row["gender"] ?></td>

                    <td><?= $row["contact_number"] ?></td>

                    <td><?= $row["email"] ?></td>

                    <td>

                        <a
                            href="edit_patient.php?id=<?= $row["patient_id"] ?>"
                            class="edit-btn">

                            Edit

                        </a>

                        <a
                            href="delete_patient.php?id=<?= $row["patient_id"] ?>"
                            class="delete-btn"
                            onclick="return confirm('Delete this patient?')">

                            Delete

                        </a>

                    </td>

                </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td colspan="7">

                        No patients found.

                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>

</html>