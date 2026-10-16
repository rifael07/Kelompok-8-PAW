<?php
require "koneksi.php";
$hasil = mysqli_query($koneksi, "SELECT * FROM data_pelanggan_dan_alamat");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pelanggan & Alamat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: darkslategray">
        <div class="container">
            <a class="navbar-brand" href="index.php">Toko Online UMKM</a>
            <div class="navbar-nav">
                <a class="nav-link active" href="index.php">Daftar Pelanggan</a>
                <a class="nav-link" href="tambah.php">Tambah Pelanggan</a>
            </div>
        </div>
    </nav>

<div class="container">
        <div class="kotak-konten">
            <h2 class="judul-halaman">Daftar Pelanggan & Alamat</h2>
            <a href="tambah.php" class="btn btn-success mb-3">Tambah pelanggan</a>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover tabel-pelanggan">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No Hp</th>
                            <th>Jenis Kelamin</th>
                            <th>Alamat Pengiriman</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($hasil) > 0) : ?>
              <?php $no = 1; ?>
              <?php while ($row = mysqli_fetch_assoc($hasil)) : ?>
                <tr>
                  <td><?= $no++; ?></td>
                  <td><?= htmlspecialchars($row['nama']); ?></td>
                  <td><?= htmlspecialchars($row['email']); ?></td>
                  <td><?= htmlspecialchars($row['no_hp']); ?></td>
                  <td><?= htmlspecialchars($row['jenis_kelamin']); ?></td>
                  <td><?= htmlspecialchars($row['alamat_pengiriman']); ?></td>
                  <td> 
                    <a href="edit.php?id=<?= $row["id"] ?>" class="btn btn-success btn-sm me-1">Edit</a>
                    <a href="hapus.php?id=<?= $row["id"] ?>" class= "btn btn-danger btn-sm" onclick= "return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                 </td>
                </tr>
              <?php endwhile; ?>
            <?php else : ?>
              <tr>
                <td colspan="6" class="text-center py-3 text-muted">Belum ada data pelanggan.</td>
              </tr>
            <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
     
    
</body>
</html>