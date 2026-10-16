<?php
require "koneksi.php";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $id                = $_POST["id"]; 
    $nama              = $_POST["nama"];
    $email             = $_POST["email"];
    $no_hp             = $_POST["no_hp"];
    $jenis_kelamin     = $_POST["jenis_kelamin"];
    $alamat_pengiriman = $_POST["alamat_pengiriman"];
    $sql =  "UPDATE data_pelanggan_dan_alamat SET nama = ?, email = ?, no_hp = ?, jenis_kelamin = ?, alamat_pengiriman = ? WHERE id = ?";
    mysqli_execute_query($koneksi, $sql, [$nama, $email, $no_hp, $jenis_kelamin, $alamat_pengiriman, $id]);
    header ("Location: index.php");
    exit;
}

$id= $_GET ["id"];
$hasil = mysqli_execute_query ($koneksi, "SELECT * FROM data_pelanggan_dan_alamat WHERE id = ?", [$id]);
$row = mysqli_fetch_assoc($hasil);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: darkslategray">
        <div class="container">
            <a class="navbar-brand" href="index.php">Toko Online UMKM</a>
            <div class="navbar-nav">
                <a class="nav-link" href="index.php">Daftar Pelanggan</a>
                <a class="nav-link" href="tambah.php">Tambah Pelanggan</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="kotak-konten p-4 border rounded bg-white">
            <h2 class="judul-halaman mb-3">Edit Pelanggan</h2>

            <form method="POST" action="">
                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($row['nama']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($row['email']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="no_hp" class="form-label">No Hp</label>
                        <input type="tel" class="form-control" id="no_hp" name="no_hp" value="<?= htmlspecialchars($row['no_hp']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <span class="form-label d-block">Jenis Kelamin</span>
                        <div class="form-check form-check-inline mt-2">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="lk" value="Laki-laki" <?= ($row['jenis_kelamin'] == 'Laki-laki') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="lk">Laki-laki</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="pr" value="Perempuan" <?= ($row['jenis_kelamin'] == 'Perempuan') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="pr">Perempuan</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="alamat_pengiriman" class="form-label">Alamat Pengiriman</label>
                        <textarea class="form-control" id="alamat_pengiriman" name="alamat_pengiriman" rows="3" required><?= htmlspecialchars($row['alamat_pengiriman']); ?></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                <a href="index.php" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>