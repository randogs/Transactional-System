<div class="sidebar">

    <h2>

        ❤️ CareSync

    </h2>

    <a href="/Transactional-System/home.php"
    class="<?= ($pageTitle ?? '') === 'Dashboard' ? 'active' : '' ?>">

        <i class="bi bi-house-door-fill"></i>

        Dashboard

    </a>

    <a href="/Transactional-System/patients/view_patients.php"
          class="<?= ($pageTitle ?? '') === 'Patient Management' ? 'active' : '' ?>">

        <i class="bi bi-people-fill"></i>

        Patients

    </a>

    <a href="/Transactional-System/doctors/view_doctors.php"
           class="<?= ($pageTitle ?? '') === 'Doctor Management' ? 'active' : '' ?>">

        <i class="bi bi-person-badge-fill"></i>

        Doctors

    </a>

    <a href="#">

        <i class="bi bi-calendar-check-fill"></i>

        Appointments

    </a>

    <a href="/Transactional-System/reset_password.php">

        <i class="bi bi-key-fill"></i>

        Reset Password

    </a>

    <a href="/Transactional-System/logout.php">

        <i class="bi bi-box-arrow-right"></i>

        Logout

    </a>

</div>