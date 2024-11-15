session_start();

// Mengecek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

include 'koneksi.php';
include 'session.php'; // Pastikan user sudah login
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Poliklinik</title>
    <link rel="stylesheet" href="style.css"> <!-- Gaya untuk halaman -->
</head>
<body>
    <header>
        <h1>Selamat datang di Dashboard Poliklinik</h1>
        <p>Halo, <?php echo $_SESSION['username']; ?> | <a href="logout.php">Logout</a></p>
    </header>

    <nav>
        <ul>
            <li><a href="dokter.php">Manajemen Dokter</a></li>
            <li><a href="pasien.php">Manajemen Pasien</a></li>
            <li><a href="jadwal.php">Jadwal Konsultasi</a></li>
            <li><a href="laporan.php">Laporan Kesehatan</a></li>
            <li><a href="pengaturan.php">Pengaturan Akun</a></li>
        </ul>
    </nav>

    <section>
        <h2>Informasi Terbaru</h2>
        <p>Selamat datang di sistem informasi Poliklinik! Di sini, Anda dapat mengelola data dokter, pasien, jadwal konsultasi, dan lainnya.</p>
    </section>

    <footer>
        <p>&copy; 2024 Poliklinik</p>
    </footer>
</body>
</html>
<header>
    <h1>Selamat datang di Dashboard Poliklinik</h1>
    <p>Halo, <?php echo $_SESSION['username']; ?> | <a href="logout.php">Logout</a></p>
</header>
<nav>
    <ul>
        <li><a href="dokter.php">Manajemen Dokter</a></li>
        <li><a href="pasien.php">Manajemen Pasien</a></li>
        <li><a href="jadwal.php">Jadwal Konsultasi</a></li>
        <li><a href="laporan.php">Laporan Kesehatan</a></li>
        <li><a href="pengaturan.php">Pengaturan Akun</a></li>
    </ul>
</nav>
<section>
    <h2>Informasi Terbaru</h2>
    <p>Selamat datang di sistem informasi Poliklinik! Di sini, Anda dapat mengelola data dokter, pasien, jadwal konsultasi, dan lainnya.</p>
</section>
<footer>
    <p>&copy; 2024 Poliklinik</p>
</footer>

session_start();

// Menghapus semua data sesi
session_unset();
session_destroy();

// Redirect ke halaman login
header("Location: login.php");
exit();
?>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
}

header {
    background-color: #0044cc;
    color: white;
    padding: 10px;
    text-align: center;
}

nav {
    background-color: #333;
    overflow: hidden;
}

nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}

nav ul li {
    display: inline;
}

nav ul li a {
    color: white;
    padding: 14px 20px;
    text-decoration: none;
    display: inline-block;
}

nav ul li a:hover {
    background-color: #575757;
}

section {
    padding: 20px;
    margin: 20px;
    background-color: white;
    border-radius: 8px;
}

footer {
    text-align: center;
    padding: 10px;
    background-color: #333;
    color: white;
    position: fixed;
    width: 100%;
    bottom: 0;
}
