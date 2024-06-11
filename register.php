<?php
include 'db/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "INSERT INTO admin (nama, email, password) VALUES ('$nama', '$email', '$password')";

    if ($conn->query($sql) === true) {
        header('Location: login.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<?php include 'templates/header.php';?>

<div class="content">
    <h2>Register</h2>
    <form method="post" action="">
        <div>
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" required>
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Register</button>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </form>
</div>

<?php include 'templates/footer.php';?>
