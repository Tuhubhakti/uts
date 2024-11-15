session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

<?php
$host = "localhost"; // host database
$username = "root";  // username
$password = "";      // password
$dbname = "db_hospital"; // nama database

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Spesialis</th>
            <th>Telepon</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nama']; ?></td>
                <td><?php echo $row['spesialis']; ?></td>
                <td><?php echo $row['telepon']; ?></td>
                <td>
                    <a href="edit_dokter.php?id=<?php echo $row['id']; ?>">Edit</a> |
                    <a href="delete_dokter.php?id=<?php echo $row['id']; ?>">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

include 'koneksi.php';
include 'session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $spesialis = $_POST['spesialis'];
    $telepon = $_POST['telepon'];

    $sql = "INSERT INTO dokter (nama, spesialis, telepon) VALUES ('$nama', '$spesialis', '$telepon')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Dokter berhasil ditambahkan.";
        header("Location: Dokter.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="POST" action="tambah_dokter.php">
    <label>Nama:</label>
    <input type="text" name="nama" required><br>

    <label>Spesialis:</label>
    <input type="text" name="spesialis" required><br>

    <label>Telepon:</label>
    <input type="text" name="telepon" required><br>

    <button type="submit">Tambah Dokter</button>
</form>
include 'koneksi.php';
include 'session.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM dokter WHERE id = $id";
    $result = $conn->query($sql);
    $dokter = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $spesialis = $_POST['spesialis'];
    $telepon = $_POST['telepon'];

    $sql = "UPDATE dokter SET nama='$nama', spesialis='$spesialis', telepon='$telepon' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Dokter berhasil diperbarui.";
        header("Location: Dokter.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="POST" action="edit_dokter.php?id=<?php echo $dokter['id']; ?>">
    <label>Nama:</label>
    <input type="text" name="nama" value="<?php echo $dokter['nama']; ?>" required><br>

    <label>Spesialis:</label>
    <input type="text" name="spesialis" value="<?php echo $dokter['spesialis']; ?>" required><br>

    <label>Telepon:</label>
    <input type="text" name="telepon" value="<?php echo $dokter['telepon']; ?>" required><br>

    <button type="submit">Perbarui Dokter</button>
</form>

include 'koneksi.php';
include 'session.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM dokter WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo "Dokter berhasil dihapus.";
        header("Location: Dokter.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>