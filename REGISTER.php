<?php
// Mulai session
session_start();

// Menghubungkan dengan file koneksi database
include 'koneksi.php';

// Cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];

    // Validasi input
    $errors = [];

    // Cek apakah username sudah ada
    $result = $conn->query("SELECT * FROM users WHERE username = '$username'");
    if ($result->num_rows > 0) {
        $errors[] = "Username sudah terdaftar.";
    }

    // Cek apakah password dan konfirmasi password cocok
    if ($password !== $confirm_password) {
        $errors[] = "Password dan konfirmasi password tidak cocok.";
    }

    // Cek format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    }

    // Jika tidak ada error, simpan ke database
    if (empty($errors)) {
        // Hash password sebelum disimpan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query untuk memasukkan data ke tabel users
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $hashed_password, $email);

        if ($stmt->execute()) {
            // Arahkan pengguna ke halaman login setelah berhasil daftar
            header("Location: loginuser.php");
            exit();
        } else {
            $errors[] = "Terjadi kesalahan saat pendaftaran. Silakan coba lagi.";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Akun - Poliklinik</title>
    <link rel="stylesheet" href="style.css"> <!-- Sesuaikan dengan file CSS Anda -->
</head>
<body>
    <h1>Pendaftaran Akun Pengguna</h1>

    <!-- Tampilkan pesan error jika ada -->
    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Form Pendaftaran -->
    <form method="POST" action="registeruser.php">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Konfirmasi Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <button type="submit">Daftar</button>
    </form>

    <p>Sudah punya akun? <a href="loginuser.php">Login di sini</a></p>
</body>
</html>

<?php
// Menutup koneksi database
$conn->close();
?>