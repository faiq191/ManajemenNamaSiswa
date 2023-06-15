<?php
session_start();

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $conn = mysqli_connect("localhost", "root", "", "crud_db");
    if (!$conn) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $error = "Username sudah digunakan. Silakan pilih username lain.";
    } else {
        $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if (mysqli_query($conn, $query)) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Terjadi kesalahan. Silakan coba lagi.";
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <style>
        .form-control-sm {
            height: 2rem;
            padding: 0.25rem;
            font-size: 0.875rem;
        }

        body {
            background-color: #cde0f7;
            background-image: url('path_to_your_background_image.png');
            background-repeat: repeat;
            background-size: auto;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            margin-top: 10rem;
        }

        .form-container {
            background-color: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .register-title {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1 class="register-title">Daftar</h1>
            <?php if (isset($error)) { echo "<p class='text-danger'>$error</p>"; } ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" id="username" name="username" class="form-control form-control-sm" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" id="password" name="password" class="form-control form-control-sm" required>
                </div>
                <div class="text-center">
                    <input type="submit" value="Daftar" class="btn btn-primary">
                </div>
            </form>
            <p class="text-center mt-3">Sudah punya akun? <a href="login.php">Login disini</a></p>
        </div>
    </div>
</body>
</html>
