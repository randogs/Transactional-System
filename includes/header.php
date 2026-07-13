<div class="header">

    <h1>

        <?= $pageTitle ?? "Dashboard" ?>

    </h1>

    <div class="user">

        Welcome,

        <?= htmlspecialchars($_SESSION["first_name"]) ?>

    </div>

</div>