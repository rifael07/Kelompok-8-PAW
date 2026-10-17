<?php
require "koneksi.php";
 
$pesan = "";
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode          = mysqli_real_escape_string($conn, $_POST["kode"] ?? "");
    $jenis         = $_POST["jenis"] ?? "";
    $nilai_diskon  = $_POST["nilai_diskon"] ?? "";
    $minimum_spend = $_POST["minimum_spend"] ?? "";
    $berlaku       = $_POST["berlaku"] ?? "";
    $status        = $_POST["status"] ?? "";
 
    if ($kode == "" || $nilai_diskon <= 0 || $berlaku == "") {
        $pesan = "Kode, nilai diskon, dan tanggal harus diisi.";
    } elseif ($jenis == "persen" && $nilai_diskon > 100) {
        $pesan = "Diskon persen tidak boleh lebih dari 100.";
    } else {
        $sql = "INSERT INTO voucher_diskon (kode, jenis, nilai_diskon, minimum_spend, berlaku, status)
                VALUES ('$kode', '$jenis', $nilai_diskon, $minimum_spend, '$berlaku', '$status')";
 
        if (mysqli_query($conn, $sql)) {
            header("Location: index.php?pesan=tambah");
            exit;
        } else {
            $pesan = "Gagal menyimpan data: " . mysqli_error($conn);
        }
    }
}
?>
 
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Voucher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Style.css">
</head>
<body>
 
<div class="body">
<nav class="navbar navbar-expand bg-light border-bottom">
    <div class="container">
        <span class="navbar-brand">Toko Online</span>
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="index.php">Voucher</a></li>
            <li class="nav-item"><a class="nav-link active" href="tambah.php">Tambah</a></li>
        </ul>
    </div>
</nav>
 
<div class="container">
    <div class="kotak">
        <h4>Tambah Voucher</h4>
 
        <?php if ($pesan != "") { ?>
            <div class="alert alert-danger"><?php echo $pesan; ?></div>
        <?php } ?>
 
        <form method="post">
            <div class="mb-3">
                <label for="kode" class="form-label">Kode voucher</label>
                <input type="text" id="kode" name="kode" class="form-control">
            </div>
 
            <div class="mb-3">
                <label for="jenis" class="form-label">Jenis diskon</label>
                <select id="jenis" name="jenis" class="form-select" onchange="ubahSatuan()">
                    <option value="persen">Persen</option>
                    <option value="nominal">Nominal</option>
                </select>
            </div>
 
            <div class="mb-3">
                <label for="nilai_diskon" class="form-label">Nilai diskon</label>
                <div class="input-group">
                    <span class="input-group-text" id="awalan" style="display:none">Rp</span>
                    <input type="number" id="nilai_diskon" name="nilai_diskon" class="form-control" min="1" max="100">
                    <span class="input-group-text" id="akhiran">%</span>
                </div>
            </div>
 
            <div class="mb-3">
                <label for="minimum_spend" class="form-label">Minimum spend</label>
                <input type="number" id="minimum_spend" name="minimum_spend" class="form-control" value="0">
            </div>
 
            <div class="mb-3">
                <label for="berlaku" class="form-label">Berlaku sampai</label>
                <input type="date" id="berlaku" name="berlaku" class="form-control">
            </div>
 
            <div class="mb-3">
                <label class="form-label">Status</label><br>
                <input type="radio" name="status" value="aktif" checked> Aktif
                <input type="radio" name="status" value="nonaktif" class="ms-3"> Nonaktif
            </div>
 
            <button type="submit" class="btn btn-dark">Simpan</button>
            <a href="index.php" class="btn btn-outline-secondary">Kembali</a>
        </form>
    </div>
</div>
</div>
 
<script>
function ubahSatuan() {
    var jenis  = document.getElementById("jenis").value;
    var nilai  = document.getElementById("nilai_diskon");
    var awalan = document.getElementById("awalan");
    var akhir  = document.getElementById("akhiran");
 
    if (jenis == "persen") {
        awalan.style.display = "none";
        akhir.style.display  = "";
        nilai.max = 100;
    } else {
        awalan.style.display = "";
        akhir.style.display  = "none";
        nilai.removeAttribute("max");
    }
}
</script>
 
</body>
</html>