<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareSync | Registration</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>
<body>

<div class="container">

    <div class="logo">
        <i class="bi bi-heart-pulse-fill"></i>
    </div>

    <h2>CareSync</h2>

    <p class="subtitle">
        Integrated Clinic Appointment and Patient Management System
    </p>

    <form action="" method="POST">

        <label>
            <i class="bi bi-person"></i>
            First Name
        </label>
        <input type="text" name="first_name" placeholder="Enter your first name" required>

        <label>
            <i class="bi bi-person"></i>
            Middle Name
        </label>
        <input type="text" name="middle_name" placeholder="Enter your middle name">

        <label>
            <i class="bi bi-person"></i>
            Last Name
        </label>
        <input type="text" name="last_name" placeholder="Enter your last name" required>

        <label>
            <i class="bi bi-person-circle"></i>
            Username
        </label>
        <input type="text" name="username" placeholder="Choose a username" required>

        <label>
            <i class="bi bi-lock"></i>
            Password
        </label>
        <input type="password" name="password" placeholder="Enter password" required>

        <label>
            <i class="bi bi-lock-fill"></i>
            Confirm Password
        </label>
        <input type="password" name="confirm_password" placeholder="Confirm password" required>

        <label>
            <i class="bi bi-calendar-event"></i>
            Birthday
        </label>
        <input type="date" name="birthday" required>

        <label>
            <i class="bi bi-envelope"></i>
            Email Address
        </label>
        <input type="email" name="email" placeholder="example@email.com" required>

        <label>
            <i class="bi bi-telephone"></i>
            Contact Number
        </label>
        <input type="text" name="contact_number" placeholder="09XXXXXXXXX" required>

        <button type="submit">
            <i class="bi bi-person-plus-fill"></i>
            Create Account
        </button>

    </form>

    <div class="footer">
        Already have an account?
        <a href="login.php">Login Here</a>
    </div>

</div>

</body>
</html>