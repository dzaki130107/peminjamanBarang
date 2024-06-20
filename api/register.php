<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Menggunakan koneksi ke database
$koneksi = mysqli_connect('localhost', 'root', '', 'sts_agung');

// Memeriksa koneksi
if (!$koneksi) {
    die("Connection failed: " . mysqli_connect_error());
}

// Mendapatkan data dari body POST request
$data = json_decode(file_get_contents("php://input"));

// Memeriksa apakah data dari POST request tersedia
if(isset($data->no_identitas) && isset($data->nama) && isset($data->password)) {
    // Mendapatkan data no_identitas, nama, dan password dari request
    $no_identitas = $data->no_identitas;
    $nama = $data->nama;
    $password = $data->password;

    // Query untuk memeriksa apakah pengguna sudah terdaftar berdasarkan no_identitas
    $query_check_user = "SELECT * FROM user WHERE no_identitas = '$no_identitas'";
    $result_check_user = mysqli_query($koneksi, $query_check_user);

    // Memeriksa apakah pengguna sudah terdaftar
    if(mysqli_num_rows($result_check_user) > 0) {
        $response = array('status' => 'error', 'message' => 'Pengguna dengan no_identitas tersebut sudah terdaftar');
    } else {
        // Jika pengguna belum terdaftar, lakukan proses pendaftaran
        // Lakukan hashing password sebelum menyimpannya ke dalam database
        $hashed_password = md5($password);

        // Query untuk menambahkan pengguna baru ke dalam database
        $query_register_user = "INSERT INTO user (no_identitas, nama, status, username, password, role) VALUES ('$no_identitas', '$nama', 'member', '$nama', '$hashed_password', 'member')";
        $result_register_user = mysqli_query($koneksi, $query_register_user);

        if($result_register_user) {
            $response = array('status' => 'success', 'message' => 'Pendaftaran berhasil');
        } else {
            $response = array('status' => 'error', 'message' => 'Pendaftaran gagal. Silakan coba lagi.');
        }
    }
} else {
    $response = array('status' => 'error', 'message' => 'Data tidak lengkap.');
}

echo json_encode($response);

// Menutup koneksi database
mysqli_close($koneksi);
?>
