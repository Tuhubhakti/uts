$host = "localhost";        // Nama host (biasanya localhost)
$username = "root";         // Username untuk login ke MySQL
$password = "";             // Password untuk login ke MySQL (kosong jika tidak ada password)
$dbname = "db_poliklinik";  // Nama database yang akan digunakan

// Membuat koneksi menggunakan MySQLi
$conn = new mysqli($host, $username, $password, $dbname);

// Cek apakah koneksi berhasil
if ($conn->connect_error) {
    // Jika koneksi gagal, tampilkan pesan error dan hentikan script
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengatur charset untuk koneksi agar mendukung karakter khusus (misal: untuk bahasa Indonesia)
$conn->set_charset("utf8");

// Menyampaikan pesan jika koneksi berhasil (opsional, hanya untuk debugging)
echo "Koneksi berhasil!";
?>
include 'koneksi.php';  // Menyertakan koneksi.php

// Menggunakan koneksi untuk query
$sql = "SELECT * FROM dokter";  // Contoh query untuk mengambil data dokter
$result = $conn->query($sql);   // Eksekusi query

// Mengecek apakah ada hasil
if ($result->num_rows > 0) {
    // Menampilkan data dokter
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"]. " - Nama: " . $row["nama"]. " - Spesialis: " . $row["spesialis"]. "<br>";
    }
} else {
    echo "Tidak ada data dokter.";
}

// Menutup koneksi setelah query selesai
$conn->close();
?>
