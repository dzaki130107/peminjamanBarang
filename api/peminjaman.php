<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$database = "sts_agung"; 

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

header('Content-Type: application/json');

session_start();  

if (!isset($_SESSION['no_identitas'])) {
  echo json_encode(array('status' => 'error', 'message' => 'Anda belum login.'));
  http_response_code(401); 
  exit();
}

$no_identitas = $_SESSION['no_identitas']; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $keperluan = $_POST['keperluan'];
  $jumlah = $_POST['jumlah'];
  $kode_barang = $_POST['kode_barang'];

  if (empty($keperluan) || empty($jumlah) || empty($kode_barang)) {
    echo json_encode(array('status' => 'error', 'message' => 'Semua field harus diisi.'));
    http_response_code(400); 
    exit();
  }

  $sql = "INSERT INTO peminjaman (tgl_pinjam, tgl_kembali, no_identitas, kode_barang, jumlah, keperluan, status, id_login) 
  VALUES (NOW(), NULL, '$no_identitas', '$kode_barang', '$jumlah', '$keperluan', 'Dipinjam', '$no_identitas')";

  if ($conn->query($sql) === TRUE) {
    echo json_encode(array('status' => 'success', 'message' => 'Peminjaman berhasil ditambahkan.'));
    http_response_code(201); 
  } else {
    echo json_encode(array('status' => 'error', 'message' => 'Gagal menambahkan peminjaman: ' . $conn->error));
    http_response_code(500); 
  }
}

$sql = "SELECT kode_barang FROM barang";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $kodeBarangList = [];
    while ($row = $result->fetch_assoc()) {
        $kodeBarangList[] = $row;
    }
    echo json_encode($kodeBarangList);
} else {
    echo json_encode([]);
}

$conn->close();
?>
