<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Menggunakan koneksi ke database
$koneksi = mysqli_connect('localhost', 'root', '', 'sts_agung');

// Mendapatkan data dari body POST request
$data = json_decode(file_get_contents("php://input"));

// Mendapatkan data no_identitas, username, dan password dari request
$no_identitas = $data->no_identitas;
$username = $data->nama;
$password = md5($data->password); // Menggunakan hash MD5 untuk enkripsi password

// Query untuk mencari pengguna dengan no_identitas, username, dan password yang cocok
$query = "SELECT * FROM user WHERE no_identitas = '$no_identitas' AND username = '$username' AND password = '$password'";
$result = mysqli_query($koneksi, $query);

// Memeriksa apakah pengguna ditemukan atau tidak
if(mysqli_num_rows($result) == 1) {
    $response = array('status' => 'success', 'message' => 'Login berhasil');
} else {
    $response = array('status' => 'error', 'message' => 'Login gagal. No Identitas, username, atau password salah');
}

echo json_encode($response);
?>
