<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'];

$last_login = "First Login";

if (isset($_COOKIE['last_login'])) {
    $last_login = $_COOKIE['last_login'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="ssstyle.css">
</head>
<body>

<div class="container">

    <h1>Dashboard</h1>

    <h3>Welcome, <?php echo $user_name; ?>!</h3>

    <p>Last Login Time: <?php echo $last_login; ?></p>

    <a href="logout.php">
        <button>Logout</button>
    </a>

</div>

</body>
</html>