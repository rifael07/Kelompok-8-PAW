<?php
require "koneksi.php";
 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $aksi = $_POST["aksi"];
 
        
        if ($aksi == "tambah") {
            $stmt = mysqli_prepare($conn,
                "INSERT INTO voucher_diskon (kode, jenis, nilai_diskon, minimum_spend, berlaku, status)
                 VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssiiss",
                $_POST["kode"], $_POST["jenis"], $_POST["nilai_diskon"],
                $_POST["minimum_spend"], $_POST["berlaku"], $_POST["status"]);
            mysqli_stmt_execute($stmt);
            header("Location: index.php?pesan=tambah");
            exit;
        }
 
        if ($aksi == "ubah") {
            $stmt = mysqli_prepare($conn,
                "UPDATE voucher_diskon
                 SET kode = ?, jenis = ?, nilai_diskon = ?, minimum_spend = ?, berlaku = ?, status = ?
                 WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "ssiissi",
                $_POST["kode"], $_POST["jenis"], $_POST["nilai_diskon"],
                $_POST["minimum_spend"], $_POST["berlaku"], $_POST["status"], $_POST["id"]);
            mysqli_stmt_execute($stmt);
            header("Location: index.php?pesan=ubah");
            exit;
        }
 
        
        if ($aksi == "hapus") {
            $stmt = mysqli_prepare($conn, "DELETE FROM voucher_diskon WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $_POST["id"]);
            mysqli_stmt_execute($stmt);
            header("Location: index.php?pesan=hapus");
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        $pesan = ($e->getCode() == 1062) ? "duplikat" : "gagal";
        header("Location: index.php?pesan=" . $pesan);
        exit;
    }
}
 

$hasil = mysqli_query($conn, "SELECT * FROM voucher_diskon ORDER BY id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher & Diskon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="Style.css">
</head>
<body>
    <div class="body">
        <nav class="navbar navbar-expand bg-light border-bottom">
            <div class="container">
                <span class="navbar-brand">Toko Online</span>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Voucher</a></li>
                    <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah</a>
                    </li>
                </ul>
            </div>
        </nav>
 
        <div class="container">
            <div class="center">
                <h2>Voucher dan promo yang tersedia</h2>
            </div>
 
            <?php if (isset($_GET["pesan"])) { ?>
                <?php if ($_GET["pesan"] == "tambah") { ?>
                    <div class="alert alert-success">Voucher berhasil ditambahkan.</div>
                <?php } elseif ($_GET["pesan"] == "ubah") { ?>
                    <div class="alert alert-success">Voucher berhasil diubah.</div>
                <?php } elseif ($_GET["pesan"] == "hapus") { ?>
                    <div class="alert alert-success">Voucher berhasil dihapus.</div>
                <?php } elseif ($_GET["pesan"] == "duplikat") { ?>
                    <div class="alert alert-danger">Gagal: kode voucher sudah dipakai.</div>
                <?php } elseif ($_GET["pesan"] == "gagal") { ?>
                    <div class="alert alert-danger">Terjadi kesalahan saat menyimpan data.</div>
                <?php } ?>
            <?php } ?>
 
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th scope="col">Kode</th>
                            <th scope="col">Jenis</th>
                            <th scope="col">Nilai Diskon</th>
                            <th scope="col">Minimum Spend</th>
                            <th scope="col">Berlaku sampai</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($v = mysqli_fetch_assoc($hasil)) { ?>
                        <tr>
                            <th scope="row"><?php echo htmlspecialchars($v["kode"]); ?></th>
                            <td><?php echo ucfirst($v["jenis"]); ?></td>
                            <td>
                                <?php
                                if ($v["jenis"] == "persen") {
                                    echo $v["nilai_diskon"] . "%";
                                } else {
                                    echo number_format($v["nilai_diskon"], 0, ",", ".");
                                }
                                ?>
                            </td>
                            <td><?php echo number_format($v["minimum_spend"], 0, ",", "."); ?></td>
                            <td><?php echo date("d-m-Y", strtotime($v["berlaku"])); ?></td>
                            <td><?php echo ucfirst($v["status"]); ?></td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-warning btn-edit"
                                        data-bs-toggle="modal" data-bs-target="#modalEdit"
                                        data-id="<?php echo $v["id"]; ?>"
                                        data-kode="<?php echo htmlspecialchars($v["kode"]); ?>"
                                        data-jenis="<?php echo $v["jenis"]; ?>"
                                        data-nilai="<?php echo $v["nilai_diskon"]; ?>"
                                        data-minimum="<?php echo $v["minimum_spend"]; ?>"
                                        data-berlaku="<?php echo $v["berlaku"]; ?>"
                                        data-status="<?php echo $v["status"]; ?>">
                                    Edit
                                </button>
 
                                <form method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus voucher <?php echo htmlspecialchars($v["kode"]); ?>?')">
                                    <input type="hidden" name="aksi" value="hapus">
                                    <input type="hidden" name="id" value="<?php echo $v["id"]; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <br>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                Tambah Voucher
            </button>
        </div>
    </div>
 
    
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="aksi" value="tambah">
                    <div class="mb-3">
                        <label class="form-label">Kode</label>
                        <input type="text" name="kode" class="form-control" maxlength="30" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis</label>
                        <select name="jenis" class="form-select" required>
                            <option value="persen">Persen</option>
                            <option value="nominal">Nominal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nilai Diskon</label>
                        <input type="number" name="nilai_diskon" class="form-control" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Minimum Spend</label>
                        <input type="number" name="minimum_spend" class="form-control" min="0" value="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Berlaku sampai</label>
                        <input type="date" name="berlaku" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
 
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="aksi" value="ubah">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="mb-3">
                        <label class="form-label">Kode</label>
                        <input type="text" name="kode" id="edit-kode" class="form-control" maxlength="30" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis</label>
                        <select name="jenis" id="edit-jenis" class="form-select" required>
                            <option value="persen">Persen</option>
                            <option value="nominal">Nominal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nilai Diskon</label>
                        <input type="number" name="nilai_diskon" id="edit-nilai" class="form-control" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Minimum Spend</label>
                        <input type="number" name="minimum_spend" id="edit-minimum" class="form-control" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Berlaku sampai</label>
                        <input type="date" name="berlaku" id="edit-berlaku" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="edit-status" class="form-select" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Saat tombol Edit diklik, isi form modal dengan data baris tersebut
        document.querySelectorAll(".btn-edit").forEach(function (btn) {
            btn.addEventListener("click", function () {
                document.getElementById("edit-id").value      = this.dataset.id;
                document.getElementById("edit-kode").value    = this.dataset.kode;
                document.getElementById("edit-jenis").value   = this.dataset.jenis;
                document.getElementById("edit-nilai").value   = this.dataset.nilai;
                document.getElementById("edit-minimum").value = this.dataset.minimum;
                document.getElementById("edit-berlaku").value = this.dataset.berlaku;
                document.getElementById("edit-status").value  = this.dataset.status;
            });
        });
    </script>
</body>
</html>