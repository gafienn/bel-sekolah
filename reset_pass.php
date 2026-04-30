<?php
// reset_pass.php
require_once 'config.php';

// ===============================================
// SETTING USERNAME & PASSWORD BARU DI SINI
// ===============================================
 $username_baru = "gafien";  // Ganti dengan username yang Anda mau
 $password_baru = "gafien62"; // Ganti dengan password yang Anda mau
// ===============================================

// Proses Hash Password
 $hash_password = password_hash($password_baru, PASSWORD_DEFAULT);

// Update ke Database
 $stmt = $mysqli->prepare("UPDATE users SET username = ?, password = ? WHERE id = 1");
 $stmt->bind_param("ss", $username_baru, $hash_password);

if ($stmt->execute()) {
    echo "<div style='font-family:sans-serif; max-width:500px; margin:50px auto; padding:20px; background:#d1fae5; border:1px solid #10b981; border-radius:8px;'>";
    echo "<h3 style='color:#065f46;'>Berhasil!</h3>";
    echo "<p>Akun berhasil diubah menjadi:</p>";
    echo "<ul>";
    echo "<li><strong>Username:</strong> $username_baru</li>";
    echo "<li><strong>Password:</strong> $password_baru</li>";
    echo "</ul>";
    echo "<p style='color:red; font-weight:bold;'>PENTING: Hapus file 'reset_pass.php' ini sekarang juga demi keamanan!</p>";
    echo "</div>";
} else {
    echo "Gagal mengubah data: " . $mysqli->error;
}
?>