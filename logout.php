<?php
session_start();

// Menghapus semua data session (Membakar tiketnya)
session_unset();
session_destroy();

// Setelah tiket dihancurkan, lempar kembali ke halaman login
header("Location: login.php");
exit;
?>
