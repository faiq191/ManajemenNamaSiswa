<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "crud_db");
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if (isset($_SESSION['username'])) {
    $role = $_SESSION['role'];

    if ($role === 'admin') {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $sql = "SELECT * FROM nilai_siswa WHERE id='$id'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $nama = $row['nama'];
                $kelas = $row['kelas'];
                $mapel = $row['mapel'];
                $nilai = $row['nilai'];
            } else {
                echo "Data tidak ditemukan.";
                exit();
            }
        } else {
            echo "ID tidak ditemukan.";
            exit();
        }
    } else {
        echo "Anda tidak memiliki akses.";
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}

$message = '';

if (isset($_POST['update'])) {
    $nama  = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $mapel = $_POST['mapel'];
    $nilai = $_POST['nilai'];

    $sql = "UPDATE nilai_siswa SET nama='$nama', mapel='$mapel', kelas='$kelas', nilai='$nilai' WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Data telah diperbarui.";

        $nama = $_POST['nama'];

        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .center-text {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="text-center">
                    <h1 class="mb-4">Edit Data</h1>
                </div>
                <div class="text-center mt-5">
                    <div class="d-flex justify-content-center">
                        <form method="post" action="">
                            <div class="form-group">
                                <input type="text" class="form-control" name="nama" placeholder="Nama" value="<?php echo $nama; ?>">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="kelas" placeholder="Kelas" value="<?php echo $kelas; ?>">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="mapel" placeholder="Mapel" value="<?php echo $mapel; ?>">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="nilai" placeholder="Nilai" value="<?php echo $nilai; ?>">
                            </div>
                            <button type="submit" class="btn btn-success" name="update">Perbarui Data</button>
                        </form>
                    </div>
                    <?php if (isset($_SESSION['error'])): ?>
                        <?php $error = $_SESSION['error']; ?>
                        <?php unset($_SESSION['error']); ?>
                        <p class="mt-3"><?php echo $error; ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
