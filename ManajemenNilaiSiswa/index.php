<!DOCTYPE html>
<html>
<head>
    <title>Halaman Utama</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .center-text {
            text-align: center;
        }

        .form-control-wide {
            width: 400px;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #E0B0FF;
            margin: 0;
            padding: 0;
            height: 200px;
        }

        .subtext {
            font-size: 50px;
            font-weight: bold;
            font-family: 'Yuji Hentaigana Akari', cursive;
        }

        .subtext2 {
            font-size: 35px;
            font-weight: bold;
            font-family: 'Yuji Hentaigana Akari', cursive;
        }

        .subtext3 {
            font-size: 30px;
            font-family: 'Gloria Hallelujah', cursive;
        }
    </style>
</head>
<body>
<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "crud_db");
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

$message = '';
$redirect = "index.php";

if ($role === 'admin' && isset($_POST['add'])) {
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $nilai = $_POST['nilai'];
    $mapel = $_POST['mapel'];

    $sql = "INSERT INTO nilai_siswa (nama, kelas, nilai, mapel) VALUES ('$nama', '$kelas', '$nilai', '$mapel')";

    if (mysqli_query($conn, $sql)) {
        $message = "Data telah ditambahkan.";
        header("Location: $redirect");
        exit();
    } else {
        $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

if ($role === 'admin' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $nilai = $_POST['nilai'];
    $mapel = $_POST['mapel'];

    $sql = "UPDATE nilai_siswa SET nama='$nama', kelas='$kelas', nilai='$nilai', mapel='$mapel' WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        $message = "Data telah diperbarui.";
        header("Location: $redirect");
        exit();
    } else {
        $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

if ($role === 'admin' && isset($_POST['delete'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM nilai_siswa WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        $message = "Data telah dihapus.";
        header("Location: $redirect");
        exit();
    } else {
        $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$data = array();
$sql = "SELECT * FROM nilai_siswa";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = array($row['id'], $row['nama'], $row['kelas'], $row['mapel'], $row['nilai']);
    }
}

mysqli_close($conn);
?>

<header>
    <div class="text-center">
        <h1 class="mb-4, subtext2">Selamat datang, <?php echo $_SESSION['username']; ?>!</h1>
        <p class="center-text, subtext">Manajemen Nilai Siswa </p>
    </div>
</header>

<div class="container mt-2">
    <div class="row">
        <div class="col-12">

            <?php if ($role === 'admin'): ?>
                <div class="text-center mt-2">
                    <p class="subtext3">Halo <?php echo $_SESSION['username']; ?>, Anda memiliki akses admin.</p>
                    <div class="d-flex justify-content-center">
                        <form method="post" action="">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-wide" name="nama" placeholder="Nama">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-wide" name="kelas" placeholder="Kelas">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-wide" name="mapel" placeholder="Mapel">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-wide" name="nilai" placeholder="Nilai">
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary mr-2" name="add">Tambah Data</button>
                                <button type="submit" class="btn btn-secondary" name="logout">Logout</button>
                            </div>
                        </form>
                    </div>
                    <p class="mt-3"><?php echo $message; ?></p>
                </div>
            <?php else: ?>
                <div class="text-center mt-2">
                    <p class="subtext3">Anda masuk sebagai guest.</p>
                    <form method="post" action="">
                        <button type="submit" class="btn btn-secondary" name="logout">Logout</button>
                    </form>
                </div>
            <?php endif; ?>
            <div class="mt-5">
                <h2 class="text-center">Nilai Siswa</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Mapel</th>
                            <th>Nilai</th>
                            <?php if ($role === 'admin'): ?>
                                <th>Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($data as $row): ?>
                            <tr>
                                <td><?php echo $counter; ?></td>
                                <td><?php echo $row[1]; ?></td>
                                <td><?php echo $row[2]; ?></td>
                                <td><?php echo $row[3]; ?></td>
                                <td><?php echo $row[4]; ?></td>
                                <?php if ($role === 'admin'): ?>
                                    <td>
                                        <form method="post" action="">
                                            <input type="hidden" name="id" value="<?php echo $row[0]; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" name="delete" >Hapus</button>
                                            <a href="edit.php?id=<?php echo $row[0]; ?>" class="btn btn-sm btn-success ml-2" role="button">Edit</a>
                                        </form>
                                    </td>
                                <?php endif; ?>
                            </tr>
                            <?php $counter++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
