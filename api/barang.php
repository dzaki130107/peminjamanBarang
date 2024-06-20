<?php
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "sts_agung";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, nama_barang, image FROM barang";
$result = $conn->query($sql);

$barang = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $barang[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($barang);
?>