<?php
include 'db/connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id_admin FROM admin WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['login_user'] = $email;
        header('Location: dashboard.php');
    } else {
        $error = "Invalid email or password";
    }
}
?>

<?php include 'templates/header.php';?>

<div class="content">
    <h2>Login</h2>
    <form method="post" action="">
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </form>
</div>

<?php include 'templates/footer.php';?>
