<?php
require_once 'get_data.php';
$kampus = getKampus();

// Set judul halaman
$pageTitle = "About";

// Konten halaman dimulai di sini
ob_start();
?>
<div class="container mt-5">
    <h1>About</h1>
    <div class="col-md-12 mt-3">
        
    </div>
</div>


<?php
// Simpan konten ke dalam variabel $content
$content = ob_get_clean();

// Skrip khusus halaman ini
ob_start();
?>
<script>

</script>
<?php
$customScripts = ob_get_clean();

// Menyertakan template.php untuk menampilkan halaman lengkap
include 'template.php';
?>
