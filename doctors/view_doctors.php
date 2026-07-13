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
    FROM doctors
    WHERE
        doctor_name LIKE ?
        OR specialization LIKE ?
        OR contact_number LIKE ?
        OR email LIKE ?
    ORDER BY doctor_id DESC
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
        FROM doctors
        ORDER BY doctor_id DESC
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

<title>CareSync | Doctor Management</title>

<link rel="stylesheet" href="../css/dashboard.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

<div class="wrapper">

    <?php include "../includes/sidebar.php"; ?>

    <div class="main">

        <?php

        $pageTitle = "Doctor Management";
        include "../includes/header.php";

        ?>

        <div class="top-actions">

            <a href="add_doctor.php" class="action-btn">

                <i class="bi bi-person-plus-fill"></i>

                Add doctor

            </a>

            <form method="GET" class="search-form">

                <input
                    type="text"
                    name="search"
                    placeholder="Search doctor..."
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
                    <th>Doctor Name</th>
                    <th>Specialization</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Actions</th>

                </tr>

            </thead>

            <tbody>

            <?php if($result->num_rows > 0): ?>

                <?php while($row = $result->fetch_assoc()): ?>

                <tr>

                   <td><?= $row["doctor_id"] ?></td>

                   <td><?= htmlspecialchars($row["doctor_name"]) ?></td>

                   <td><?= htmlspecialchars($row["specialization"]) ?></td>

                    <td><?= htmlspecialchars($row["contact_number"]) ?></td>

                    <td><?= htmlspecialchars($row["email"]) ?></td>

                    <td>

    <a
        href="edit_doctor.php?id=<?= $row["doctor_id"] ?>"
        class="edit-btn">

        Edit

    </a>

    <a
        href="delete_doctor.php?id=<?= $row["doctor_id"] ?>"
        class="delete-btn"
        onclick="return confirm('Delete this doctor?')">

        Delete

    </a>

</td>

                </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td colspan="7">

                        No doctors found.

                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>

</html>