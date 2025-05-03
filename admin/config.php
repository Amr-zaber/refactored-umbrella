<?php
/* File konfigurasi database */

// Konfigurasi database
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Sesuaikan dengan username MySQL Anda
define('DB_PASSWORD', ''); // Sesuaikan dengan password MySQL Anda
define('DB_NAME', 'portfolio_db');

// Koneksi ke database MySQL
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Cek koneksi
if($conn === false){
    die("ERROR: Tidak dapat terhubung. " . mysqli_connect_error());
}

// Fungsi untuk menangani upload gambar
function uploadImage($file) {
    $target_dir = "../img/portfolio/";
    
    // Buat direktori jika belum ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_name = basename($file["name"]);
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $new_file_name = uniqid() . '.' . $imageFileType;
    $target_file = $target_dir . $new_file_name;
    
    // Cek apakah file adalah gambar
    $check = getimagesize($file["tmp_name"]);
    if($check === false) {
        return ["status" => false, "message" => "File bukan gambar."];
    }
    
    // Cek ukuran file (max 5MB)
    if ($file["size"] > 5000000) {
        return ["status" => false, "message" => "Ukuran file terlalu besar."];
    }
    
    // Izinkan format tertentu
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        return ["status" => false, "message" => "Hanya file JPG, JPEG, PNG & GIF yang diizinkan."];
    }
    
    // Upload file
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ["status" => true, "file_path" => "img/portfolio/" . $new_file_name];
    } else {
        return ["status" => false, "message" => "Terjadi kesalahan saat mengupload file."];
    }
}

// Pastikan direktori data ada untuk menyimpan file JSON
if (!file_exists("../data")) {
    mkdir("../data", 0777, true);
}

// Inisialisasi file portfolio.json jika belum ada
$portfolio_file = "../data/portfolio.json";
if(!file_exists($portfolio_file)){
    $initial_data = [
        [
            "id" => 1,
            "title" => "Website Sekolah",
            "description" => "Proyek pembuatan website untuk sekolah dengan fitur berita, galeri, dan informasi akademik.",
            "image" => "img/portfolio/project1.jpg",
            "technologies" => "HTML, CSS, JavaScript, PHP",
            "link" => "#",
            "demoLink" => "#"
        ],
        [
            "id" => 2,
            "title" => "Aplikasi Manajemen Perpustakaan",
            "description" => "Sistem manajemen perpustakaan untuk memudahkan pencatatan peminjaman dan pengembalian buku.",
            "image" => "img/portfolio/project2.jpg",
            "technologies" => "PHP, MySQL, Bootstrap",
            "link" => "#",
            "demoLink" => "#"
        ],
        [
            "id" => 3,
            "title" => "Game Edukasi",
            "description" => "Game edukasi sederhana untuk membantu siswa belajar matematika dengan cara yang menyenangkan.",
            "image" => "img/portfolio/project3.jpg",
            "technologies" => "JavaScript, HTML5 Canvas",
            "link" => "#",
            "demoLink" => "#"
        ]
    ];
    
    file_put_contents($portfolio_file, json_encode($initial_data, JSON_PRETTY_PRINT));
}
?>
