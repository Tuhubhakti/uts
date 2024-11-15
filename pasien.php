<?php
session_start(); // Mulai session untuk memastikan user sudah login

// Mengecek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: loginuser.php"); // Redirect ke halaman login jika belum login
    exit();
}

// Menghubungkan dengan file koneksi database
include 'koneksi.php';

// Menangani aksi CRUD

// 1. CREATE (Menambahkan Pasien)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];

    // Query untuk menambah data pasien
    $sql = "INSERT INTO pasien (nama, alamat, telepon) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nama, $alamat, $telepon);
    if ($stmt->execute()) {
        echo "Pasien berhasil ditambahkan.";
    } else {
        echo "Terjadi kesalahan saat menambahkan pasien.";
    }
    $stmt->close();
}

// 2. UPDATE (Mengedit Data Pasien)
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM pasien WHERE id = $id");
    $data = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];

    // Query untuk mengupdate data pasien
    $sql = "UPDATE pasien SET nama = ?, alamat = ?, telepon = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nama, $alamat, $telepon, $id);
    if ($stmt->execute()) {
        echo "Data pasien berhasil diperbarui.";
    } else {
        echo "Terjadi kesalahan saat memperbarui data pasien.";
    }
    $stmt->close();
}

// 3. DELETE (Menghapus Data Pasien)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Query untuk menghapus data pasien
    $sql = "DELETE FROM pasien WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Data pasien berhasil dihapus.";
    } else {
        echo "Terjadi kesalahan saat menghapus data pasien.";
    }
    $stmt->close();
}

// Menampilkan data pasien
$sql = "SELECT * FROM pasien";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pasien - Poliklinik</title>
    <link rel="stylesheet" href="style.css"> <!-- Sesuaikan dengan file CSS Anda -->
</head>
<body>
    <h1>Manajemen Pasien</h1>
    <a href="logoutuser.php">Logout</a> <!-- Link untuk logout -->

    <!-- Form untuk menambahkan pasien -->
    <h2>Tambah Pasien</h2>
    <form method="POST" action="pasien.php">
        <label for="nama">Nama Pasien</label>
        <input type="text" id="nama" name="nama" required>

        <label for="alamat">Alamat</label>
        <textarea id="alamat" name="alamat" required></textarea>

        <label for="telepon">Telepon</label>
        <input type="text" id="telepon" name="telepon" required>

        <button type="submit" name="add">Tambah Pasien</button>
    </form>

    <!-- Menampilkan data pasien -->
    <h2>Daftar Pasien</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nama']; ?></td>
                <td><?php echo $row['alamat']; ?></td>
                <td><?php echo $row['telepon']; ?></td>
                <td>
                    <!-- Tautan untuk mengedit pasien -->
                    <a href="pasien.php?edit=<?php echo $row['id']; ?>">Edit</a> |
                    <!-- Tautan untuk menghapus pasien -->
                    <a href="pasien.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus pasien ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Form untuk mengedit pasien -->
    <?php if (isset($data)): ?>
    <h2>Edit Pasien</h2>
    <form method="POST" action="pasien.php">
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

        <label for="nama">Nama Pasien</label>
        <input type="text" id="nama" name="nama" value="<?php echo $data['nama']; ?>" required>

        <label for="alamat">Alamat</label>
        <textarea id="alamat" name="alamat" required><?php echo $data['alamat']; ?></textarea>

        <label for="telepon">Telepon</label>
        <input type="text" id="telepon" name="telepon" value="<?php echo $data['telepon']; ?>" required>

        <button type="submit" name="update">Perbarui Pasien</button>
    </form>
    <?php endif; ?>

</body>
</html>

<?php
// Menutup koneksi
$conn->close();
?>