<?php
    if (mysqli_num_rows($result) == 1)
    {

        $user = mysqli_fetch_assoc($result);

        
        if (password_verify($password, $user['password'])) {

           
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            
            setcookie('user_email', $email, time() + (7 * 24 * 60 * 60));

            
            setcookie('last_login', date('Y-m-d H:i:s'), time() + (7 * 24 * 60 * 60));

            header("Location: dashboard.php");
            exit();

        } 
        else {
            $message = "Invalid Password!";
        }

    } 
    else {
        $message = "User not found!";
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="ssstyle.css">
</head>
<body>

<div class="container">
    <h2>User Login</h2>

    <form method="POST">

        <input type="email"
               name="email"
               placeholder="Enter Email"
               value="<?php echo $saved_email; ?>"
               required>

        <input type="password"
               name="password"
               placeholder="Enter Password"
               required>

        <button type="submit">Login</button>
    </form>

    <p><?php echo $message; ?></p>

    <p>Don't have an account?
        <a href="register.php">Register</a>
    </p>
</div>

</body>
</html>