session_start(); // Mulai sesi

// Mengecek apakah user sudah login, jika sudah, redirect ke halaman utama
if (isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect ke halaman utama jika sudah login
    exit();
}

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'koneksi.php'; // Menghubungkan dengan database
    
    // Menangkap data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa kecocokan username dan password di database
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username); // Mengikat parameter
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Mengecek apakah username ditemukan
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Jika login berhasil, simpan data session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            
            // Redirect ke halaman utama setelah login sukses
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Username atau password salah!";
        }
    } else {
        $error_message = "Username tidak ditemukan!";
    }

    // Tutup koneksi
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Poliklinik</title>
    <link rel="stylesheet" href="style.css"> <!-- Sesuaikan dengan file CSS yang digunakan -->
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        <!-- Menampilkan pesan error jika ada -->
        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="loginuser.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>

session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$username = $_POST['username'];
$password = $_POST['password'];
$sql = "SELECT id, username, password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if (password_verify($password, $row['password'])) {
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];
    header("Location: index.php");
    exit();
}
<form action="loginuser.php" method="POST">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Login</button>
</form>

<?php if (isset($error_message)): ?>
    <div class="error"><?php echo $error_message; ?></div>
<?php endif; ?>

?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "db_poliklinik";

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
