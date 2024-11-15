<?php
// Mulai session
session_start();

// Menghapus semua data session
session_unset();  // Menghapus semua variabel session

// Menghancurkan session
session_destroy();  // Menghapus seluruh session

// Mengarahkan pengguna kembali ke halaman login
header("Location: loginuser.php");  // Sesuaikan dengan halaman login Anda
exit();
?>
<?php
// Menampilkan nama pengguna setelah login
echo "Halo, " . $_SESSION['username']; 
?>

<!-- Link Logout -->
<a href="logoutuser.php">Logout</a>