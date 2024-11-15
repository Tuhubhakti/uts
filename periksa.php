<?php
session_start(); // Memulai session untuk memastikan user sudah login

// Mengecek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: loginuser.php"); // Redirect ke halaman login jika belum login
    exit();
}

// Menghubungkan dengan file koneksi database
include 'koneksi.php';

// Menangani aksi CRUD

// 1. CREATE (Menambahkan Data Pemeriksaan)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $pasien_id = $_POST['pasien_id'];
    $dokter_id = $_POST['dokter_id'];
    $tanggal_periksa = $_POST['tanggal_periksa'];
    $keluhan = $_POST['keluhan'];

    // Query untuk menambah data pemeriksaan
    $sql = "INSERT INTO periksa (pasien_id, dokter_id, tanggal_periksa, keluhan) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $pasien_id, $dokter_id, $tanggal_periksa, $keluhan);
    if ($stmt->execute()) {
        echo "Pemeriksaan berhasil ditambahkan.";
    } else {
        echo "Terjadi kesalahan saat menambahkan pemeriksaan.";
    }
    $stmt->close();
}

// 2. UPDATE (Mengedit Data Pemeriksaan)
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM periksa WHERE id = $id");
    $data = $result->fetch_assoc();
}

// 3. Memperbarui data pemeriksaan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $pasien_id = $_POST['pasien_id'];
    $dokter_id = $_POST['dokter_id'];
    $tanggal_periksa = $_POST['tanggal_periksa'];
    $keluhan = $_POST['keluhan'];

    // Query untuk mengupdate data pemeriksaan
    $sql = "UPDATE periksa SET pasien_id = ?, dokter_id = ?, tanggal_periksa = ?, keluhan = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissi", $pasien_id, $dokter_id, $tanggal_periksa, $keluhan, $id);
    if ($stmt->execute()) {
        echo "Pemeriksaan berhasil diperbarui.";
    } else {
        echo "Terjadi kesalahan saat memperbarui pemeriksaan.";
    }
    $stmt->close();
}

// 4. DELETE (Menghapus Data Pemeriksaan)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Query untuk menghapus data pemeriksaan
    $sql = "DELETE FROM periksa WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Pemeriksaan berhasil dihapus.";
    } else {
        echo "Terjadi kesalahan saat menghapus pemeriksaan.";
    }
    $stmt->close();
}

// Menampilkan data pemeriksaan
$sql = "SELECT p.id, p.tanggal_periksa, p.keluhan, d.nama AS dokter, ps.nama AS pasien 
        FROM periksa p 
        JOIN dokter d ON p.dokter_id = d.id 
        JOIN pasien ps ON p.pasien_id = ps.id";
$result = $conn->query($sql);

// Menampilkan data dokter dan pasien untuk form tambah/update
$doctors = $conn->query("SELECT * FROM dokter");
$patients = $conn->query("SELECT * FROM pasien");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pemeriksaan - Poliklinik</title>
    <link rel="stylesheet" href="style.css"> <!-- Sesuaikan dengan file CSS Anda -->
</head>
<body>
    <h1>Manajemen Pemeriksaan</h1>
    <a href="logoutuser.php">Logout</a> <!-- Link untuk logout -->

    <!-- Form untuk menambahkan pemeriksaan -->
    <h2>Tambah Pemeriksaan</h2>
    <form method="POST" action="periksa.php">
        <label for="pasien_id">Pilih Pasien</label>
        <select name="pasien_id" required>
            <?php while ($row = $patients->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?></option>
            <?php endwhile; ?>
        </select>

        <label for="dokter_id">Pilih Dokter</label>
        <select name="dokter_id" required>
            <?php while ($row = $doctors->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?></option>
            <?php endwhile; ?>
        </select>

        <label for="tanggal_periksa">Tanggal Pemeriksaan</label>
        <input type="date" name="tanggal_periksa" required>

        <label for="keluhan">Keluhan Pasien</label>
        <textarea name="keluhan" required></textarea>

        <button type="submit" name="add">Tambah Pemeriksaan</button>
    </form>

    <!-- Menampilkan data pemeriksaan -->
    <h2>Daftar Pemeriksaan</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pasien</th>
                <th>Dokter</th>
                <th>Tanggal Pemeriksaan</th>
                <th>Keluhan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['pasien']; ?></td>
                <td><?php echo $row['dokter']; ?></td>
                <td><?php echo $row['tanggal_periksa']; ?></td>
                <td><?php echo $row['keluhan']; ?></td>
                <td>
                    <!-- Tautan untuk mengedit pemeriksaan -->
                    <a href="periksa.php?edit=<?php echo $row['id']; ?>">Edit</a> |
                    <!-- Tautan untuk menghapus pemeriksaan -->
                    <a href="periksa.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus pemeriksaan ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Form untuk mengedit pemeriksaan -->
    <?php if (isset($data)): ?>
    <h2>Edit Pemeriksaan</h2>
    <form method="POST" action="periksa.php">
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

        <label for="pasien_id">Pilih Pasien</label>
        <select name="pasien_id" required>
            <?php
            // Menampilkan pasien yang sudah dipilih di form edit
            $patients->data_seek(0);
            while ($row = $patients->fetch_assoc()):
                $selected = ($row['id'] == $data['pasien_id']) ? 'selected' : '';
            ?>
                <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>><?php echo $row['nama']; ?></option>
            <?php endwhile; ?>
        </select>

        <label for="dokter_id">Pilih Dokter</label>
        <select name="dokter_id" required>
            <?php
            // Menampilkan dokter yang sudah dipilih di form edit
            $doctors->data_seek(0);
            while ($row = $doctors->fetch_assoc()):
                $selected = ($row['id'] == $data['dokter_id']) ? 'selected' : '';
            ?>
                <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>><?php echo $row['