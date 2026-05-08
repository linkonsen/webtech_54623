<?php
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    
    $check = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {
        $message = "Email already registered!";
    } else {

        $sql = "INSERT INTO users(name, email, password)
                VALUES('$name', '$email', '$hashed_password')";

        if (mysqli_query($conn, $sql)) {
            $message = "Registration Successful! <a href='login.php'>Login Here</a>";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="ssstyle.css">
</head>
<body>

<div class="container">
    <h2>User Registration</h2>

    <form method="POST">
        <input type="text" name="name" placeholder="Enter Name" required>

        <input type="email" name="email" placeholder="Enter Email" required>

        <input type="password" name="password" placeholder="Enter Password" required>

        <button type="submit">Register</button>
    </form>

    <p><?php echo $message; ?></p>

    <p>Already have an account?
        <a href="login.php">Login</a>
    </p>
</div>

</body>
</html>