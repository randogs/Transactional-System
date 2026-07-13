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

$stmt = $conn->prepare("DELETE FROM doctors WHERE doctor_id = ?");

$stmt->bind_param("i", $id);

$stmt->execute();

$stmt->close();

$conn->close();

header("Location: view_doctors.php");

exit();

?>